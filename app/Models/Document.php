<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'doc_path',
        'type',
        'upload_by',
        'status',
    ];


    public function uploader()
    {
        return $this->belongsTo(Admin::class, 'upload_by');
    }

}
