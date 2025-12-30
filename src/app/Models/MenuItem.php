<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_group_id',
        'title',
        'url',
        'target',
        'linkable_id',
        'linkable_type',
        'sort_order',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(MenuGroup::class, 'menu_group_id');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL for the menu item.
     *
     * @return string
     */
    public function getUrl(): string
    {
        if ($this->linkable_type) {
            // Check if the related model still exists
            if (!$this->linkable) {
                return '#item-deleted';
            }

            $routeName = '';
            switch ($this->linkable_type) {
                case Page::class:
                    $routeName = 'page.show';
                    break;
                case Product::class:
                    // Differentiate between product and package
                    $routeName = $this->linkable->is_package ? 'packages.show' : 'products.show';
                    break;
                case Category::class:
                    // Assuming you have a category.show route
                    // If not, this needs to be created.
                    // For now, let's point to a placeholder or a product listing page for that category.
                    // $routeName = 'categories.show';
                    // Let's assume a route name 'categories.products' that lists products in a category
                    // This is a guess and might need adjustment based on your actual routes.
                    // For now, we will just return a slug-based URL.
                    return url('/kategori/' . $this->linkable->slug); // Fallback
                // Add other cases if needed
            }

            if ($routeName && Route::has($routeName)) {
                return route($routeName, $this->linkable->slug);
            }
        }

        // For custom links
        return $this->url ?? '#';
    }
}
