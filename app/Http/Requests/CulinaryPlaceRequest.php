<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CulinaryPlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitUgc();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'culinary_type' => ['required', 'string', 'max:100'],
            'price_range' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:20'],
            'address' => ['required', 'string', 'max:500'],
            'location_district' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone_whatsapp' => ['nullable', 'string', 'max:25'],
            'operating_hours' => ['nullable', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama tempat kuliner wajib diisi.',
            'culinary_type.required' => 'Jenis kuliner wajib dipilih.',
            'price_range.required' => 'Rentang harga wajib dipilih.',
            'description.required' => 'Deskripsi kuliner & menu andalan wajib diisi.',
            'address.required' => 'Alamat lokasi kuliner wajib diisi.',
        ];
    }
}
