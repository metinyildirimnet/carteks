<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\HomepageBlock;
use App\Models\MarqueeItem; // Added
use App\Models\Order; // Added
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const USAGE_AREAS = ['Otomotiv', 'Ev', 'Ofis', 'Endüstriyel'];

    public function index()
    {
        // Fetch active homepage blocks, eager-loading their products and product images
        $homepageBlocks = HomepageBlock::where('is_active', true)
                                    ->orderBy('sort_order')
                                    ->with(['products.images', 'products.reviews']) // Eager load products, their images, and reviews
                                    ->get();

        $slides = Slide::where('is_active', true)->orderBy('sort_order')->get();

        // For now, we'll still pass all products and categories, but homepageBlocks will be used for the main display
        $products = Product::with('images')->latest()->take(8)->get(); // Keep for other sections if needed
        $categories = Category::all(); // Keep for other sections if needed

        return view('pages.home', compact('homepageBlocks', 'products', 'categories', 'slides'));
    }

    /**
     * Display the specified product by its slug.
     */
    public function showProductBySlug($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $product->increment('view_count');

        $relationsToLoad = ['images', 'category', 'reviews'];
        if ($product->is_package) {
            $relationsToLoad[] = 'containedProducts.images';
        }

        $product->load($relationsToLoad);

        return view('pages.product_detail', compact('product'));
    }

    /**
     * Display a listing of all products.
     */
    public function allProducts(Request $request)
    {
        $query = Product::isStandardProduct()->with('images', 'reviews');

        $selectedUsageAreas = $request->input('usage_areas', []);
        if (!is_array($selectedUsageAreas)) {
            $selectedUsageAreas = [$selectedUsageAreas]; // Ensure it's an array even if single value
        }

        if (!empty($selectedUsageAreas)) {
            foreach ($selectedUsageAreas as $area) {
                $query->whereJsonContains('usage_areas', $area);
            }
        }

        $products = $query->latest()->paginate(12); // Paginate with 12 products per page
        $usageAreas = self::USAGE_AREAS; // Pass all available usage areas to the view

        if ($request->ajax()) {
            $productGridHtml = '';
            foreach ($products as $product) {
                $productGridHtml .= view('partials._product_card', compact('product'))->render();
            }
            $paginationHtml = $products->links()->toHtml();

            return response()->json([
                'product_grid_html' => $productGridHtml,
                'pagination_html' => $paginationHtml,
            ]);
        }

        return view('pages.products_index', compact('products', 'usageAreas', 'selectedUsageAreas'));
    }

    /**
     * Display a listing of all packages.
     */
    public function allPackages(Request $request)
    {
        $query = Product::isPackage()->with('images', 'reviews');

        $selectedUsageAreas = $request->input('usage_areas', []);
        if (!is_array($selectedUsageAreas)) {
            $selectedUsageAreas = [$selectedUsageAreas]; // Ensure it's an array even if single value
        }

        if (!empty($selectedUsageAreas)) {
            foreach ($selectedUsageAreas as $area) {
                $query->whereJsonContains('usage_areas', $area);
            }
        }

        $packages = $query->latest()->paginate(12);
        $usageAreas = self::USAGE_AREAS; // Pass all available usage areas to the view

        if ($request->ajax()) {
            $productGridHtml = '';
            foreach ($packages as $package) {
                $productGridHtml .= view('partials._product_card', ['product' => $package])->render();
            }
            $paginationHtml = $packages->links()->toHtml();

            return response()->json([
                'product_grid_html' => $productGridHtml,
                'pagination_html' => $paginationHtml,
            ]);
        }

        return view('pages.packages_index', compact('packages', 'usageAreas', 'selectedUsageAreas'));
    }

    /**
     * Display the order tracking page and search for an order.
     */
    public function orderTracking(Request $request)
    {
        $order = null;
        $errorMessage = null;

        if ($request->has('order_code')) {
            $orderCode = $request->input('order_code');
            $order = Order::where('order_code', $orderCode)
                          ->with('items.product.images', 'status')
                          ->first();

            if (!$order) {
                $errorMessage = 'Belirtilen sipariş koduyla eşleşen bir sipariş bulunamadı.';
            }
        }

        return view('pages.order_tracking', compact('order', 'errorMessage'));
    }
}
