<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformationComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'information_id',
        'user_id',
        'admin_id',
        'parent_id',
        'comment',
        'status'
    ];

    // Relasi ke Information
    public function information()
    {
        return $this->belongsTo(Information::class);
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke admin
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Relasi ke parent comment
    public function parent()
    {
        return $this->belongsTo(InformationComment::class, 'parent_id');
    }

    // Relasi ke replies
    public function replies()
    {
        return $this->hasMany(InformationComment::class, 'parent_id')->orderBy('created_at','asc');
    }
}
