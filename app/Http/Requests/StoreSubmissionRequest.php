<?php

namespace App\Http\Requests;

use App\Rules\MaxUploadSize;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_jawaban' => ['required', 'file', 'mimes:pdf,png', new MaxUploadSize()],
        ];
    }
}
