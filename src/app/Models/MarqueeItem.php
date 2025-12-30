<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarqueeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'is_active',
        'sort_order',
    ];
}
