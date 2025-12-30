<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private const USAGE_AREAS = ['Otomotiv', 'Ev', 'Ofis', 'Endüstriyel'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::isStandardProduct()->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Display a listing of the package resources.
     */
    public function packageIndex()
    {
        $products = Product::isPackage()->latest()->get();
        // Assuming you will create a new view for packages
        return view('admin.products.package_index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $products = Product::isStandardProduct()->get(); // For package selection
        $usageAreas = self::USAGE_AREAS; // Pass usage areas to the view
        return view('admin.products.create', compact('categories', 'products', 'usageAreas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'discounted_price' => 'nullable|numeric|lt:price',
                'category_id' => 'nullable|exists:categories,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'contained_products' => 'nullable|array', // Array of objects with id and quantity
                'usage_areas' => 'nullable|array', // Add validation for usage_areas
                'usage_areas.*' => 'string|in:' . implode(',', self::USAGE_AREAS), // Validate each item in the array
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $validatedData['is_package'] = $request->has('is_package');
        $product = Product::create($validatedData);

        if ($product->is_package) {
            $this->syncContainedProducts($product, $request->input('contained_products', []));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'uploads');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->ajax()) {
            $redirectRoute = $product->is_package ? route('admin.packages.index') : route('admin.products.index');
            return response()->json(['success' => 'Ürün başarıyla oluşturuldu.', 'redirect' => $redirectRoute]);
        }

        $redirectRoute = $product->is_package ? 'admin.packages.index' : 'admin.products.index';
        return redirect()->route($redirectRoute)->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not typically used for admin CRUD, but can be implemented if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product) // Route model binding
    {
        $categories = Category::all();
        $products = Product::isStandardProduct()->where('id', '!=', $product->id)->get();
        $recommendedProductIds = $product->recommendedProducts()->pluck('id')->toArray();
        $usageAreas = self::USAGE_AREAS; // Pass usage areas to the view

        $containedProductsData = [];
        if ($product->is_package) {
            $containedProductsData = $product->containedProducts()->get()->mapWithKeys(function ($p) {
                return [$p->id => ['quantity' => $p->pivot->quantity]];
            });
        }

        return view('admin.products.edit', compact('product', 'categories', 'products', 'recommendedProductIds', 'containedProductsData', 'usageAreas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'discounted_price' => 'nullable|numeric|lt:price',
                'category_id' => 'nullable|exists:categories,id',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
                'recommended_products' => 'nullable|array',
                'recommended_products.*' => 'exists:products,id',
                'contained_products' => 'nullable|array',
                'usage_areas' => 'nullable|array', // Add validation for usage_areas
                'usage_areas.*' => 'string|in:' . implode(',', self::USAGE_AREAS), // Validate each item in the array
            ]);
        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json(['errors' => $e->errors()], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $validatedData['is_package'] = $request->has('is_package');
        $product->update($validatedData);

        if ($product->is_package) {
            $this->syncContainedProducts($product, $request->input('contained_products', []));
        } else {
            $product->containedProducts()->sync([]);
        }

        if ($request->has('recommended_products')) {
            $product->recommendedProducts()->sync($request->input('recommended_products', []));
        } else {
            $product->recommendedProducts()->sync([]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'uploads');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        if ($request->ajax()) {
            $redirectRoute = $product->is_package ? route('admin.packages.index') : route('admin.products.index');
            return response()->json(['success' => 'Ürün başarıyla güncellendi.', 'redirect' => $redirectRoute]);
        }

        $redirectRoute = $product->is_package ? 'admin.packages.index' : 'admin.products.index';
        return redirect()->route($redirectRoute)->with('success', 'Ürün başarıyla güncellendi.');
    }

    /**
     * Temporarily delete the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete(); // This will now soft delete

        if (request()->ajax()) {
            return response()->json(['success' => 'Ürün başarıyla silindi.', 'redirect' => url()->previous()]);
        }

        return redirect()->back()->with('success', 'Ürün başarıyla silindi.');
    }

    private function syncContainedProducts(Product $package, array $containedProductsData)
    {
        $syncData = [];
        if(is_array($containedProductsData)) {
            foreach ($containedProductsData as $item) {
                // Ensure item is an array and has the required keys
                if (is_array($item) && isset($item['id']) && isset($item['quantity'])) {
                    $syncData[$item['id']] = ['quantity' => $item['quantity']];
                }
            }
        }
        $package->containedProducts()->sync($syncData);
    }

    /**
     * Display a listing of the trashed resources.
     */
    public function trashed()
    {
        $products = Product::onlyTrashed()->latest()->get();
        return view('admin.products.trashed', compact('products'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return redirect()->route('admin.products.trashed')->with('success', 'Ürün başarıyla geri yüklendi.');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        // Delete associated images from storage before force deleting the product
        foreach ($product->images as $image) {
            Storage::disk('uploads')->delete($image->image_path);
        }
        $product->forceDelete();
        return redirect()->route('admin.products.trashed')->with('success', 'Ürün kalıcı olarak silindi.');
    }
}
