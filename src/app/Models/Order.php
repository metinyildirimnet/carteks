<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL; // Added

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_code',
        'user_id',
        'total_amount',
        'order_status_id',
        'ip_address',
        'user_agent',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_district',
        'payment_method',
        'discount_amount',
        'incomplete_order_sms_sent', // Added
        'incomplete_order_sms_sent_at', // Added
    ];

    protected $appends = ['resume_url']; // Append resume_url to model attributes

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function discounts()
    {
        return $this->hasMany(OrderDiscount::class);
    }

    // Accessor to generate a signed URL for resuming the order
    public function getResumeUrlAttribute(): string
    {
        return URL::temporarySignedRoute(
            'checkout.resume', // This route will be defined later
            now()->addDays(7), // Link valid for 7 days
            ['order' => $this->order_code] // Pass the order code
        );
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'order_code';
    }
}
