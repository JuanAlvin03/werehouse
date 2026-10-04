<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:warehouses',
            'name' => 'required|string|max:150',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ];
    }
}
