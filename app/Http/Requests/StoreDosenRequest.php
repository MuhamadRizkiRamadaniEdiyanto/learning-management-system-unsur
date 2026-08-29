<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'nomor_induk' => ['required', 'string', 'max:20', 'regex:/^\d{10,20}$/', Rule::unique('users', 'nomor_induk')],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
