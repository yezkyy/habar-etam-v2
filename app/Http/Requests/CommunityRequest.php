<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommunityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitUgc();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'interest_category' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:30'],
            'activity_schedule' => ['nullable', 'string', 'max:150'],
            'base_location' => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'contact_phone' => ['required', 'string', 'max:25'],
            'social_media' => ['nullable', 'string', 'max:150'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama komunitas / klub wajib diisi.',
            'interest_category.required' => 'Bidang minat komunitas wajib dipilih.',
            'description.required' => 'Deskripsi komunitas wajib diisi.',
            'base_location.required' => 'Lokasi markas / tempat kumpul rutin wajib diisi.',
            'contact_phone.required' => 'Nomor narahubung wajib diisi.',
        ];
    }
}
