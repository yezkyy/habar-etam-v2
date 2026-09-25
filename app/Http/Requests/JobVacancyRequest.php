<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitUgc();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'company' => ['required', 'string', 'max:200'],
            'employment_type' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:150'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:30'],
            'requirements' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date', 'after_or_equal:today'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'max:25'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Posisi / jabatan lowongan wajib diisi.',
            'company.required' => 'Nama perusahaan / usaha pemberi kerja wajib diisi.',
            'employment_type.required' => 'Tipe pekerjaan wajib dipilih.',
            'location.required' => 'Lokasi penempatan kerja wajib diisi.',
            'description.required' => 'Deskripsi pekerjaan wajib diisi.',
            'contact_phone.required' => 'Nomor kontak / WhatsApp lamaran wajib diisi.',
        ];
    }
}
