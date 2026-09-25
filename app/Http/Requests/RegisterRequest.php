<?php

namespace App\Http\Requests;

use App\Services\NikVerificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nik' => ['required', 'string', 'size:16', 'regex:/^[0-9]{16}$/'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:500'],
            'district' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar dalam sistem.',
            'phone.required' => 'Nomor telepon/WhatsApp wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.size' => 'NIK harus tepat berjumlah 16 digit.',
            'nik.regex' => 'NIK hanya boleh berisi angka.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $nik = $this->input('nik');
            $dob = $this->input('date_of_birth');

            if ($nik && $dob && preg_match('/^[0-9]{16}$/', $nik)) {
                $nikService = app(NikVerificationService::class);
                $analysis = $nikService->analyze($nik, $dob);

                // Check for duplicate NIK hash
                $existing = \App\Models\Verification::where('nik_hash', $analysis['nik_hash'])->first();
                if ($existing) {
                    $validator->errors()->add('nik', 'NIK ini sudah terdaftar dalam akun lain.');
                }

                // Check birth date match
                if (!$analysis['birth_date_valid']) {
                    $validator->errors()->add('date_of_birth', 'Data tanggal lahir tidak sesuai dengan struktur NIK.');
                }
            }
        });
    }
}
