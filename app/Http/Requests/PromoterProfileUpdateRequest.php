<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoterProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'location' => 'sometimes|string',
            'postal_town' => 'sometimes|string',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'logo' => 'sometimes|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
            'about' => 'sometimes|string',
            'myVenues' => 'sometimes|string',
            'genres' => 'sometimes|array',
            'band_types' => 'sometimes|array',
            'contact_name' => 'sometimes|string',
            'contact_email' => 'sometimes|email',
            'contact_links.*.*' => 'nullable|url'
        ];
    }
}