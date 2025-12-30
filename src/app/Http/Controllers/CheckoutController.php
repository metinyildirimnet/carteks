<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\SmsService; // Added
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $smsService; // Added

    public function __construct(CartService $cartService, SmsService $smsService) // Modified
    {
        $this->cartService = $cartService;
        $this->smsService = $smsService; // Added
    }

    /**
     * Display the checkout page.
     */
    public function show(): View
    {
        $cartItems = $this->cartService->get();
        $cartTotal = $this->cartService->getTotal();
        $bankTransferDiscount = (int) Setting::where('key', 'havale_odeme_indirimi')->value('value') ?? 0;

        if ($cartItems->isEmpty()) {
            return view('pages.empty_cart');
        }

        $cartProductIds = $cartItems->pluck('product_id')->toArray();

        $recommendedProducts = collect();
        $productsInCart = \App\Models\Product::with('recommendedProducts')->whereIn('id', $cartProductIds)->get();

        foreach ($productsInCart as $product) {
            $recommendedProducts = $recommendedProducts->merge($product->recommendedProducts);
        }

        $recommendedProducts = $recommendedProducts->unique('id')->whereNotIn('id', $cartProductIds);


        return view('pages.checkout', compact('cartItems', 'cartTotal', 'bankTransferDiscount', 'recommendedProducts'));
    }

    /**
     * Display the order success page.
     */
    public function success(): View
    {
        $order = null;
        if (session()->has('last_order_id')) {
            $orderId = session('last_order_id');
            $order = Order::with('items.product', 'discounts')->find($orderId); // Corrected relationship name
            // Clear the flashed session data after retrieving
            //session()->forget('last_order_id');
        }

        if (!$order || $order->user_id !== Auth::id()) {
            // Redirect to home or show an error if order not found or not for current user
            // For now, just return the view with no order data, or you can redirect
            return view('pages.checkout_success', ['order' => null]);
        }

        return view('pages.checkout_success', compact('order'));
    }

    /**
     * Create an incomplete order (for tracking purposes).
     */
    public function createIncompleteOrder(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $cartItems = $this->cartService->get();
        $cartTotal = $this->cartService->getTotal();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Sepetiniz boş.'], 400);
        }

        DB::beginTransaction();
        try {
            // Generate unique order code
            do {
                $orderCode = strtoupper(Str::random(8));
            } while (Order::where('order_code', $orderCode)->exists());

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => Auth::check() ? Auth::id() : null,
                'total_amount' => $cartTotal, // Set initial total amount from cart
                'order_status_id' => OrderStatus::where('name', 'Incomplete')->first()->id ?? 1, // Assuming 1 for Incomplete
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'customer_name' => $request->name,
                'customer_phone' => $request->phone,
                // Other fields will be updated in placeOrder
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'], // Price at the time of order
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Incomplete order created.', 'order_id' => $order->id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Incomplete order creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Geçici sipariş oluşturulurken bir hata oluştu.'], 500);
        }
    }

    /**
     * Process the order.
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Ensure order_id exists
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'payment_method' => 'required|string|in:cash_on_delivery,card_on_delivery,bank_transfer,credit_card',
        ]);

        $cartItems = $this->cartService->get();
        $cartTotal = $this->cartService->getTotal();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Sepetiniz boş.'], 400);
        }

        DB::beginTransaction();
        try {
            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json(['message' => 'Sipariş bulunamadı.'], 404);
            }

            $order->items()->delete();

            $bankTransferDiscountPercentage = (int) Setting::where('key', 'havale_odeme_indirimi')->value('value') ?? 0;
            $discountAmount = 0;
            $finalOrderTotal = $cartTotal;

            if ($request->payment_method === 'bank_transfer' && $bankTransferDiscountPercentage > 0) {
                $discountAmount = $cartTotal * ($bankTransferDiscountPercentage / 100);
                $finalOrderTotal = $cartTotal - $discountAmount;
            }

            // Update the existing order
            $order->update([
                'total_amount' => $finalOrderTotal,
                'discount_amount' => $discountAmount, // Save the calculated discount amount
                'order_status_id' => OrderStatus::where('name', 'Completed')->first()->id ?? 2, // Assuming 2 for Completed
                'customer_address' => $request->address,
                'customer_city' => $request->city,
                'customer_district' => $request->district,
                'payment_method' => $request->payment_method,
            ]);

            // Create OrderDiscount entry if applicable
            if ($discountAmount > 0) {
                $order->discounts()->create([
                    'type' => 'bank_transfer_discount',
                    'description' => "%{$bankTransferDiscountPercentage} Havale Ödeme İndirimi",
                    'amount' => $discountAmount,
                    'percentage' => $bankTransferDiscountPercentage,
                ]);
            }

            // Add order items (if not already added, or clear and re-add if cart can change)
            // For simplicity, assuming order items are added only on final placeOrder
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'], // Price at the time of order
                ]);
            }

            $this->cartService->clear(); // Clear cart after successful order

            DB::commit();
            session()->flash('last_order_id', $order->id); // Store order ID in session

            // --- SMS Sending Logic ---
            $smsTemplate = Setting::where('key', 'order_completion_sms_template')->value('value');
            $customerPhone = $order->customer_phone; // Assuming customer_phone is stored in the order

            if ($smsTemplate && $customerPhone) {
                $smsData = [
                    'order_code' => $order->order_code,
                    'customer_name' => $order->customer_name,
                    'total_amount' => number_format($order->total_amount, 2),
                    // Add more placeholders as needed
                ];
                $parsedSms = $this->smsService->parseTemplate($smsTemplate, $smsData);
                $this->smsService->sendSms($customerPhone, $parsedSms);
            }
            // --- End SMS Sending Logic ---

            return response()->json([
                'message' => 'Siparişiniz başarıyla alındı!',
                'order_id' => $order->id,
                'redirect' => route('checkout.success')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order finalization failed: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Sipariş tamamlanırken bir hata oluştu. Lütfen tekrar deneyin.'], 500);
        }
    }

    /**
     * Resume an incomplete order.
     */
    public function resumeOrder(Order $order): RedirectResponse
    {
        // Check if the order is actually incomplete
        $incompleteStatus = OrderStatus::where('name', 'Tamamlanmayan')->first();
        if (!$incompleteStatus || $order->order_status_id !== $incompleteStatus->id) {
            // Redirect to home or show an error if the order is not incomplete
            return redirect()->route('home')->with('error', 'Bu sipariş tamamlanmış veya geçersiz.');
        }

        // Load items from the incomplete order into the cart
        $this->cartService->loadFromOrder($order);

        // Redirect to the checkout page
        return redirect()->route('checkout.show')->with('success', 'Siparişiniz sepete yüklendi, lütfen tamamlayın.');
    }
}
