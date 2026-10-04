<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWarehouseLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $locationId = optional($this->route('location'))->id;
        
        return [
            'code' => 'required|string|max:50|unique:warehouse_locations,code,' . $locationId . ',id,warehouse_id,' . $this->warehouse->id,
            'name' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }
}
