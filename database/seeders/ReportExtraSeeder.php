<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportExtraSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where('email', 'budi@warga.id')->first() ?? User::first();
        $siti = User::where('email', 'siti@warga.id')->first() ?? User::first();
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. Update/Add media to Report 1 (Lampu PJU)
        $rep1 = Report::where('ticket_number', 'ETAM-202609-001')->first();
        if ($rep1) {
            ReportMedia::firstOrCreate([
                'report_id' => $rep1->id,
                'path' => 'reports/lapor_pju_lampu.jpg',
            ], [
                'media_type' => 'image',
            ]);
        }

        // 2. Update/Add media to Report 2 (Drainase)
        $rep2 = Report::where('ticket_number', 'ETAM-202609-002')->first();
        if ($rep2) {
            ReportMedia::firstOrCreate([
                'report_id' => $rep2->id,
                'path' => 'reports/lapor_drainase.jpg',
            ], [
                'media_type' => 'image',
            ]);
        }

        // 3. Update/Add media to Report 3 (Jalan Rusak)
        $rep3 = Report::where('ticket_number', 'ETAM-202609-003')->first();
        if ($rep3) {
            ReportMedia::firstOrCreate([
                'report_id' => $rep3->id,
                'path' => 'reports/lapor_jalan_rusak.jpg',
            ], [
                'media_type' => 'image',
            ]);
        }

        // 4. Update/Add media to Report 4 (Pohon / Fasilitas)
        $rep4 = Report::where('ticket_number', 'ETAM-202609-004')->first();
        if ($rep4) {
            ReportMedia::firstOrCreate([
                'report_id' => $rep4->id,
                'path' => 'reports/lapor_pohon_trim.jpg',
            ], [
                'media_type' => 'image',
            ]);
        }

        // 5. Additional Realistic Kukar Reports
        $extraReports = [
            [
                'ticket_number' => 'ETAM-202609-005',
                'user_id' => $budi->id,
                'category' => 'Jalan & Jembatan',
                'title' => 'Amblasnya Bahu Jalan Penghubung Tenggarong Seberang Menuju Samarinda',
                'description' => "Bahu jalan sepanjang 25 meter di KM 5 Tenggarong Seberang mengalami pergeseran tanah akibat curah hujan tinggi. Rambu pengaman seadanya dipasang warga agar truk dan bus tidak terperosok ke jurang sisi kiri jalan.",
                'address' => 'Jl. Poros Tenggarong - Samarinda KM 5, Desa Teluk Dalam',
                'location_district' => 'Tenggarong Seberang',
                'latitude' => -0.4450000,
                'longitude' => 117.0350000,
                'status' => 'live_agenda',
                'admin_notes' => 'Diangkat dalam Live Studio SCM edisi Ruang Publik Kukar untuk percepatan penanganan oleh BBPJN dan Dinas PU Kaltim-Kukar.',
                'editorial_summary' => 'Redaksi telah memverifikasi kondisi longsor bahu jalan dan menyiarkan peringatan hati-hati bagi pengguna jalan antarkota.',
                'is_featured_live' => true,
                'moderated_by' => $admin->id,
                'moderated_at' => now()->subHours(8),
                'media_file' => 'reports/lapor_jalan_rusak.jpg',
            ],
            [
                'ticket_number' => 'ETAM-202609-006',
                'user_id' => $siti->id,
                'category' => 'Sampah & Kebersihan',
                'title' => 'Tumpukan Sampah Liar di Pinggir Jalan Poros Loa Kulu Perlu Bak Kontainer',
                'description' => "Warga mengeluhkan aroma tidak sedap akibat tumpukan sampah liar di tepi jalan umum Loa Kulu. Belum tersedianya TPS kontainer resmi dari instansi kebersihan di sekitar pemukiman baru.",
                'address' => 'Jl. Jenderal Sudirman RT 08, Desa Ponoragan',
                'location_district' => 'Loa Kulu',
                'latitude' => -0.4850000,
                'longitude' => 117.0200000,
                'status' => 'processing_editorial',
                'admin_notes' => 'Telah diteruskan ke Dinas Lingkungan Hidup dan Kebersihan Kukar untuk penempatan kontainer TPS bergerak.',
                'editorial_summary' => 'Dinas Lingkungan Hidup Kukar menjadwalkan pengangkutan armada kebersihan dan pembersihan lokasi.',
                'is_featured_live' => false,
                'moderated_by' => $admin->id,
                'moderated_at' => now()->subHours(14),
                'media_file' => 'reports/lapor_drainase.jpg',
            ],
            [
                'ticket_number' => 'ETAM-202609-007',
                'user_id' => $budi->id,
                'category' => 'Lampu & Penerangan',
                'title' => 'Lampu Dermaga Penyeberangan Tradisional Kota Bangun Padam Total',
                'description' => "Penerangan di dermaga penyeberangan kapal klotok Kota Bangun padam sejak seminggu lalu. Menyulitkan warga hulu Mahakam yang beraktivitas bongkar muat hasil kebun dan ikan di malam hari.",
                'address' => 'Dermaga Penyeberangan Mahakam Ulu, Kelurahan Kota Bangun Ulu',
                'location_district' => 'Kota Bangun',
                'latitude' => -0.2315000,
                'longitude' => 116.5920000,
                'status' => 'resolved',
                'admin_notes' => 'Petugas teknis Dinas Perhubungan Kukar bersama PLN telah mengganti panel MCB dan 4 titik lampu sorot LED dermaga.',
                'editorial_summary' => 'Dermaga penyeberangan Kota Bangun kembali terang benderang per 22 September 2026.',
                'resolved_at' => now()->subDays(1),
                'moderated_by' => $admin->id,
                'moderated_at' => now()->subDays(2),
                'media_file' => 'reports/lapor_pju_lampu.jpg',
            ],
            [
                'ticket_number' => 'ETAM-202609-008',
                'user_id' => $siti->id,
                'category' => 'Drainase & Banjir',
                'title' => 'Saluran Pembuangan Air Hujan Kawasan Pesisir Samboja Meluap ke Jalan Desa',
                'description' => "Gorong-gorong di persimpangan jalan desa Kuala Samboja tersumbat sedimentasi pasir pantai pasca air pasang, menyebabkan genangan setinggi 20 cm saat hujan lebat.",
                'address' => 'Jl. Pesisir Pantai Ambalat RT 04, Kuala Samboja',
                'location_district' => 'Samboja',
                'latitude' => -1.0420000,
                'longitude' => 117.0650000,
                'status' => 'pending_verification',
                'admin_notes' => null,
                'media_file' => 'reports/lapor_drainase.jpg',
            ],
        ];

        foreach ($extraReports as $data) {
            $mediaFile = $data['media_file'] ?? null;
            unset($data['media_file']);

            $rep = Report::updateOrCreate(
                ['ticket_number' => $data['ticket_number']],
                $data
            );

            if ($mediaFile) {
                ReportMedia::firstOrCreate([
                    'report_id' => $rep->id,
                    'path' => $mediaFile,
                ], [
                    'media_type' => 'image',
                ]);
            }
        }
    }
}
