<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|numeric|exists:categories,id',
            'name'       => 'required|string|max:150',
            'image_url'   => 'required|string'
        ];
    }
}
