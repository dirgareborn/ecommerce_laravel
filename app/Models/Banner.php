<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    public function scopeActiveSlider($query)
    {
        return $query->where('type', 'slider')
        ->where('status', 1)
        ->orderBy('sort', 'asc');
    }

}
