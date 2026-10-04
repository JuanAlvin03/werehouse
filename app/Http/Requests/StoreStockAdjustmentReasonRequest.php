<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockAdjustmentReasonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:stock_adjustment_reasons',
            'name' => 'required|string|max:100',
            'direction' => 'required|in:INCREASE,DECREASE,BOTH',
            'is_active' => 'boolean',
        ];
    }
}
