<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Rules\MaxUploadSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $this->user()?->role === 'dosen'
            && $course instanceof Course
            && (int) $course->dosen_id === (int) $this->user()->id;
    }

    public function rules(): array
    {
        $tipeMateri = $this->input('tipe_materi');

        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'tipe_materi' => ['required', Rule::in(['pdf', 'png', 'youtube'])],
            'file' => [
                Rule::requiredIf(in_array($tipeMateri, ['pdf', 'png'], true)),
                Rule::prohibitedIf($tipeMateri === 'youtube'),
                'file',
                $tipeMateri === 'pdf' ? 'mimes:pdf' : ($tipeMateri === 'png' ? 'mimes:png' : 'nullable'),
                $tipeMateri === 'pdf' || $tipeMateri === 'png' ? new MaxUploadSize() : 'nullable',
            ],
            'link_youtube' => [
                Rule::requiredIf($tipeMateri === 'youtube'),
                Rule::prohibitedIf(in_array($tipeMateri, ['pdf', 'png'], true)),
                'nullable',
                'url',
                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\//i',
            ],
        ];
    }
}
