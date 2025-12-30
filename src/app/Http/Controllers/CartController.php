<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $this->cartService->add($product, $request->quantity);

        return response()->json([
            'message' => 'Ürün sepete eklendi!',
            'cart' => $this->cartService->get()->values()->all(),
            'total' => $this->cartService->getTotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $this->cartService->remove($request->product_id);

        return response()->json([
            'message' => 'Ürün sepetten kaldırıldı!',
            'cart' => $this->cartService->get()->values()->all(),
            'total' => $this->cartService->getTotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cartService->update($request->product_id, $request->quantity);

        return response()->json([
            'message' => 'Sepet güncellendi!',
            'cart' => $this->cartService->get()->values()->all(),
            'total' => $this->cartService->getTotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * Get the current cart contents.
     */
    public function get(): JsonResponse
    {
        return response()->json([
            'cart' => $this->cartService->get()->values()->all(),
            'total' => $this->cartService->getTotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * Clear the cart.
     */
    public function clear(): JsonResponse
    {
        $this->cartService->clear();

        return response()->json([
            'message' => 'Sepet temizlendi!',
            'cart' => $this->cartService->get()->values()->all(),
            'total' => $this->cartService->getTotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    /**
     * Add a product to the cart and redirect to checkout.
     */
    public function buyNow(Product $product)
    {
        $this->cartService->add($product, 1);

        return redirect()->route('checkout.show');
    }
}
