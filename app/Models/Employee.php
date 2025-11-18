<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'email',
        'name',
        'address',
        'city',
        'state',
        'postal_code',
        'image',
        'sosmed',
        'status',
    ];

    protected $casts = [
        'sosmed' => 'array',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
