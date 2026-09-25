<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitUgc();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:20'],
            'address' => ['required', 'string', 'max:500'],
            'location_district' => ['required', 'string', 'max:100'],
            'phone_whatsapp' => ['required', 'string', 'max:25'],
            'instagram' => ['nullable', 'string', 'max:100'],
            'operating_hours' => ['nullable', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama usaha / UMKM wajib diisi.',
            'category.required' => 'Kategori usaha wajib dipilih.',
            'description.required' => 'Deskripsi produk/jasa wajib diisi.',
            'address.required' => 'Alamat usaha wajib diisi.',
            'phone_whatsapp.required' => 'Nomor WhatsApp bisnis wajib diisi.',
        ];
    }
}
