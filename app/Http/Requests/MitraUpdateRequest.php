<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MitraUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'status' => 'required|in:0,1'
        ];
    }
}
