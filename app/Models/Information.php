<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    protected $table = 'Informations';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'admin_id',
        'status',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function comments()
    {
        return $this->hasMany(InformationComment::class)->whereNull('parent_id')->where('status','approved');
    }
}
