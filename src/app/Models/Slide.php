<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class Slide extends Model
{
    use HasFactory;

    protected $fillable = [
        'desktop_image_path',
        'mobile_image_path',
        'content',
        'button_text',
        'button_url',
        'linkable_id',
        'linkable_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getDesktopImageUrlAttribute(): string
    {
        return Storage::url($this->desktop_image_path);
    }

    public function getMobileImageUrlAttribute(): string
    {
        return Storage::url($this->mobile_image_path);
    }

    public function getButtonUrl(): string
    {
        if ($this->linkable_type && $this->linkable) {
            $routeName = '';
            switch ($this->linkable_type) {
                case Page::class:
                    $routeName = 'page.show';
                    break;
                case Product::class:
                    $routeName = $this->linkable->is_package ? 'packages.show' : 'products.show';
                    break;
                case Category::class:
                    // This is a guess and might need adjustment.
                    return url('/kategori/' . $this->linkable->slug);
            }

            if ($routeName && Route::has($routeName)) {
                return route($routeName, $this->linkable->slug ?? $this->linkable->id);
            }
        }

        return $this->button_url ?? '#';
    }

    public function getButtonLinkType(): ?string
    {
        if ($this->linkable_type) {
            switch ($this->linkable_type) {
                case Page::class: return 'page';
                case Category::class: return 'category';
                case Product::class:
                    return $this->linkable?->is_package ? 'package' : 'product';
            }
        }
        
        if ($this->button_url) {
            return 'custom';
        }

        return null;
    }
}
