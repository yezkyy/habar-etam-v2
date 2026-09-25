<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitUgc();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:1000'],
            'condition' => ['required', 'string', 'in:Baru,Bekas - Seperti Baru,Bekas - Normal/Bagus,Bekas - Apa Adanya'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'location_name' => ['required', 'string', 'max:150'],
            'contact_phone' => ['required', 'string', 'max:25'],
            'contact_whatsapp' => ['required', 'string', 'max:25'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // 5MB max
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul barang jual cepat wajib diisi.',
            'category.required' => 'Kategori barang wajib dipilih.',
            'price.required' => 'Harga barang wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka nominal.',
            'condition.required' => 'Kondisi barang wajib dipilih.',
            'description.required' => 'Deskripsi detail barang wajib diisi.',
            'description.min' => 'Deskripsi minimal 20 karakter agar jelas bagi calon pembeli.',
            'location_name.required' => 'Lokasi barang / area COD wajib diisi.',
            'contact_phone.required' => 'Nomor telepon wajib diisi.',
            'contact_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'photos.max' => 'Maksimal 5 foto per barang.',
            'photos.*.image' => 'File harus berupa foto/gambar.',
            'photos.*.max' => 'Ukuran setiap foto maksimal 5 MB.',
        ];
    }
}
