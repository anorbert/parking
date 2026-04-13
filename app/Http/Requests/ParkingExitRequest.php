<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParkingExitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:cash,momo',
            'phone_number'   => 'nullable|string|min:10|max:15',
            'amount'         => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Payment amount must be zero or greater.',
        ];
    }
}
