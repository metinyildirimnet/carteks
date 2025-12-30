<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\MarqueeItem; // Added

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $marqueeItems = MarqueeItem::where('is_active', true)->orderBy('sort_order')->get();
            $view->with('marqueeItems', $marqueeItems);
        });
    }
}
