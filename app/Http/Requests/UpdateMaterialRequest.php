<?php

namespace App\Http\Requests;

use App\Rules\MaxUploadSize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $tipeMateri = $this->input('tipe_materi');

        return [
            'judul' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'nullable', 'string'],
            'tipe_materi' => ['sometimes', 'required', Rule::in(['pdf', 'png', 'youtube'])],
            'file' => [
                Rule::requiredIf(in_array($tipeMateri, ['pdf', 'png'], true)),
                Rule::prohibitedIf($tipeMateri === 'youtube'),
                'sometimes',
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
