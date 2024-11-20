<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinBandRequest extends FormRequest
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
            'band_id' => 'required|exists:other_services,id',
        ];
    }

    public function messages()
    {
        return [
            'band_id.required' => 'You must select a band to join.',
            'band_id.exists' => 'The selected band does not exist.'
        ];
    }
}
