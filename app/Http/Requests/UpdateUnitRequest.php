<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:units,code,' . $this->unit->id,
            'name' => 'required|string|max:50',
            'symbol' => 'nullable|string|max:20',
        ];
    }
}
