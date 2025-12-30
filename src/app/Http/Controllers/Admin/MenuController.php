<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menuGroups = MenuGroup::with('items')->get();
        $linkableTypes = [
            'custom' => 'Özel Link',
            'page' => 'Sayfa',
            'category' => 'Kategori',
            'product' => 'Ürün',
            'package' => 'Paket',
        ];

        // Eager load selectable models for the form
        $pages = Page::where('is_published', true)->get(['id', 'title']);
        $categories = Category::all(['id', 'name']);
        $products = Product::where('is_package', false)->get(['id', 'title']);
        $packages = Product::where('is_package', true)->get(['id', 'title']);


        return view('admin.menus.index', compact(
            'menuGroups',
            'linkableTypes',
            'pages',
            'categories',
            'products',
            'packages'
        ));
    }

    public function updateGroup(Request $request, MenuGroup $menuGroup)
    {
        $request->validate(['title' => 'required|string|max:255']);
        $menuGroup->update(['title' => $request->title]);
        return back()->with('success', 'Menü grubu başlığı güncellendi.');
    }

    public function storeItem(Request $request, MenuGroup $menuGroup)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:custom,page,category,product,package',
            'target' => 'required|string|in:_self,_blank',
            'url' => 'required_if:type,custom|nullable|url',
            'linkable_id' => 'required_if:type,page,category,product,package|nullable|integer',
        ]);

        $data = [
            'menu_group_id' => $menuGroup->id,
            'title' => $request->title,
            'target' => $request->target,
            'linkable_id' => null,
            'linkable_type' => null,
            'url' => null,
        ];

        if ($request->type === 'custom') {
            $data['url'] = $request->url;
        } else {
            $data['linkable_id'] = $request->linkable_id;
            switch ($request->type) {
                case 'page':
                    $data['linkable_type'] = Page::class;
                    break;
                case 'category':
                    $data['linkable_type'] = Category::class;
                    break;
                case 'product':
                case 'package':
                    $data['linkable_type'] = Product::class;
                    break;
            }
        }

        MenuItem::create($data);

        return back()->with('success', 'Menü linki başarıyla eklendi.');
    }

    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:custom,page,category,product,package',
            'target' => 'required|string|in:_self,_blank',
            'url' => 'required_if:type,custom|nullable|url',
            'linkable_id' => 'required_if:type,page,category,product,package|nullable|integer',
        ]);

        $data = [
            'title' => $request->title,
            'target' => $request->target,
            'linkable_id' => null,
            'linkable_type' => null,
            'url' => null,
        ];

        if ($request->type === 'custom') {
            $data['url'] = $request->url;
        } else {
            $data['linkable_id'] = $request->linkable_id;
            switch ($request->type) {
                case 'page':
                    $data['linkable_type'] = Page::class;
                    break;
                case 'category':
                    $data['linkable_type'] = Category::class;
                    break;
                case 'product':
                case 'package':
                    $data['linkable_type'] = Product::class;
                    break;
            }
        }

        $menuItem->update($data);

        return back()->with('success', 'Menü linki başarıyla güncellendi.');
    }

    public function destroyItem(MenuItem $menuItem)
    {
        $menuItem->delete();
        return back()->with('success', 'Menü linki silindi.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        foreach ($request->order as $index => $itemId) {
            MenuItem::where('id', $itemId)->update(['sort_order' => $index]);
        }

        return response()->json(['status' => 'success', 'message' => 'Sıralama güncellendi.']);
    }
}
