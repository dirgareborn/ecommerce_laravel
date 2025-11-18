<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name','location_id','description','capacity','image_thumbnail','status'];

    public function virtualTours()
    {
        return $this->hasMany(VirtualTour::class);
    }
}
