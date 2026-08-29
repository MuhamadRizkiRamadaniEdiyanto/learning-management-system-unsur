<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dosen = $this->route('dosen');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dosen)],
            'nomor_induk' => ['sometimes', 'required', 'string', 'max:20', 'regex:/^\d{10,20}$/', Rule::unique('users', 'nomor_induk')->ignore($dosen)],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
        ];
    }
}
