<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai' => ['required', 'numeric', 'between:0,100'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
