<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:product_categories,code,' . $this->product_category->id,
            'name' => 'required|string|max:150',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
