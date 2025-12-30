<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public function getValueAttribute($value)
    {
        if ($this->key === 'site_logo' && $value) {
            if (str_starts_with($value, 'http')) {
                return $value;
            }
            return Storage::disk('uploads')->url($value);
        }

        return $value;
    }
}
