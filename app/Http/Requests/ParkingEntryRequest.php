<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParkingEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'plate_number' => 'required|string|max:20',
        ];
    }
}
