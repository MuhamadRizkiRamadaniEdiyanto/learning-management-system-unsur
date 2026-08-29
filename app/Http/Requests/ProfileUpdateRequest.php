<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];

        if ($this->user()?->role === 'mahasiswa' || $this->user()?->role === 'dosen') {
            $rules['nomor_induk'] = ['prohibited'];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        if (($this->user()?->role === 'mahasiswa' || $this->user()?->role === 'dosen') && $this->has('nomor_induk')) {
            $this->request->remove('nomor_induk');
        }
    }
}
