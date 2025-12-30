<?php

namespace App\Http\View\Composers;

use App\Models\MenuGroup;
use Illuminate\View\View;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Eager load items and their polymorphic relationships to prevent N+1 issues
        $footerMenus = MenuGroup::with('items.linkable')->get();
        
        $view->with('footerMenus', $footerMenus);
    }
}
