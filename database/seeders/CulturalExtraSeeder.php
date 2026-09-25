<?php

namespace Database\Seeders;

use App\Models\CulturalDestination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CulturalExtraSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'title' => 'Museum Kayu Tuah Himba Tenggarong',
                'slug' => 'museum-kayu-tuah-himba-' . Str::random(5),
                'category' => 'museum_sejarah',
                'description' => 'Museum keanekaragaman hayati dan keilmuan kehutanan yang menyimpan ratusan koleksi kayu ulin (kayu besi) raksasa, fosil kayu membatu (kayu sungkai & gaharu), awetan satwa langka khas Kalimantan seperti Buaya Badadas dan Bekantan, serta miniatur rumah panggung suku Dayak & Kutai.',
                'historical_context' => 'Diresmikan untuk mendokumentasikan kekayaan vegetasi hutan hujan tropis Borneo dan sejarah eksploitasi kayu masa lampau.',
                'address' => 'Kawasan Waduk Panji Sukarame, Panji, Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4192000,
                'longitude' => 116.9635000,
                'operating_info' => 'Buka Setiap Hari: 08.30 - 16.00 WITA. Tiket: Rp 5.000 / orang.',
                'status' => 'published',
            ],
            [
                'title' => 'Planetarium Jagad Raya Tenggarong',
                'slug' => 'planetarium-jagad-raya-' . Str::random(5),
                'category' => 'wisata_alam',
                'description' => 'Salah satu planetarium termegah di Indonesia dengan teater kubah bintang berkapasitas 90 penonton. Dilengkapi proyektor bintang digital canggih Zeiss Skymaster ZKP-4 untuk mensimulasikan gugusan galaksi, tata surya, dan rasi bintang langit Kutai Kartanegara.',
                'historical_context' => 'Dibangun sebagai wahana edukasi astronomi dan sains antariksa pertama di Pulau Kalimantan.',
                'address' => 'Jl. Diponegoro, Kel. Panji (Sebelah Museum Mulawarman), Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4269000,
                'longitude' => 116.9831000,
                'operating_info' => 'Selasa - Minggu: Pertunjukan Jam 10.00, 14.00, 16.00 WITA.',
                'status' => 'published',
            ],
            [
                'title' => 'Pesta Adat Erau & Festival Budaya Internasional (EIFAF)',
                'slug' => 'pesta-adat-erau-festival-internasional-' . Str::random(5),
                'category' => 'festival_adat',
                'description' => 'Festival upacara adat akbar warisan abad ke-13 yang digelar Kesultanan Kutai Kartanegara. Menampilkan ritual sakral Mendirikan Ayu, Belian, Bepelas, Mengulur Naga, hingga puncak tradisi Belimbur (saling menyiram air berkah Mahakam sebagai simbol penyucian diri).',
                'historical_context' => 'Erau berasal dari bahasa Kutai "Eroh" yang berarti riuh sukacita, pertama kali digelar saat upacara penabalan Raja Aji Batara Agung Dewa Sakti.',
                'address' => 'Kawasan Keraton Kesultanan & Tepian Sungai Mahakam, Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4275000,
                'longitude' => 116.9840000,
                'operating_info' => 'Digelar tahunan setiap bulan September / Oktober.',
                'status' => 'published',
            ],
            [
                'title' => 'Situs Bersejarah Candi Agung & Prasasti Yupa Muara Kaman',
                'slug' => 'situs-prasasti-yupa-muara-kaman-' . Str::random(5),
                'category' => 'museum_sejarah',
                'description' => 'Situs arkeologi asal mula peradaban aksara tertua di Nusantara (abad ke-4 Masehi). Di sinilah 7 tugu batu Prasasti Yupa peninggalan Raja Mulawarman ditemukan yang memuat tulisan beraksara Pallawa dan bahasa Sanskerta mengenai sedekah 20.000 ekor sapi.',
                'historical_context' => 'Menjadi tonggak sejarah peralihan Indonesia dari masa pra-sejarah menuju masa sejarah tertulis.',
                'address' => 'Kawasan Muara Kaman Ulu, Kec. Muara Kaman, Kutai Kartanegara',
                'location_district' => 'Muara Kaman',
                'latitude' => -0.2460000,
                'longitude' => 116.7840000,
                'operating_info' => 'Buka Setiap Hari: 08.00 - 17.00 WITA.',
                'status' => 'published',
            ],
            [
                'title' => 'Taman Wisata Alam & Danau Waduk Panji Sukarame',
                'slug' => 'taman-waduk-panji-sukarame-' . Str::random(5),
                'category' => 'wisata_alam',
                'description' => 'Kawasan telaga dan waduk seluas 32 hektar yang dikelilingi hutan lindung perbukitan. Menawarkan perahu kayuh bebek air, jembatan kayu terapung, gazebo sejuk, dan jalur jalan santai rindang di bawah pepohonan kanopi tropis.',
                'historical_context' => 'Waduk buatan bersejarah yang dibangun untuk mengendalikan tata air kota dan dikembangkan menjadi taman rekreasi.',
                'address' => 'Kel. Sukarame, Tenggarong, Kutai Kartanegara',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4170000,
                'longitude' => 116.9640000,
                'operating_info' => 'Buka Setiap Hari: 08.00 - 18.00 WITA.',
                'status' => 'published',
            ],
            [
                'title' => 'Tradisi Masakan Khas Kutai (Gence Ruan & Sayur Asam Kutai)',
                'slug' => 'tradisi-kuliner-khas-kutai-' . Str::random(5),
                'category' => 'kuliner_tradisi',
                'description' => 'Pusat apresiasi tradisi kuliner bangsawan dan masyarakat Kutai. Menyajikan hidangan otentik seperti Gence Ruan (ikan gabus bakar sambal pedas asam khas), Sayur Asam Kutai dengan kepala ikan baung & pisang mentah, Buras Kutai, dan kue tradisional Keminting & Roti Pisang.',
                'historical_context' => 'Kekayaan gastronomi suku Kutai yang lahir dari perpaduan hasil perikanan Sungai Mahakam dan rempah hutan pedalaman.',
                'address' => 'Sentra Kuliner Tradisional Tepian Mahakam, Jl. KH. Ahmad Muksin, Tenggarong',
                'location_district' => 'Tenggarong',
                'latitude' => -0.4390000,
                'longitude' => 116.9810000,
                'operating_info' => 'Buka Setiap Hari: 10.00 - 22.00 WITA.',
                'status' => 'published',
            ]
        ];

        foreach ($destinations as $data) {
            if (!CulturalDestination::where('title', $data['title'])->exists()) {
                CulturalDestination::create($data);
            }
        }
    }
}
