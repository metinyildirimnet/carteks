<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    private function getLinkableData()
    {
        return [
            'linkableTypes' => [
                'custom' => 'Özel Link',
                'page' => 'Sayfa',
                'category' => 'Kategori',
                'product' => 'Ürün',
                'package' => 'Paket',
            ],
            'pages' => Page::where('is_published', true)->get(['id', 'title']),
            'categories' => Category::all(['id', 'name']),
            'products' => Product::where('is_package', false)->get(['id', 'title']),
            'packages' => Product::where('is_package', true)->get(['id', 'title']),
        ];
    }

    public function index()
    {
        $slides = Slide::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('slides'));
    }

    public function create()
    {
        $data = $this->getLinkableData();
        return view('admin.sliders.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $this->validateSlide($request);
        $this->handleSlideData($validated);
        Slide::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slayt başarıyla oluşturuldu.');
    }

    public function edit(Slide $slider)
    {
        $data = $this->getLinkableData();
        $data['slide'] = $slider;
        return view('admin.sliders.edit', $data);
    }

    public function update(Request $request, Slide $slider)
    {
        $validated = $this->validateSlide($request, $slider->id);
        $this->handleSlideData($validated, $slider);
        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slayt başarıyla güncellendi.');
    }

    public function destroy(Slide $slider)
    {
        Storage::disk('public')->delete($slider->desktop_image_path);
        Storage::disk('public')->delete($slider->mobile_image_path);
        $slider->delete();

        return back()->with('success', 'Slayt başarıyla silindi.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $index => $itemId) {
            Slide::where('id', $itemId)->update(['sort_order' => $index]);
        }
        return response()->json(['status' => 'success', 'message' => 'Sıralama güncellendi.']);
    }

    private function validateSlide(Request $request, $slideId = null): array
    {
        $imageRules = $slideId ? 'nullable' : 'required';

        return $request->validate([
            'desktop_image' => [$imageRules, 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'mobile_image' => [$imageRules, 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            'content' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            // Button link validation
            'button_link_type' => 'nullable|string|in:custom,page,category,product,package',
            'button_url' => 'required_if:button_link_type,custom|nullable|url',
            'linkable_id' => 'required_if:button_link_type,page,category,product,package|nullable|integer',
        ]);
    }

    private function handleSlideData(array &$validatedData, Slide $slide = null): void
    {
        if (request()->hasFile('desktop_image')) {
            if ($slide && $slide->desktop_image_path) {
                Storage::disk('public')->delete($slide->desktop_image_path);
            }
            $validatedData['desktop_image_path'] = request()->file('desktop_image')->store('slides', 'uploads');
        }

        if (request()->hasFile('mobile_image')) {
            if ($slide && $slide->mobile_image_path) {
                Storage::disk('public')->delete($slide->mobile_image_path);
            }
            $validatedData['mobile_image_path'] = request()->file('mobile_image')->store('slides', 'uploads');
        }

        $validatedData['is_active'] = request()->has('is_active');

        // Reset link fields
        $validatedData['linkable_id'] = null;
        $validatedData['linkable_type'] = null;
        $validatedData['button_url'] = null;

        $type = $validatedData['button_link_type'] ?? null;

        if ($type === 'custom') {
            $validatedData['button_url'] = $validatedData['button_url'];
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
