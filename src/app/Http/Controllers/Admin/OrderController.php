<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user', 'status')->latest()->get();
        $orderStatuses = OrderStatus::all();
        return view('admin.orders.index', compact('orders', 'orderStatuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'status', 'discounts');
        $orderStatuses = OrderStatus::all(); // Fetch all order statuses
        return view('admin.orders.show', compact('order', 'orderStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'customer_city' => 'required|string|max:255',
            'customer_district' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'order_status_id' => 'required|exists:order_statuses,id',
            'items.*.quantity' => 'sometimes|integer|min:1',
            'remove_items' => 'nullable|array',
            'remove_items.*' => 'exists:order_items,id',
        ]);

        DB::beginTransaction();
        try {
            // Handle item removals first
            if ($request->has('remove_items')) {
                if ($order->items()->count() <= count($request->remove_items)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Siparişten son kalemi silemezsiniz.');
                }
                OrderItem::whereIn('id', $request->remove_items)->delete();
            }

            // Update quantities for remaining items
            if ($request->has('items')) {
                foreach ($request->items as $itemId => $itemData) {
                    $orderItem = OrderItem::find($itemId);
                    if ($orderItem && $orderItem->order_id === $order->id) {
                        $orderItem->update(['quantity' => $itemData['quantity']]);
                    }
                }
            }

            // Refresh the items relationship after potential deletions
            $order->refresh();

            // Recalculate total amount
            $newTotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            // Update order details including the new total
            $order->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'customer_city' => $request->customer_city,
                'customer_district' => $request->customer_district,
                'payment_method' => $request->payment_method,
                'order_status_id' => $request->order_status_id,
                'total_amount' => $newTotal,
            ]);

            DB::commit();
            return redirect()->route('admin.orders.show', $order->order_code)->with('success', 'Sipariş başarıyla güncellendi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Sipariş güncellenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    // You might want to add methods for adding new items to an order via AJAX if needed
    // public function addItem(Request $request, Order $order) { ... }

    /**
     * Remove the specified resources from storage.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id',
        ]);

        Order::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Seçili siparişler başarıyla silindi.']);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:orders,id',
            'order_status_id' => 'required|exists:order_statuses,id',
        ]);

        Order::whereIn('id', $request->ids)->update(['order_status_id' => $request->order_status_id]);

        return response()->json(['message' => 'Seçili siparişlerin durumları başarıyla güncellendi.']);
    }

    /**
     * Temporarily delete the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Sipariş başarıyla silindi.');
    }

    /**
     * Display a listing of the trashed resources.
     */
    public function trashed()
    {
        $orders = Order::onlyTrashed()->with('user', 'status')->latest()->get();
        return view('admin.orders.trashed', compact('orders'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return redirect()->route('admin.orders.trashed')->with('success', 'Sipariş başarıyla geri yüklendi.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        // Note: Related items (order_items) are not automatically deleted
        // unless the foreign key has a cascade on delete, which is good here
        // as we might not want to delete them if the order is force deleted.
        // If they should be deleted, add: $order->items()->delete();
        $order->forceDelete();
        return redirect()->route('admin.orders.trashed')->with('success', 'Sipariş kalıcı olarak silindi.');
    }
}
