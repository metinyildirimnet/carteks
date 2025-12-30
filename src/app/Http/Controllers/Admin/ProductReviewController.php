<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = ProductReview::with('product', 'user')->latest()->paginate(10);
        return view('admin.product_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $users = User::all(); // Assuming User model exists and reviews can be linked to users
        return view('admin.product_reviews.create', compact('products', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'product_id' => 'required|exists:products,id',
                'user_id' => 'nullable|exists:users,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
                'is_approved' => 'boolean',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $validatedData['is_approved'] = $request->has('is_approved');

        ProductReview::create($validatedData);

        return redirect()->route('admin.product-reviews.index')->with('success', 'Değerlendirme başarıyla eklendi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReview $productReview)
    {
        // Not typically used for admin CRUD, but can be implemented if needed
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReview $productReview)
    {
        $productReview->load('product', 'user');
        return view('admin.product_reviews.edit', compact('productReview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductReview $productReview)
    {
        try {
            $validatedData = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
                'is_approved' => 'boolean',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $validatedData['is_approved'] = $request->has('is_approved');

        $productReview->update($validatedData);

        return redirect()->route('admin.product-reviews.index')->with('success', 'Değerlendirme başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductReview $productReview)
    {
        $productReview->delete();
        return redirect()->route('admin.product-reviews.index')->with('success', 'Değerlendirme başarıyla silindi.');
    }
}
