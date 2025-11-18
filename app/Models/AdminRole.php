<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    use HasFactory;

    protected $table = 'admins_roles';

    protected $fillable = [
        'admin_id',
        'module',
        'view_access',
        'edit_access',
        'full_access'
    ];

    // Relasi ke tabel admin
    public function admin()
    {
        // Pastikan namespace Admin benar
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    // Jika modul disimpan sebagai array JSON
    protected $casts = [
        'module' => 'array',
        'view_access' => 'boolean',
        'edit_access' => 'boolean',
        'full_access' => 'boolean',
    ];
    
}
