<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'discounted_price',
        'category_id', // category_id'yi fillable'a ekle
        'view_count', // view_count'ı fillable'a ekle
        'is_package',
        'usage_areas', // Add usage_areas to fillable
    ];

    protected $casts = [
        'usage_areas' => 'array', // Cast usage_areas to array
    ];

    protected $appends = ['average_rating', 'total_reviews', 'url'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->title);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->title);
        });
    }

    public function getUrlAttribute()
    {
        if ($this->is_package) {
            return route('packages.show', $this->slug);
        }
        return route('products.show', $this->slug);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeIsPackage($query)
    {
        return $query->where('is_package', true);
    }

    public function scopeIsStandardProduct($query)
    {
        return $query->where('is_package', false);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function getAverageRatingAttribute()
    {
        return (float) $this->reviews()->where('is_approved', true)->avg('rating');
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }

    public function recommendedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_recommendations', 'product_id', 'recommended_product_id');
    }

    public function containedProducts()
    {
        return $this->belongsToMany(Product::class, 'package_product', 'package_id', 'product_id')->withPivot('quantity');
    }

    public function parentPackages()
    {
        return $this->belongsToMany(Product::class, 'package_product', 'product_id', 'package_id');
    }
}
