<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the specified page.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\View\View
     */
    public function show(Page $page)
    {
        // Abort if the page is not published
        if (!$page->is_published) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }
}
