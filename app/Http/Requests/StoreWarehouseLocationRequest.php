<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:warehouse_locations,code,NULL,id,warehouse_id,' . $this->warehouse->id,
            'name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }
}
