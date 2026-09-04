<?php

namespace App\Http\Requests;

use App\Models\Assignment;
use App\Rules\MaxUploadSize;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $assignment = $this->route('assignment');
        $user = $this->user();

        return $user?->role === 'mahasiswa'
            && $assignment instanceof Assignment
            && $assignment->course->mahasiswa()->whereKey($user->id)->exists();
    }

    public function rules(): array
    {
        return [
            'file_jawaban' => ['required', 'file', 'mimes:pdf,png', new MaxUploadSize()],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $assignment = $this->route('assignment');

            if ($assignment instanceof Assignment && $this->user()?->id && $assignment->submissions()->where('user_id', $this->user()->id)->exists()) {
                $validator->errors()->add('file_jawaban', 'Anda sudah mengumpulkan tugas ini. Gunakan fitur perbarui submission.');
            }

            if ($assignment instanceof Assignment && now()->greaterThan($assignment->tenggat_waktu)) {
                $validator->errors()->add('file_jawaban', 'Tenggat waktu tugas sudah lewat.');
            }
        });
    }
}
