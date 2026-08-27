<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $course = $this->route('course');

        return [
            'kode_matkul' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('courses', 'kode_matkul')->ignore($course)],
            'nama' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
            'dosen_id' => ['sometimes', 'required', Rule::exists('users', 'id')->where(fn($query) => $query->where('role', 'dosen'))],
        ];
    }
}
