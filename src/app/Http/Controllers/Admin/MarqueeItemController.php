<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarqueeItem;
use Illuminate\Http\Request;

class MarqueeItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marqueeItems = MarqueeItem::orderBy('sort_order')->get();
        return view('admin.marquee_items.index', compact('marqueeItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.marquee_items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        MarqueeItem::create($validatedData);

        if ($request->ajax()) {
            return response()->json(['message' => 'Kayan yazı öğesi başarıyla eklendi.', 'redirect' => route('admin.marquee-items.index')]);
        }

        return redirect()->route('admin.marquee-items.index')->with('success', 'Kayan yazı öğesi başarıyla eklendi.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MarqueeItem $marqueeItem)
    {
        return view('admin.marquee_items.edit', compact('marqueeItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MarqueeItem $marqueeItem)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $marqueeItem->update($validatedData);

        if ($request->ajax()) {
            return response()->json(['message' => 'Kayan yazı öğesi başarıyla güncellendi.', 'redirect' => route('admin.marquee-items.index')]);
        }

        return redirect()->route('admin.marquee-items.index')->with('success', 'Kayan yazı öğesi başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MarqueeItem $marqueeItem)
    {
        $marqueeItem->delete();
        return redirect()->route('admin.marquee-items.index')->with('success', 'Kayan yazı öğesi başarıyla silindi.');
    }
}
