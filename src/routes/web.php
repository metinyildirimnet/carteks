<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\HomepageBlockController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\MarqueeItemController; // Added
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController; // Add this line
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan; // Added

// Auth Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;


Route::get('/', [HomeController::class, 'index'])->name('home');

// Page display route
Route::get('/sayfa/{page:slug}', [PageController::class, 'show'])->name('page.show');

// Product & Package Listing
Route::get('/urunler', [HomeController::class, 'allProducts'])->name('products.index');
Route::get('/paketler', [HomeController::class, 'allPackages'])->name('packages.index');

// Product & Package Detail
Route::get('/urunler/{slug}', [HomeController::class, 'showProductBySlug'])->name('products.show');
Route::get('/paketler/{slug}', [HomeController::class, 'showProductBySlug'])->name('packages.show');

Route::get('/blok/{block:slug}', [BlockController::class, 'show'])->name('block.show');

// Order Tracking
Route::get('/siparis-takip', [HomeController::class, 'orderTracking'])->name('order.tracking');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('buy-now/{product}', [CartController::class, 'buyNow'])->name('buyNow');
    Route::post('add', [CartController::class, 'add'])->name('add');
    Route::post('remove', [CartController::class, 'remove'])->name('remove');
    Route::post('update', [CartController::class, 'update'])->name('update');
    Route::get('get', [CartController::class, 'get'])->name('get'); // For AJAX update of sidebar
    Route::post('clear', [CartController::class, 'clear'])->name('clear');
});

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/checkout/incomplete', [CheckoutController::class, 'createIncompleteOrder'])->name('checkout.createIncompleteOrder');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success'); // New route
Route::get('/checkout/resume/{order}', [CheckoutController::class, 'resumeOrder'])
    ->name('checkout.resume')
    ->middleware('signed'); // Re-add signed middleware

Route::get('/admin', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('packages', [ProductController::class, 'packageIndex'])->name('packages.index');
    Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductController::class)->except(['show']); // show is handled by public routes
    Route::resource('categories', CategoryController::class);
    Route::resource('homepage-blocks', HomepageBlockController::class);
    Route::post('homepage-blocks/reorder', [HomepageBlockController::class, 'reorder'])->name('homepage-blocks.reorder');
    Route::post('homepage-blocks/{homepageBlock}/reorder-products', [HomepageBlockController::class, 'reorderProducts'])->name('homepage-blocks.reorderProducts');
    Route::post('orders/bulk-delete', [OrderController::class, 'bulkDelete'])->name('orders.bulk-delete');
    Route::post('orders/bulk-update-status', [OrderController::class, 'bulkUpdateStatus'])->name('orders.bulk-update-status');
    Route::get('orders/trashed', [OrderController::class, 'trashed'])->name('orders.trashed');
    Route::post('orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
    Route::delete('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
    Route::resource('order-statuses', \App\Http\Controllers\Admin\OrderStatusController::class);
    Route::resource('product-reviews', \App\Http\Controllers\Admin\ProductReviewController::class);
    Route::resource('marquee-items', MarqueeItemController::class); // Added
    Route::resource('pages', AdminPageController::class);

    // Menu Management Routes
    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::put('menus/groups/{menuGroup}', [MenuController::class, 'updateGroup'])->name('menus.group.update');
    Route::post('menus/groups/{menuGroup}/items', [MenuController::class, 'storeItem'])->name('menus.item.store');
    Route::put('menus/items/{menuItem}', [MenuController::class, 'updateItem'])->name('menus.item.update');
    Route::delete('menus/items/{menuItem}', [MenuController::class, 'destroyItem'])->name('menus.item.destroy');
    Route::post('menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');

    // Slider Management Routes
    Route::post('sliders/reorder', [SliderController::class, 'reorder'])->name('sliders.reorder');
    Route::resource('sliders', SliderController::class)->except(['show']);
});

// Authentication Routes (Manual Breeze-like setup)
Route::middleware('guest')->group(function () {
    //Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    //Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    //Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    //Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    //Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    //Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationNotificationController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/run-incomplete-order-reminders', function () {
    // Protect this route with a secret key
    if (request('key') !== env('CRON_SECRET_KEY')) {
        abort(403, 'Unauthorized access.');
    }

    Artisan::call('app:send-incomplete-order-reminders');

    return "Incomplete order reminders command executed.";
})->name('cron.incomplete_reminders');
