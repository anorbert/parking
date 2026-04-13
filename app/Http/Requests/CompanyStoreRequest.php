<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'tin'           => 'nullable|string|max:50|unique:companies,tin',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255|unique:companies,email',
            'address'       => 'nullable|string|max:500',
            // Admin user fields
            'admin_name'    => 'required|string|max:255',
            'admin_email'   => 'nullable|email|max:255|unique:users,email',
            'admin_phone'   => 'required|string|max:20|unique:users,phone_number',
        ];
    }

    public function messages(): array
    {
        return [
            'tin.unique'         => 'A company with this TIN already exists.',
            'email.unique'       => 'A company with this email already exists.',
            'admin_phone.unique' => 'This phone number is already registered.',
        ];
    }
}
