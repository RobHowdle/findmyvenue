<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateFinanceRequest extends FormRequest
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
            'desired_profit' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'budget_name' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'link_to_event' => 'nullable|url',
            'income_presale' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'income_otd' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'income_other' => 'array|nullable',
            'income_other.*' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_venue' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_band' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_promotion' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_rider' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_other' => 'array|nullable',
            'outgoing_other.*' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'income_total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'outgoing_total' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'profit_total' => 'required|numeric',
            'desired_profit_remaining' => 'nullable|numeric',
        ];
    }
}