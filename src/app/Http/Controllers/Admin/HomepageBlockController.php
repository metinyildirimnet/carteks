<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomepageBlock;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HomepageBlockController extends Controller
{
    private function getLinkableData()
    {
        return [
            'products' => Product::where('is_package', false)->get(['id', 'title']),
            'packages' => Product::where('is_package', true)->get(['id', 'title']),
            'pages' => Page::where('is_published', true)->get(['id', 'title']),
            'categories' => Category::all(['id', 'name']),
            'linkableTypes' => [
                'custom' => 'Özel Link',
                'page' => 'Sayfa',
                'category' => 'Kategori',
                'product' => 'Ürün',
                'package' => 'Paket',
            ],
        ];
    }

    public function index()
    {
        $homepageBlocks = HomepageBlock::orderBy('sort_order')->get();
        return view('admin.homepage_blocks.index', compact('homepageBlocks'));
    }

    public function create()
    {
        $data = $this->getLinkableData();
        $data['allProducts'] = Product::all(); // For product block type
        return view('admin.homepage_blocks.create', $data);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateBlock($request);
        $this->handleBlockData($validatedData);

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $maxSortOrder = HomepageBlock::max('sort_order');
        $validatedData['sort_order'] = $maxSortOrder ? $maxSortOrder + 1 : 1;

        $homepageBlock = HomepageBlock::create($validatedData);

        if ($homepageBlock->type === 'product' && isset($validatedData['products'])) {
            $homepageBlock->products()->attach($validatedData['products']);
        }

        return redirect()->route('admin.homepage-blocks.index')->with('success', 'Ana sayfa bloğu başarıyla oluşturuldu.');
    }

    public function edit(HomepageBlock $homepageBlock)
    {
        $data = $this->getLinkableData();
        $data['homepageBlock'] = $homepageBlock->load('products');
        $data['allProducts'] = Product::all(); // For product block type
        return view('admin.homepage_blocks.edit', $data);
    }

    public function update(Request $request, HomepageBlock $homepageBlock)
    {
        $validatedData = $this->validateBlock($request);
        $this->handleBlockData($validatedData, $homepageBlock);

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $homepageBlock->update($validatedData);

        if ($homepageBlock->type === 'product') {
            $homepageBlock->products()->sync($validatedData['products'] ?? []);
        } else {
            $homepageBlock->products()->detach(); // Remove products if switching to visual
        }

        return redirect()->route('admin.homepage-blocks.index')->with('success', 'Ana sayfa bloğu başarıyla güncellendi.');
    }

    public function destroy(HomepageBlock $homepageBlock)
    {
        if ($homepageBlock->type === 'visual' && $homepageBlock->image_path) {
            Storage::disk('uploads')->delete($homepageBlock->image_path);
        }
        $homepageBlock->products()->detach();
        $homepageBlock->delete();

        return redirect()->route('admin.homepage-blocks.index')->with('success', 'Ana sayfa bloğu başarıyla silindi.');
    }

    private function validateBlock(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'show_on_desktop' => 'boolean',
            'show_on_mobile' => 'boolean',
            'type' => 'required|string|in:product,visual',
            // Product block fields
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            // Visual block fields
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'link_type' => 'nullable|string|in:custom,page,category,product,package',
            'link_url' => 'required_if:link_type,custom|nullable|url',
            'linkable_id' => 'required_if:link_type,page,category,product,package|nullable|integer',
        ]);
    }

    private function handleBlockData(array &$validatedData, HomepageBlock $block = null): void
    {
        $validatedData['is_active'] = request()->has('is_active');
        $validatedData['show_on_desktop'] = request()->has('show_on_desktop');
        $validatedData['show_on_mobile'] = request()->has('show_on_mobile');

        if ($validatedData['type'] === 'visual') {
            if (request()->hasFile('image')) {
                if ($block && $block->image_path) {
                    Storage::disk('uploads')->delete($block->image_path);
                }
                $validatedData['image_path'] = request()->file('image')->store('homepage_blocks', 'uploads');
            }

            // Handle link
            $validatedData['linkable_id'] = null;
            $validatedData['linkable_type'] = null;
            $validatedData['link_url'] = null;
            $type = $validatedData['link_type'] ?? null;

            if ($type === 'custom') {
                $validatedData['link_url'] = $validatedData['link_url'];
            } elseif ($type) {
                $validatedData['linkable_id'] = $validatedData['linkable_id'];
                switch ($type) {
                    case 'page': $validatedData['linkable_type'] = Page::class; break;
                    case 'category': $validatedData['linkable_type'] = Category::class; break;
                    case 'product': case 'package': $validatedData['linkable_type'] = Product::class; break;
                }
            }
        }
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:homepage_blocks,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('order') as $item) {
            HomepageBlock::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Sıralama başarıyla güncellendi.']);
    }

    public function reorderProducts(Request $request, HomepageBlock $homepageBlock)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        $syncData = [];
        foreach ($request->input('product_ids') as $index => $productId) {
            $syncData[$productId] = ['sort_order' => $index];
        }

        $homepageBlock->products()->sync($syncData);

        return response()->json(['status' => 'success', 'message' => 'Ürün sıralaması başarıyla güncellendi.']);
    }
}
