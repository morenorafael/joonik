<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLocationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:locations,code',
            'name' => 'required|string',
            'image' => 'required|url',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'The code is required',

            'name.required' => 'The name is required',
            'name.string' => 'The name must be a string',

            'image.required' => 'The image is required',
            'image.url' => 'The image must be an url',
        ];
    }
}
