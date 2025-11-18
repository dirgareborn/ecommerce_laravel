<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitraStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'status' => 'required|in:0,1'
        ];
    }
}
