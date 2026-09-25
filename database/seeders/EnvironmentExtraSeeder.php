<?php

namespace Database\Seeders;

use App\Models\EnvironmentPoint;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnvironmentExtraSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) return;

        if (!EnvironmentPoint::where('slug', 'like', '%muara-kaman%')->exists()) {
            EnvironmentPoint::create([
                'title' => 'TMA Sungai Mahakam — Pos Pantau Dermaga Ulu Muara Kaman',
                'slug' => 'tma-sungai-mahakam-muara-kaman-' . Str::random(5),
                'info_type' => 'water_level',
                'severity' => 'warning',
                'description' => 'Tinggi muka air Sungai Mahakam terpantau pada level 5.60 meter (Siaga Kuning) menyusul intensitas hujan tinggi di daerah hulu Mahakam. Arus sungai cukup deras dengan serpihan ranting kayu.',
                'location_name' => 'Pos Pantau Dermaga Muara Kaman Ulu',
                'location_district' => 'Muara Kaman',
                'latitude' => -0.2450000,
                'longitude' => 116.7820000,
                'source' => 'BPBD Kukar & Balai Wilayah Sungai (BWS) Kalimantan IV',
                'status_condition' => 'TMA: 5.60 M (Status: Waspada / Kuning)',
                'updated_by' => $admin->id,
            ]);
        }

        if (!EnvironmentPoint::where('slug', 'like', '%cuaca-bmkg-pesisir%')->exists()) {
            EnvironmentPoint::create([
                'title' => 'Peringatan Dini Cuaca & Gelombang Pesisir Samboja — Muara Jawa',
                'slug' => 'peringatan-cuaca-bmkg-pesisir-' . Str::random(5),
                'info_type' => 'weather_alert',
                'severity' => 'warning',
                'description' => 'Prakiraan potensi hujan sedang hingga lebat disertai kilat dan angin kencang berdurasi singkat di perairan Selat Makassar wilayah Samboja dan Muara Jawa. Nelayan diimbau waspada gelombang 1.25 - 2.0 meter.',
                'location_name' => 'Kawasan Pesisir Samboja & Muara Jawa',
                'location_district' => 'Samboja',
                'latitude' => -1.0250000,
                'longitude' => 117.0650000,
                'source' => 'BMKG Stasiun Meteorologi Sultan Aji Muhammad Sulaiman',
                'status_condition' => 'Status: Waspada Angin Kencang & Gelombang',
                'updated_by' => $admin->id,
            ]);
        }

        if (!EnvironmentPoint::where('slug', 'like', '%bukit-biru%')->exists()) {
            EnvironmentPoint::create([
                'title' => 'Monitoring Lereng Rawan Longsor — Kawasan Jalur Poros Bukit Biru',
                'slug' => 'monitoring-lereng-bukit-biru-' . Str::random(5),
                'info_type' => 'landslide_prone',
                'severity' => 'warning',
                'description' => 'Terdeteksi rembesan air pada tebing sisi kiri arah Bukit Biru pasca intensitas hujan tinggi. Rambu peringatan dan terpal proteksi lereng telah dipasang oleh tim Reaksi Cepat BPBD.',
                'location_name' => 'Jalur Poros Bukit Biru KM 4, Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4580000,
                'longitude' => 116.9530000,
                'source' => 'TRC Badan Penanggulangan Bencana Daerah (BPBD) Kukar',
                'status_condition' => 'Status: Terpantau Stabil Bersyarat',
                'updated_by' => $admin->id,
            ]);
        }

        if (!EnvironmentPoint::where('slug', 'like', '%waduk-panji%')->exists()) {
            EnvironmentPoint::create([
                'title' => 'Pemeliharaan Pintu Air & Saluran Pelimpah Waduk Panji Sukarame',
                'slug' => 'pemeliharaan-waduk-panji-' . Str::random(5),
                'info_type' => 'infrastructure',
                'severity' => 'normal',
                'description' => 'Pembersihan gulma enceng gondok dan pengecekan sensor elevasi air waduk oleh tim UPT Pengairan. Kapasitas tampungan waduk berfungsi optimal mereduksi debit limpasan air hujan perkotaan.',
                'location_name' => 'Waduk Panji Sukarame, Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4180000,
                'longitude' => 116.9620000,
                'source' => 'Dinas Pekerjaan Umum & Penataan Ruang (PUPR) Kukar',
                'status_condition' => 'Elevasi Waduk Normal: 8.20 MDPL',
                'updated_by' => $admin->id,
            ]);
        }
    }
}
