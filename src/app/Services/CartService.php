<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order; // Added
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'cart';

    /**
     * Get all items in the cart.
     */
    public function get(): Collection
    {
        $cart = Session::get(self::CART_SESSION_KEY, collect());

        return $cart->map(function ($item) {
            $item['price'] = (float) $item['price'];
            return $item;
        });
    }

    /**
     * Add a product to the cart.
     */
    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->get();

        if ($cart->has($product->id)) {
            $cartItem = $cart->get($product->id);
            $cartItem['quantity'] += $quantity;
        } else {
            $cartItem = [
                'product_id' => $product->id,
                'name' => $product->title,
                'price' => (float)($product->discounted_price ?? $product->price),
                'quantity' => $quantity,
                'image' => $product->images->first()->image_path ?? null,
            ];
        }

        $cart->put($product->id, $cartItem);
        Session::put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(int $productId): void
    {
        $cart = $this->get();
        $cart->forget($productId);
        Session::put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(int $productId, int $quantity): void
    {
        $cart = $this->get();

        if ($cart->has($productId)) {
            $cartItem = $cart->get($productId);
            $cartItem['quantity'] = $quantity;
            if ($cartItem['quantity'] <= 0) {
                $cart->forget($productId);
            } else {
                $cart->put($productId, $cartItem);
            }
            Session::put(self::CART_SESSION_KEY, $cart);
        }
    }

    /**
     * Get the total price of items in the cart.
     */
    public function getTotal(): float
    {
        return $this->get()->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
    }

    /**
     * Get the number of items in the cart.
     */
    public function count(): int
    {
        return $this->get()->sum('quantity');
    }

    /**
     * Load items from an Order into the cart.
     */
    public function loadFromOrder(Order $order): void
    {
        $this->clear(); // Clear current cart

        $cart = collect();
        foreach ($order->items as $orderItem) {
            // Ensure product exists and is not soft-deleted
            $product = Product::find($orderItem->product_id);
            if ($product) {
                $cartItem = [
                    'product_id' => $product->id,
                    'name' => $product->title,
                    'price' => (float)($product->discounted_price ?? $product->price),
                    'quantity' => $orderItem->quantity,
                    'image' => $product->images->first()->image_path ?? null,
                ];
                $cart->put($product->id, $cartItem);
            }
        }
        Session::put(self::CART_SESSION_KEY, $cart);
    }
}
