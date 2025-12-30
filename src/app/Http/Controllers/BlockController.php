<?php

namespace App\Http\Controllers;

use App\Models\HomepageBlock;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function show(HomepageBlock $block)
    {
        $block->load('products.reviews'); // Eager load products and their reviews
        return view('pages.block', compact('block'));
    }
}
