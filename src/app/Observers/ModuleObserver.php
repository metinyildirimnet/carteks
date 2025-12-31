<?php

namespace App\Observers;

use App\Models\Module;
use Illuminate\Support\Facades\Cache;

class ModuleObserver
{
    /**
     * Handle the Module "updated" event.
     *
     * @param  \App\Models\Module  $module
     * @return void
     */
    public function updated(Module $module)
    {
        // Clear the specific cache key used by the Module model's helper methods.
        Cache::forget('all_modules');
    }

    /**
     * Handle the Module "saved" event.
     *
     * This will catch both create and update events.
     *
     * @param  \App\Models\Module  $module
     * @return void
     */
    public function saved(Module $module)
    {
        Cache::forget('all_modules');
    }

    /**
     * Handle the Module "deleted" event.
     *
     * @param  \App\Models\Module  $module
     * @return void
     */
    public function deleted(Module $module)
    {
        Cache::forget('all_modules');
    }
}
