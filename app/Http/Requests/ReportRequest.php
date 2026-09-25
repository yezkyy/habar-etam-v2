<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->canSubmitReport();
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:30', 'max:10000'],
            'address' => ['required', 'string', 'max:500'],
            'location_district' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'evidence_files' => ['nullable', 'array', 'max:5'],
            'evidence_files.*' => ['file', 'mimes:jpeg,png,jpg,webp,mp4,mov,avi', 'max:20480'], // 20MB max
        ];
    }

    public function messages(): array
    {
        return [
            'category.required' => 'Kategori laporan pengaduan wajib dipilih.',
            'title.required' => 'Judul ringkas pengaduan wajib diisi.',
            'description.required' => 'Deskripsi detail kronologi/kondisi masalah wajib diisi.',
            'description.min' => 'Deskripsi pengaduan minimal 30 karakter agar dapat ditindaklanjuti redaksi.',
            'address.required' => 'Alamat atau patokan lokasi jelas wajib diisi.',
            'location_district.required' => 'Kecamatan wajib dipilih.',
            'evidence_files.max' => 'Maksimal 5 lampiran bukti foto/video.',
            'evidence_files.*.max' => 'Ukuran setiap file bukti maksimal 20 MB.',
        ];
    }
}
