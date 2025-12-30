<?php

namespace App\Providers;

use App\Http\View\Composers\MenuComposer;
use App\Http\View\Composers\SettingsComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        View::composer(['admin.layouts.new_app', 'layouts.app', 'auth.login'], SettingsComposer::class);
        
        // Attach MenuComposer to the main app layout, which should contain the footer.
        View::composer('layouts.app', MenuComposer::class);
    }
}
