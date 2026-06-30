<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlueprintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:100'],
            'ton' => ['required', 'string', 'max:255'],
            'max_hashtags' => ['required', 'integer', 'min:0', 'max:10'],
            'max_caracteres' => ['required', 'integer', 'min:50', 'max:280'],
        ];
    }
}
