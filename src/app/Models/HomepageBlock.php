<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class HomepageBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'sort_order',
        'is_active',
        'type',
        'image_path',
        'show_on_desktop',
        'show_on_mobile',
        'link_url',
        'linkable_id',
        'linkable_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_on_desktop' => 'boolean',
        'show_on_mobile' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'homepage_block_product')
            ->withPivot('sort_order')
            ->orderBy('homepage_block_product.sort_order', 'asc');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }

    public function getLink(): ?string
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
                    return url('/kategori/' . $this->linkable->slug);
            }

            if ($routeName && Route::has($routeName)) {
                return route($routeName, $this->linkable->slug ?? $this->linkable->id);
            }
        }

        return $this->link_url;
    }

    public function getLinkType(): ?string
    {
        if ($this->linkable_type) {
            switch ($this->linkable_type) {
                case Page::class: return 'page';
                case Category::class: return 'category';
                case Product::class:
                    return $this->linkable?->is_package ? 'package' : 'product';
            }
        }
        
        if ($this->link_url) {
            return 'custom';
        }

        return null;
    }
}
