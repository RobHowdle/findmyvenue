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
            'name' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'postal_town' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:5120',
            'about' => 'nullable|string',
            'myVenues' => 'nullable|string',
            'genres' => 'nullable|array',
            'band_types' => 'nullable|array',
            'contact_name' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_links.*.*' => 'nullable|url',
            'genres' => 'nullable|array',
        ];
    }
}
