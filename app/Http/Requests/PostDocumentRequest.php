<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // No additional validation needed, the service handles it
        ];
    }
}
