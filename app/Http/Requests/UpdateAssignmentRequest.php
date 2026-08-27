<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['judul' => ['sometimes', 'required', 'string', 'max:255'], 'deskripsi' => ['nullable', 'string'], 'tenggat_waktu' => ['sometimes', 'required', 'date', 'after:now']];
    }
}
