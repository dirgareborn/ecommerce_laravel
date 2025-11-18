<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'description',
        'status',
    ];


    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }


    // Optional: relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
