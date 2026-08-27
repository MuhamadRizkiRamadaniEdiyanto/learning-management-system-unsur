<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['judul' => ['required', 'string', 'max:255'], 'deskripsi' => ['nullable', 'string'], 'tenggat_waktu' => ['required', 'date', 'after:now']];
    }
}
