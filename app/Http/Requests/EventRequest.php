<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'organizer' => ['required', 'string', 'max:200'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'string', 'max:50'],
            'location_name' => ['required', 'string', 'max:200'],
            'location_address' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string', 'min:30'],
            'contact_phone' => ['nullable', 'string', 'max:25'],
            'poster_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Nama kegiatan / event wajib diisi.',
            'category.required' => 'Kategori event wajib dipilih.',
            'organizer.required' => 'Penyelenggara event wajib diisi.',
            'start_date.required' => 'Tanggal mulai kegiatan wajib diisi.',
            'location_name.required' => 'Nama lokasi / venue kegiatan wajib diisi.',
            'description.required' => 'Deskripsi kegiatan wajib diisi.',
        ];
    }
}
