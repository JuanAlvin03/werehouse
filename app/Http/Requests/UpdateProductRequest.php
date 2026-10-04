<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|string|max:100|unique:products,sku,' . $this->product->id,
            'name' => 'required|string|max:200',
            'category_id' => 'nullable|exists:product_categories,id',
            'unit_id' => 'required|exists:units,id',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $this->product->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
