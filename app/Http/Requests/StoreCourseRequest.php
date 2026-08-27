<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_matkul' => ['required', 'string', 'max:20', 'unique:courses,kode_matkul'],
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'dosen_id' => ['required', Rule::exists('users', 'id')->where(fn($query) => $query->where('role', 'dosen'))],
        ];
    }
}
