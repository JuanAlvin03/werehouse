<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'warehouse_id' => 'required|exists:warehouses,id',
            'business_partner_id' => 'nullable|exists:business_partners,id',
            'external_system' => 'nullable|string|max:50',
            'external_reference' => 'nullable|string|max:100',
            'idempotency_key' => 'nullable|string|max:150',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|gt:0',
            'items.*.unit_id' => 'required|exists:units,id',
            'items.*.notes' => 'nullable|string',
        ];
    }
}
