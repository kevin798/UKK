<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakePetugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // =====================
        // CREATE PETUGAS (POST)
        // =====================
        if ($this->isMethod('post')) {
            return [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ];
        }

        // =====================
        // UPDATE PETUGAS (PUT/PATCH)
        // =====================
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'unique:users,email,' . $this->route('user')->id,
                ],
                'password' => ['nullable', 'string', 'min:8'], // 🔥 BOLEH KOSONG
            ];
        }

        return [];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama petugas wajib diisi',
            'name.string'   => 'Nama petugas harus berupa teks',
            'name.min'      => 'Nama petugas minimal 3 karakter',
            'name.max'      => 'Nama petugas maksimal 255 karakter',

            'email.required' => 'Email petugas wajib diisi',
            'email.email'    => 'Email petugas harus berupa email yang valid',
            'email.unique'   => 'Email petugas sudah terdaftar',

            'password.required' => 'Password petugas wajib diisi',
            'password.string'   => 'Password petugas harus berupa teks',
            'password.min'      => 'Password petugas minimal 8 karakter',
        ];
    }
}
