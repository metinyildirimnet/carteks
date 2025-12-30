<?php

namespace App\Http\View\Composers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class SettingsComposer
{
    public function compose(View $view)
    {
        // Check if the settings table exists to avoid errors during migrations
        if (Schema::hasTable('settings')) {
            $settings = Setting::all()->keyBy('key');
            $view->with('settings', $settings);
        } else {
            // Provide an empty collection or default values if the table doesn't exist
            $view->with('settings', collect());
        }
    }
}
