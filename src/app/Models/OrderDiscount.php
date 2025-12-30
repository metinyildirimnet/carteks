<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDiscount extends Model
{
    protected $fillable = [
        'order_id',
        'type',
        'description',
        'amount',
        'percentage',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
