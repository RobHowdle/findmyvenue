<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateEventRequest extends FormRequest
{
    public function authorize()
    {
        // Determine if the user is authorized to make this request.
        return true;
    }

    public function rules()
    {
        $rules = [
            'event_name' => 'required|string',
            'event_date' => 'required|date_format:d-m-Y',
            'event_start_time' => 'required|date_format:H:i',
            'event_end_time' => 'nullable|date_format:H:i',
            'event_description' => 'nullable|string',
            'facebook_event_url' => 'nullable|url',
            'ticket_url' => 'nullable|url',
            'otd_ticket_price' => 'required|numeric',
            'venue_id' => 'required|integer|exists:venues,id',
            'headliner' => 'required|string',
            'headliner_id' => 'required|integer',
            'mainSupport' => 'required|string',
            'main_support_id' => 'required|integer',
            'artist' => 'nullable|array',
            'band.*' => 'nullable|string',
            'band_id' => 'required|array',
            'band_id.*' => 'required|integer',
            'opener' => 'nullable|string',
            'opener_id' => 'required|integer',
        ];

        $rules['poster_url'] = [
            'nullable',
            function ($attribute, $value, $fail) {
                // Check if `poster_url` is a file upload
                if ($this->file('poster_url')) {
                    // Validate as an image file if it's a file upload
                    if (!$value->isValid() || !in_array($value->extension(), ['jpeg', 'jpg', 'png', 'webp', 'svg'])) {
                        $fail('The poster must be a valid image file (jpeg, jpg, png, webp, svg).');
                    }
                } elseif (is_string($value)) {
                    // If it's a string, validate as an existing URL
                    if (!preg_match('/\.(jpeg|jpg|png|webp|svg)$/i', $value)) {
                        $fail('The poster URL must be a valid image URL.');
                    }
                }
            },
        ];

        // Add the required rule for poster_url only on create
        if ($this->isMethod('post')) {
            $rules['poster_url'][] = 'required';
        }


        return $rules;
    }
}
