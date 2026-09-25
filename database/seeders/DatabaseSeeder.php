<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\Verification;
use App\Models\JobVacancy;
use App\Models\Business;
use App\Models\CulinaryPlace;
use App\Models\Event;
use App\Models\Community;
use App\Models\QuickSale;
use App\Models\QuickSaleMedia;
use App\Models\Report;
use App\Models\ReportMedia;
use App\Models\EmergencyContact;
use App\Models\MarketPrice;
use App\Models\EnvironmentPoint;
use App\Models\CulturalDestination;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use App\Services\NikVerificationService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $nikService = new NikVerificationService();

        // 1. Admin User
        $admin = User::create([
            'name' => 'Administrator Redaksi',
            'email' => 'admin@habaretam.id',
            'phone' => '081255551234',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'display_name' => 'Redaksi Habar Etam',
            'date_of_birth' => '1990-01-01',
            'bio' => 'Tim Redaksi & Verifikator Resmi Habar Etam Tenggarong.',
            'address' => 'Jl. Wolter Monginsidi No. 12, Tenggarong',
            'district' => 'Tenggarong',
            'village' => 'Melayu',
        ]);

        $adminNikAnalysis = $nikService->analyze('6402060101900001', '1990-01-01');
        Verification::create([
            'user_id' => $admin->id,
            'nik_encrypted' => $adminNikAnalysis['nik_encrypted'],
            'nik_hash' => $adminNikAnalysis['nik_hash'],
            'date_of_birth' => '1990-01-01',
            'nik_region_valid' => true,
            'birth_date_valid' => true,
            'status' => 'approved',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        // 2. Member 1 (Budi Santoso - Tenggarong, Verified)
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@warga.id',
            'phone' => '081347890123',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $budi->id,
            'display_name' => 'Budi Santoso',
            'date_of_birth' => '1992-04-15',
            'bio' => 'Warga Tenggarong aktif di komunitas otomotif dan fotografi.',
            'address' => 'Jl. Pesut No. 45, RT 08',
            'district' => 'Tenggarong',
            'village' => 'Timbau',
        ]);

        $budiNik = $nikService->analyze('6402061504920001', '1992-04-15');
        Verification::create([
            'user_id' => $budi->id,
            'nik_encrypted' => $budiNik['nik_encrypted'],
            'nik_hash' => $budiNik['nik_hash'],
            'date_of_birth' => '1992-04-15',
            'nik_region_valid' => true,
            'birth_date_valid' => true,
            'status' => 'approved',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        // 3. Member 2 (Siti Rahmah - Tenggarong Seberang, Verified)
        $siti = User::create([
            'name' => 'Siti Rahmah',
            'email' => 'siti@warga.id',
            'phone' => '082155678901',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'verified',
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $siti->id,
            'display_name' => 'Siti Rahmah (UMKM)',
            'date_of_birth' => '1995-08-18',
            'bio' => 'Pelaku UMKM kuliner khas Kutai Kartanegara.',
            'address' => 'Jl. Raya Sebulu, Teluk Dalam',
            'district' => 'Tenggarong Seberang',
            'village' => 'Teluk Dalam',
        ]);

        $sitiNik = $nikService->analyze('6402165808950002', '1995-08-18');
        Verification::create([
            'user_id' => $siti->id,
            'nik_encrypted' => $sitiNik['nik_encrypted'],
            'nik_hash' => $sitiNik['nik_hash'],
            'date_of_birth' => '1995-08-18',
            'nik_region_valid' => true,
            'birth_date_valid' => true,
            'status' => 'approved',
            'verified_by' => $admin->id,
            'verified_at' => now(),
        ]);

        // 4. Member 3 (Rizky Pratama - Pending Verification)
        $rizky = User::create([
            'name' => 'Rizky Pratama',
            'email' => 'rizky@warga.id',
            'phone' => '085246781290',
            'password' => Hash::make('password'),
            'role' => 'member',
            'status' => 'pending',
            'email_verified_at' => now(),
        ]);

        UserProfile::create([
            'user_id' => $rizky->id,
            'display_name' => 'Rizky Pratama',
            'date_of_birth' => '2001-05-10',
            'bio' => 'Mahasiswa dan pegiat UMKM digital Tenggarong.',
            'address' => 'Jl. Patin RT 12',
            'district' => 'Tenggarong',
            'village' => 'Loa Ipuh',
        ]);

        $rizkyNik = $nikService->analyze('6402061005010003', '2001-05-10');
        Verification::create([
            'user_id' => $rizky->id,
            'nik_encrypted' => $rizkyNik['nik_encrypted'],
            'nik_hash' => $rizkyNik['nik_hash'],
            'date_of_birth' => '2001-05-10',
            'nik_region_valid' => true,
            'birth_date_valid' => true,
            'status' => 'pending',
        ]);

        // ==========================================
        // 5. SEED UGC: BURSA KERJA
        // ==========================================
        JobVacancy::create([
            'user_id' => $budi->id,
            'title' => 'Staf Administrasi & Kasir Toko Retail',
            'slug' => 'staf-administrasi-kasir-toko-retail-' . Str::random(5),
            'company' => 'CV Mahakam Jaya Abadi',
            'employment_type' => 'Purna Waktu',
            'location' => 'Tenggarong Kota',
            'salary_range' => 'Rp 2.800.000 - Rp 3.500.000',
            'description' => "Dibutuhkan staf administrasi dan kasir untuk operasional toko perlengkapan harian di pusat kota Tenggarong.\n\nTanggung Jawab:\n- Melayani transaksi pembayaran kasir\n- Melakukan rekapitulasi penjualan harian\n- Membantu stok opname mingguan.",
            'requirements' => "- Pria / Wanita usia maks. 28 tahun\n- Pendidikan minimal SMA/SMK Sederajat\n- Teliti, jujur, dan ramah\n- Menguasai dasar Ms. Excel / POS",
            'deadline' => Carbon::now()->addDays(20),
            'contact_person' => 'Ibu Maya',
            'contact_phone' => '081255558899',
            'contact_email' => 'hrd@mahakamjaya.co.id',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        JobVacancy::create([
            'user_id' => $siti->id,
            'title' => 'Barista & Kitchen Crew Kedai Kopi',
            'slug' => 'barista-kitchen-crew-kedai-kopi-' . Str::random(5),
            'company' => 'Kedai Kopi Tepian Mahakam',
            'employment_type' => 'Paruh Waktu',
            'location' => 'Jl. Wolter Monginsidi, Tenggarong',
            'salary_range' => 'Rp 1.800.000 - Rp 2.400.000',
            'description' => "Kedai Kopi Tepian Mahakam membuka kesempatan bagi rekan muda yang tertarik di dunia perkopian dan hospitality santai.",
            'requirements' => "- Usia 18 - 25 tahun\n- Berpenampilan rapi dan komunikatif\n- Berpengalaman dasar barista menjadi nilai tambah (fresh graduate dipersilakan)",
            'deadline' => Carbon::now()->addDays(15),
            'contact_person' => 'Kak Dian',
            'contact_phone' => '082266778899',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        JobVacancy::create([
            'user_id' => $budi->id,
            'title' => 'Teknisi & Mekanik Sepeda Motor',
            'slug' => 'teknisi-mekanik-sepeda-motor-' . Str::random(5),
            'company' => 'Bengkel Berkah Motor Tenggarong Seberang',
            'employment_type' => 'Purna Waktu',
            'location' => 'Tenggarong Seberang',
            'salary_range' => 'Rp 3.000.000 - Rp 4.200.000 + Bagi Hasil',
            'description' => "Mencari mekanik handal untuk servis rutin, injeksi, dan kelistrikan motor matic/bebek.",
            'requirements' => "- Pengalaman kerja bengkel minimal 1 tahun\n- Memahami diagnosa injeksi motor Honda & Yamaha\n- Disiplin dan bertanggung jawab",
            'deadline' => Carbon::now()->addDays(30),
            'contact_person' => 'Pak Hendra',
            'contact_phone' => '081399887766',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        // ==========================================
        // 6. SEED UGC: PRODUK & JASA (UMKM)
        // ==========================================
        Business::create([
            'user_id' => $siti->id,
            'name' => 'Kue Keminting & Amplang Kutai Bu Nur',
            'slug' => 'kue-keminting-amplang-kutai-bu-nur-' . Str::random(5),
            'category' => 'Kuliner & Olahan',
            'description' => "Produksi oleh-oleh khas Kutai Kartanegara asli Tenggarong: Kue Keminting manis gurih renyah, Amplang Ikan Pipih/Belida asli Sungai Mahakam, dan Kerupuk Ikan Haruan.",
            'address' => 'Jl. Pesut Gg. 3 No. 18, Kel. Timbau, Tenggarong',
            'location_district' => 'Tenggarong',
            'phone_whatsapp' => '082155678901',
            'instagram' => '@keminting.bunur',
            'operating_hours' => 'Senin - Sabtu: 08.00 - 18.00 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        Business::create([
            'user_id' => $budi->id,
            'name' => 'Mahakam Art Design & Digital Printing',
            'slug' => 'mahakam-art-design-digital-printing-' . Str::random(5),
            'category' => 'Jasa Kreatif & Percetakan',
            'description' => "Jasa cetak banner, sablon kaos komunitas, stiker kemasan UMKM, plakat akrilik, dan cetak undangan pernikahan khas motif Kutai.",
            'address' => 'Jl. KH Akhmad Muksin No. 88, Tenggarong',
            'location_district' => 'Tenggarong',
            'phone_whatsapp' => '081347890123',
            'instagram' => '@mahakam.artprint',
            'operating_hours' => 'Setiap Hari: 08.30 - 21.00 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        Business::create([
            'user_id' => $budi->id,
            'name' => 'Bengkel Bubut & Las Listrik Karya Mandiri',
            'slug' => 'bengkel-bubut-las-listrik-karya-mandiri-' . Str::random(5),
            'category' => 'Jasa Teknik & Fabrikasi',
            'description' => "Mengerjakan pembuatan kanopi minimalis, pagar besi tempa motif etnik, teralis, perbaikan as perahu ketinting, dan pekerjaan bubut presisi.",
            'address' => 'Jl. Gerbang Dayaku, Loa Duri Ilir',
            'location_district' => 'Loa Janan',
            'phone_whatsapp' => '081244332211',
            'operating_hours' => 'Senin - Sabtu: 08.00 - 17.00 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        // ==========================================
        // 7. SEED UGC: KULINER LOKAL
        // ==========================================
        CulinaryPlace::create([
            'user_id' => $siti->id,
            'name' => 'Rumah Makan Tepian Pandan (Gence Ruan Khas Kutai)',
            'slug' => 'rumah-makan-tepian-pandan-gence-ruan-' . Str::random(5),
            'culinary_type' => 'Kuliner Tradisional Kutai',
            'price_range' => 'Rp 25.000 - Rp 65.000',
            'description' => "Menyajikan menu otentik warisan kuliner Kutai: Gence Ruan (Ikan Gabus Bakar Bumbu Pedas Manis Gurih), Sayur Asam Kutai dengan kepala ikan patin, Sambal Raja, dan Pepes Ikan Lais.",
            'address' => 'Jl. Wolter Monginsidi RT 04, Kel. Timbau (Pinggir Sungai Mahakam)',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4285000,
            'longitude' => 116.9850000,
            'phone_whatsapp' => '08115599881',
            'operating_hours' => '10.00 - 21.30 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        CulinaryPlace::create([
            'user_id' => $budi->id,
            'name' => 'Warung Nasi Kuning Ikan Haruan Mbak Sri',
            'slug' => 'warung-nasi-kuning-ikan-haruan-mbak-sri-' . Str::random(5),
            'culinary_type' => 'Sarapan Pagi & Jajanan',
            'price_range' => 'Rp 15.000 - Rp 25.000',
            'description' => "Nasi kuning pulen khas Kalimantan Timur dengan bumbu habang rempah istimewa, lauk ikan haruan masak habang, telur, daging sapi, dan serundeng gurih.",
            'address' => 'Jl. KH Akhmad Muksin (Dekat Jembatan Besi Tenggarong)',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4320000,
            'longitude' => 116.9882000,
            'phone_whatsapp' => '085299881122',
            'operating_hours' => '06.00 - 11.30 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        CulinaryPlace::create([
            'user_id' => $budi->id,
            'name' => 'Kedai Kopi Pelataran Tepian Mahakam',
            'slug' => 'kedai-kopi-pelataran-tepian-mahakam-' . Str::random(5),
            'culinary_type' => 'Cafe & Santai Sore',
            'price_range' => 'Rp 12.000 - Rp 30.000',
            'description' => "Tempat santai menikmati kopi susu gula aren, roti bakar srikaya Kutai, dan pisang goreng keju sambil memandangi hilir mudik kapal ponton di Sungai Mahakam.",
            'address' => 'Tepian Mahakam, Jl. Jenderal Sudirman, Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4250000,
            'longitude' => 116.9810000,
            'phone_whatsapp' => '082188776655',
            'operating_hours' => '16.00 - 24.00 WITA',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        // ==========================================
        // 8. SEED UGC: EVENT & KEGIATAN
        // ==========================================
        Event::create([
            'user_id' => $admin->id,
            'title' => 'Festival Erau Adat Kutai & Pelas Benua 2026',
            'slug' => 'festival-erau-adat-kutai-pelas-benua-2026-' . Str::random(5),
            'category' => 'Budaya & Adat',
            'organizer' => 'Keraton Kesultanan Kutai bekerjasama dengan Dispar Kukar',
            'start_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(32)->format('Y-m-d'),
            'start_time' => '08.30 WITA - Selesai',
            'location_name' => 'Kedaton Kesultanan & Halaman Museum Mulawarman',
            'location_address' => 'Jl. Diponegoro, Tenggarong',
            'latitude' => -0.4261000,
            'longitude' => 116.9839000,
            'description' => "Perhelatan pesta adat tahunan terbesar di Nusantara: Festival Erau Adat Kutai & Pelas Benua. Menampilkan ritual adat Mengulur Naga, Belian, Bepelas, Lomba Perahu Ketinting di Sungai Mahakam, serta pasar rakyat kerajinan khas.",
            'contact_phone' => '08115801122',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        Event::create([
            'user_id' => $budi->id,
            'title' => 'Pekan Kreatif Pemuda Mahakam & Bazar UMKM',
            'slug' => 'pekan-kreatif-pemuda-mahakam-bazar-umkm-' . Str::random(5),
            'category' => 'Musik & Komunitas',
            'organizer' => 'Forum Komunitas Pemuda Kreatif Tenggarong',
            'start_date' => Carbon::now()->addDays(8)->format('Y-m-d'),
            'end_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
            'start_time' => '15.00 - 22.00 WITA',
            'location_name' => 'Pulau Kumala Tenggarong',
            'location_address' => 'Kawasan Wisata Pulau Kumala',
            'latitude' => -0.4375000,
            'longitude' => 116.9772000,
            'description' => "Pameran karya kreatif pemuda, bazar 50+ kuliner lokal Kukar, live music acoustic, screening film pendek lokal, dan workshop fotografi ponsel.",
            'contact_phone' => '081347890123',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        Event::create([
            'user_id' => $budi->id,
            'title' => 'Gowes Sehat Susur Jembatan Kukar & Tepian Mahakam',
            'slug' => 'gowes-sehat-susur-jembatan-kukar-tepian-' . Str::random(5),
            'category' => 'Olahraga & Hobi',
            'organizer' => 'Folding Bike Kukar Club',
            'start_date' => Carbon::now()->addDays(14)->format('Y-m-d'),
            'start_time' => '06.30 WITA',
            'location_name' => 'Halaman Kantor Bupati Kukar',
            'location_address' => 'Jl. Wolter Monginsidi, Tenggarong',
            'description' => "Gowes santai bersama warga menempuh rute 18 KM melintasi Jembatan Kutai Kartanegara, menyusuri tanggul Tepian Mahakam, dan finish di Creative Hub Tenggarong.",
            'contact_phone' => '081244332211',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        // ==========================================
        // 9. SEED UGC: KLUB & KOMUNITAS
        // ==========================================
        Community::create([
            'user_id' => $budi->id,
            'name' => 'Mahakam Lens (Komunitas Fotografi Tenggarong)',
            'slug' => 'mahakam-lens-fotografi-tenggarong-' . Str::random(5),
            'interest_category' => 'Hobi & Kreatif',
            'description' => "Wadah berkumpulnya fotografer, videografer, dan kreator visual di Tenggarong untuk hunting foto budaya, landscape Mahakam, serta sharing teknik pencahayaan.",
            'activity_schedule' => 'Setiap Minggu Sore (16.00 WITA)',
            'base_location' => 'Creative Hub Tenggarong / Tepian Mahakam',
            'contact_person' => 'Budi Santoso',
            'contact_phone' => '081347890123',
            'social_media' => 'Instagram: @mahakamlens',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        Community::create([
            'user_id' => $siti->id,
            'name' => 'Sanggar Tari Seni Tradisi Seluang Mas',
            'slug' => 'sanggar-tari-tradisi-seluang-mas-' . Str::random(5),
            'interest_category' => 'Seni & Budaya',
            'description' => "Pelestarian dan pelatihan tari tradisional Kutai (Tari Jepen, Tari Kanjar, Tari Topeng Kutai) untuk anak-anak dan remaja.",
            'activity_schedule' => 'Jumat & Sabtu (15.30 WITA)',
            'base_location' => 'Gedung Kesenian Tenggarong',
            'contact_person' => 'Ibu Siti Rahmah',
            'contact_phone' => '082155678901',
            'social_media' => 'Instagram: @seluangmas.kukar',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
        ]);

        // ==========================================
        // 10. SEED UGC: JUAL CEPAT (CLASSIFIEDS)
        // ==========================================
        $qs1 = QuickSale::create([
            'user_id' => $budi->id,
            'title' => 'iPhone 13 128GB Midnight Mulus Garansi iBox Lengkap',
            'slug' => 'iphone-13-128gb-midnight-mulus-ibox-' . Str::random(5),
            'category' => 'Gadget & HP',
            'price' => 7800000,
            'condition' => 'Bekas - Seperti Baru',
            'description' => "Dijual cepat iPhone 13 128GB warna Midnight. Pemakaian pribadi dari baru ex garansi resmi iBox Indonesia. Battery Health 88% awet seharian. Layar mulus no shadow no baret, TrueTone & FaceID normal lancar. Kelengkapan fullset original dengan box & kabel c to lightning. Bisa COD seputaran Tenggarong / Tepian.",
            'location_name' => 'Tenggarong (Dekat Jembatan Besi)',
            'contact_phone' => '081347890123',
            'contact_whatsapp' => '6281347890123',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $qs2 = QuickSale::create([
            'user_id' => $budi->id,
            'title' => 'Honda Vario 160 CBS 2023 Plat KT Kukar Surat Lengkap Pajak Hidup',
            'slug' => 'honda-vario-160-cbs-2023-plat-kt-kukar-' . Str::random(5),
            'category' => 'Kendaraan & Motor',
            'price' => 21500000,
            'condition' => 'Bekas - Seperti Baru',
            'description' => "Motor pemakaian rumah tangga, KM rendah 9.200 ongoing. Servis rutin di AHASS Tenggarong. BPKB + STNK lengkap tangan pertama dari baru. Mesin halus, tarikan mantap, ban tebal. Nego tipis setelah cek unit di lokasi.",
            'location_name' => 'Kel. Timbau, Tenggarong',
            'contact_phone' => '081347890123',
            'contact_whatsapp' => '6281347890123',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $qs3 = QuickSale::create([
            'user_id' => $siti->id,
            'title' => 'Laptop ASUS Vivobook 14 Core i5 RAM 16GB SSD 512GB Siap Kerja',
            'slug' => 'laptop-asus-vivobook-14-core-i5-ram-16gb-' . Str::random(5),
            'category' => 'Komputer & Laptop',
            'price' => 5900000,
            'condition' => 'Bekas - Normal/Bagus',
            'description' => "Dijual laptop ASUS Vivobook 14 inch, processor Intel Core i5 Gen 11, RAM sudah upgrade 16GB dual channel, SSD NVMe 512GB sangat cepat. Layar FHD tajam, keyboard backlight menyala normal, baterai 3-4 jam. Cocok untuk kuliah, kantoran, dan editing ringan.",
            'location_name' => 'Tenggarong Seberang',
            'contact_phone' => '082155678901',
            'contact_whatsapp' => '6282155678901',
            'status' => 'published',
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
            'published_at' => now(),
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $qs4 = QuickSale::create([
            'user_id' => $rizky->id,
            'title' => 'Meja Kayu Jati Minimalis + 4 Kursi Makan Rapi',
            'slug' => 'meja-kayu-jati-minimalis-4-kursi-makan-' . Str::random(5),
            'category' => 'Perabot Rumah',
            'price' => 1400000,
            'condition' => 'Bekas - Normal/Bagus',
            'description' => "Dijual cepat karena mau pindahan rumah. 1 set meja makan kayu jati kokoh plus 4 kursi sandaran. Kondisi politur masih mengkilap tanpa keropos.",
            'location_name' => 'Loa Ipuh, Tenggarong',
            'contact_phone' => '085246781290',
            'contact_whatsapp' => '6285246781290',
            'status' => 'pending',
        ]);

        // ==========================================
        // 11. SEED: LAPOR ETAM (CIVIC REPORTS)
        // ==========================================
        Report::create([
            'user_id' => $budi->id,
            'ticket_number' => 'ETAM-202609-001',
            'category' => 'Lampu & Penerangan',
            'title' => 'Lampu Penerangan Jalan Umum Mati di Sekitar Jembatan Besi Tenggarong',
            'description' => "Sepanjang kurang lebih 150 meter ruas jalan dekat bundaran Jembatan Besi menuju Jl. KH Akhmad Muksin lampu PJU tidak menyala sejak 4 hari terakhir. Kondisi jalan cukup gelap saat malam dan rawan kecelakaan bagi pengendara motor.",
            'address' => 'Jl. KH Akhmad Muksin, RT 05 dekat Jembatan Besi',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4325000,
            'longitude' => 116.9880000,
            'status' => 'live_agenda',
            'admin_notes' => 'Telah dikonfirmasi oleh tim lapangan redaksi. Masuk daftar liputan siaran live Studio PT SCM.',
            'editorial_summary' => 'Kasus PJU padam di akses utama Jembatan Besi Tenggarong diangkat dalam sesi Studio SCM untuk diteruskan ke Dinas Perhubungan Kukar.',
            'is_featured_live' => true,
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
        ]);

        Report::create([
            'user_id' => $siti->id,
            'ticket_number' => 'ETAM-202609-002',
            'category' => 'Drainase & Banjir',
            'title' => 'Drainase Tersumbat Sampah di Pasar Tangga Arung Mengakibatkan Genangan',
            'description' => "Saluran parit utama di samping blok sayur Pasar Tangga Arung mengalami pendangkalan dan tertutup tumpukan sampah plastik. Jika hujan deras lebih dari 30 menit, air meluap ke selasar kios pedagang.",
            'address' => 'Komplek Pasar Tangga Arung, Kel. Melayu',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4298000,
            'longitude' => 116.9862000,
            'status' => 'processing_editorial',
            'admin_notes' => 'Menunggu konfirmasi jadwal pembersihan berkala dari UPTD Pasar dan Dinas Lingkungan Hidup.',
            'is_featured_live' => false,
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
        ]);

        Report::create([
            'user_id' => $budi->id,
            'ticket_number' => 'ETAM-202609-003',
            'category' => 'Jalan & Jembatan',
            'title' => 'Lubang Jalan Cukup Dalam Dekat Simpang Tiga Jl. Pesut Telah Diperbaiki',
            'description' => "Terdapat aspal amblas dan lubang sedalam 12 cm di dekat pertigaan Jl. Pesut - Jl. Timbau yang membahayakan warga.",
            'address' => 'Simpang Tiga Jl. Pesut, Kel. Timbau',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4310000,
            'longitude' => 116.9845000,
            'status' => 'resolved',
            'admin_notes' => 'Dinas PU Kukar telah melakukan penambalan aspal pada 20 September 2026.',
            'resolved_at' => now(),
            'moderated_by' => $admin->id,
            'moderated_at' => now(),
        ]);

        Report::create([
            'user_id' => $siti->id,
            'ticket_number' => 'ETAM-202609-004',
            'category' => 'Fasilitas Publik',
            'title' => 'Dahan Pohon Rindang Menyentuh Kabel Listrik Tegangan Menengah di Jl. Wolter Monginsidi',
            'description' => "Dahan pohon trembesi tua di dekat dermaga feri tradisional sudah sangat rimbun dan mulai bergesekan dengan kabel listrik PLN saat angin bertiup kencang.",
            'address' => 'Jl. Wolter Monginsidi RT 11',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4270000,
            'longitude' => 116.9820000,
            'status' => 'pending_verification',
        ]);

        // ==========================================
        // 12. SEED: SMART CITY - KONTAK DARURAT
        // ==========================================
        $emergencies = [
            [
                'name' => 'RSUD A.M. Parikesit Tenggarong Seberang (IGD 24 Jam)',
                'category' => 'rumah_sakit',
                'phone' => '(0541) 661013',
                'whatsapp' => '628115801118',
                'address' => 'Jl. Ratu Agung No. 1, Teluk Dalam, Tenggarong Seberang',
                'description' => 'Instalasi Gawat Darurat, Ambulans Cepat Tanggap, Trauma Center 24 Jam.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dinas Pemadam Kebakaran & Penyelamatan Kab. Kukar',
                'category' => 'damkar',
                'phone' => '(0541) 661113',
                'whatsapp' => '6281347000113',
                'address' => 'Jl. Jenderal Sudirman No. 1, Tenggarong',
                'description' => 'Penanganan kebakaran lahan, pemukiman, penyelamatan hewan berbisa, evakuasi darurat.',
                'sort_order' => 2,
            ],
            [
                'name' => 'SPKT Polres Kutai Kartanegara (Call Center 110)',
                'category' => 'polisi',
                'phone' => '110',
                'whatsapp' => '6281255889110',
                'address' => 'Jl. Wolter Monginsidi No. 84, Tenggarong',
                'description' => 'Layanan laporan darurat kepolisian, kecelakaan lalu lintas, gangguan kamtibmas.',
                'sort_order' => 3,
            ],
            [
                'name' => 'BPBD Kutai Kartanegara (Posko Siaga Bencana)',
                'category' => 'sar_bpbd',
                'phone' => '(0541) 663242',
                'whatsapp' => '6281255554321',
                'address' => 'Jl. Pahlawan, Kawasan Bukit Biru, Tenggarong',
                'description' => 'Posko siaga banjir luapan Mahakam, tanah longsor, karhutla, dan evakuasi air.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Puskesmas Tenggarong (Unit Gawat Darurat Tingkat 1)',
                'category' => 'puskesmas',
                'phone' => '(0541) 661204',
                'address' => 'Jl. Kartini No. 2, Melayu, Tenggarong',
                'description' => 'Pemeriksaan darurat dasar, rujukan, persalinan, poli umum.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Layanan Pengaduan Kebocoran & Air Mati PDAM Tirta Mahakam',
                'category' => 'pdam',
                'phone' => '(0541) 661066',
                'whatsapp' => '62811550505',
                'address' => 'Jl. KH Akhmad Muksin No. 55, Tenggarong',
                'description' => 'Call center pengaduan pipa bocor, distribusi air bersih, pasokan tangki darurat.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Layanan Gangguan Listrik PLN ULP Tenggarong',
                'category' => 'pln',
                'phone' => '123',
                'whatsapp' => '628122123123',
                'address' => 'Jl. Jenderal Sudirman, Tenggarong',
                'description' => 'Penanganan listrik padam, trafo meledak, kabel putus.',
                'sort_order' => 7,
            ],
        ];

        foreach ($emergencies as $e) {
            EmergencyContact::create($e);
        }

        // ==========================================
        // 13. SEED: SMART CITY - HARGA PANGAN HISTORIS
        // ==========================================
        $commodities = [
            ['market' => 'Pasar Tangga Arung', 'name' => 'Cabai Rawit Merah', 'cat' => 'bumbu_dapur', 'price' => 65000, 'prev' => 68000, 'unit' => 'kg', 'notes' => 'Stok lancar dari petani Loa Kulu'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Beras Mayas Asli Kukar Super', 'cat' => 'sembako', 'price' => 16500, 'prev' => 16500, 'unit' => 'kg', 'notes' => 'Panen lokal Sebulu & Muara Kaman'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Daging Sapi Segar Lokal', 'cat' => 'daging_ikan', 'price' => 150000, 'prev' => 150000, 'unit' => 'kg', 'notes' => 'Kualitas super segar'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Ikan Haruan (Gabus Mahakam)', 'cat' => 'daging_ikan', 'price' => 55000, 'prev' => 50000, 'unit' => 'kg', 'notes' => 'Pasokan sungai sedikit berkurang'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Ikan Patin Keramba Mahakam', 'cat' => 'daging_ikan', 'price' => 32000, 'prev' => 32000, 'unit' => 'kg', 'notes' => 'Stok melimpah dari peternak Kota Bangun'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Bawang Merah Brebes', 'cat' => 'bumbu_dapur', 'price' => 38000, 'prev' => 40000, 'unit' => 'kg', 'notes' => 'Harga mulai stabil'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Minyak Goreng Minyakita', 'cat' => 'sembako', 'price' => 16000, 'prev' => 16000, 'unit' => 'liter', 'notes' => 'Tersedia merata'],
            ['market' => 'Pasar Tangga Arung', 'name' => 'Telur Ayam Ras', 'cat' => 'telur_susu', 'price' => 30000, 'prev' => 29000, 'unit' => 'piring (30 btr)', 'notes' => 'Pasokan dari Samarinda & Blitar'],

            ['market' => 'Pasar Mangkurawang', 'name' => 'Cabai Rawit Merah', 'cat' => 'bumbu_dapur', 'price' => 64000, 'prev' => 67000, 'unit' => 'kg', 'notes' => 'Stok melimpah'],
            ['market' => 'Pasar Mangkurawang', 'name' => 'Beras Mayas Asli Kukar Super', 'cat' => 'sembako', 'price' => 16500, 'prev' => 16500, 'unit' => 'kg', 'notes' => 'Stabil'],
            ['market' => 'Pasar Mangkurawang', 'name' => 'Ikan Jelawat / Baung Mahakam', 'cat' => 'daging_ikan', 'price' => 60000, 'prev' => 65000, 'unit' => 'kg', 'notes' => 'Tangkapan nelayan Sungai Belayan'],
            ['market' => 'Pasar Mangkurawang', 'name' => 'Sayur Kangkung & Bayam Lokal', 'cat' => 'sayur_mayur', 'price' => 3000, 'prev' => 3000, 'unit' => 'ikat', 'notes' => 'Petani Bukit Biru'],
        ];

        foreach ($commodities as $c) {
            MarketPrice::create([
                'market_name' => $c['market'],
                'commodity_name' => $c['name'],
                'category' => $c['cat'],
                'price' => $c['price'],
                'previous_price' => $c['prev'],
                'unit' => $c['unit'],
                'recorded_date' => Carbon::now()->format('Y-m-d'),
                'notes' => $c['notes'],
                'created_by' => $admin->id,
            ]);
        }

        // ==========================================
        // 14. SEED: SMART CITY - LINGKUNGAN & INFRASTRUKTUR
        // ==========================================
        EnvironmentPoint::create([
            'title' => 'Tinggi Muka Air (TMA) Sungai Mahakam — Pos Pantau Dermaga Pulau Kumala',
            'slug' => 'tma-sungai-mahakam-pos-pantau-dermaga-pulau-kumala-' . Str::random(5),
            'info_type' => 'water_level',
            'severity' => 'normal',
            'description' => 'Tinggi muka air Sungai Mahakam terpantau pada level 4.10 meter di atas pasang surut rata-rata. Aliran sungai lancar, dermaga feri tradisional dan ponton tambang beroperasi normal.',
            'location_name' => 'Pos Pantau Dermaga Pulau Kumala Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4350000,
            'longitude' => 116.9790000,
            'source' => 'Badan Penanggulangan Bencana Daerah (BPBD) Kukar',
            'status_condition' => 'TMA: 4.10 M (Status: Aman / Hijau)',
            'updated_by' => $admin->id,
        ]);

        EnvironmentPoint::create([
            'title' => 'Peringatan Waspada Titik Genangan Pasang Sungai — Kawasan Loa Ipuh & Sukarame',
            'slug' => 'peringatan-waspada-genangan-pasang-sungai-loa-ipuh-' . Str::random(5),
            'info_type' => 'flood_alert',
            'severity' => 'warning',
            'description' => 'Diperkirakan terjadi kenaikan air pasang laut yang menahan aliran Mahakam pada malam hari pukul 20.00 - 23.00 WITA. Warga di bantaran Sungai Tenggarong (Loa Ipuh) diimbau mengamankan barang elektronik.',
            'location_name' => 'Bantaran Sungai Tenggarong, Kel. Loa Ipuh',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4210000,
            'longitude' => 116.9750000,
            'source' => 'BMKG Stasiun Meteorologi Sultan Aji Muhammad Sulaiman & BPBD Kukar',
            'status_condition' => 'Status: Waspada Pasang Perbani',
            'updated_by' => $admin->id,
        ]);

        EnvironmentPoint::create([
            'title' => 'Pemeliharaan Rutin Lampu Artistik & Sensor Jembatan Kutai Kartanegara',
            'slug' => 'pemeliharaan-rutin-lampu-jembatan-kukar-' . Str::random(5),
            'info_type' => 'infrastructure',
            'severity' => 'normal',
            'description' => 'Dinas PUPR Kukar melaksanakan pemeliharaan sistem pencahayaan dinamis dan kalibrasi sensor struktural Jembatan Kutai Kartanegara. Arus lalu lintas kedua arah tetap dibuka lancar.',
            'location_name' => 'Jembatan Kutai Kartanegara, Tenggarong - Tenggarong Seberang',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4430000,
            'longitude' => 117.0050000,
            'source' => 'Dinas Pekerjaan Umum & Penataan Ruang (PUPR) Kukar',
            'status_condition' => 'Operasional Lancar Normal',
            'updated_by' => $admin->id,
        ]);

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

        // ==========================================
        // 15. SEED: SMART CITY - BUDAYA & PARIWISATA
        // ==========================================
        CulturalDestination::create([
            'title' => 'Kedaton Kesultanan Kutai Kartanegara Ing Martadipura',
            'slug' => 'kedaton-kesultanan-kutai-kartanegara-' . Str::random(5),
            'category' => 'kesultanan',
            'description' => 'Kedaton Kesultanan Kutai Kartanegara adalah istana resmi Sultan Kutai Kartanegara yang berdiri megah di pusat kota Tenggarong. Bangunan berarsitektur khas perpaduan gaya Kutai dan modern ini menjadi pusat upacara adat sakral, penganugerahan gelar bangsawan, serta audiensi kesultanan.',
            'historical_context' => 'Kesultanan Kutai Kartanegara didirikan pada abad ke-13 di Jembayan oleh Aji Batara Agung Dewa Sakti dan berkembang menjadi kesultanan Islam tertua dan termasyhur di Tanah Borneo dengan ibukota di Tepian Pandan (kini Tenggarong).',
            'address' => 'Jl. Monumen, Panji, Kec. Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4278000,
            'longitude' => 116.9842000,
            'operating_info' => 'Buka untuk kunjungan area luar: Setiap Hari 08.00 - 17.00 WITA. Acara adat menyesuaikan kalender kesultanan.',
            'status' => 'published',
        ]);

        CulturalDestination::create([
            'title' => 'Museum Mulawarman (Eks Istana Kerajaan Kutai)',
            'slug' => 'museum-mulawarman-eks-istana-kerajaan-kutai-' . Str::random(5),
            'category' => 'museum_sejarah',
            'description' => 'Museum megah yang menyimpan ribuan artefak sejarah peradaban Kutai kuno hingga Kesultanan Kutai Kartanegara. Menyimpan singgasana raja berlapis emas, mahkota sultan, keris pusaka, prasasti yupa tiruan, keramik Dinasti Ming & Qing, dan perangkat gamelan Gajah Prawiro.',
            'historical_context' => 'Dibangun pada tahun 1936 oleh arsitek Belanda atas prakarsa Sultan Aji Muhammad Parikesit dengan struktur beton bergaya neo-klasik Eropa dipadukan ornamen ukiran khas Kutai.',
            'address' => 'Jl. Diponegoro No. 1, Kel. Panji, Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4261000,
            'longitude' => 116.9839000,
            'operating_info' => 'Selasa - Minggu: 09.00 - 15.30 WITA (Senin Tutup). Tiket: Rp 10.000 / orang.',
            'status' => 'published',
        ]);

        CulturalDestination::create([
            'title' => 'Pulau Kumala (Ikon Rekreasi Pulau Tengah Sungai Mahakam)',
            'slug' => 'pulau-kumala-ikon-rekreasi-sungai-mahakam-' . Str::random(5),
            'category' => 'wisata_alam',
            'description' => 'Pulau delta seluas 76 hektar di tengah megahnya aliran Sungai Mahakam. Dilengkapi jembatan penyeberangan pejalan kaki (Jembatan Repo-Repo), Patung Lembuswana raksasa, Sky Tower, cable car, dan taman rekreasi keluarga yang asri.',
            'historical_context' => 'Pulau Kumala terbentuk dari endapan sedimen Sungai Mahakam selama ratusan tahun dan ditata menjadi destinasi wisata unggulan sejak awal tahun 2000-an.',
            'address' => 'Kawasan Delta Sungai Mahakam, Kel. Timbau, Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4375000,
            'longitude' => 116.9772000,
            'operating_info' => 'Buka Setiap Hari: 08.00 - 18.00 WITA. Akses lewat Jembatan Repo-Repo atau Perahu Ketinting.',
            'status' => 'published',
        ]);

        CulturalDestination::create([
            'title' => 'Ladang Budaya (Ladaya) Tenggarong',
            'slug' => 'ladang-budaya-ladaya-tenggarong-' . Str::random(5),
            'category' => 'wisata_alam',
            'description' => 'Destinasi wisata alam dan kebudayaan bertema kearifan lokal Kukar dengan rumah pohon tradisional Kutai (Odah Rehat), kebun botani, wahana outbound, mini zoo satwa endemik Kalimantan, dan cafe bernuansa hutan tropis.',
            'historical_context' => 'Menjadi ruang kreatif dan pelestarian flora endemik hutan hujan Kalimantan Timur di perbukitan Tenggarong.',
            'address' => 'Jl. H. Bachrin Seman, Mangkurawang, Tenggarong',
            'location_district' => 'Tenggarong',
            'latitude' => -0.4220000,
            'longitude' => 116.9600000,
            'operating_info' => 'Buka Setiap Hari: 09.00 - 17.30 WITA.',
            'status' => 'published',
        ]);

        // ==========================================
        // 16. SEED: SYSTEM SETTINGS
        // ==========================================
        SystemSetting::set('site_name', 'Habar Etam', 'general');
        SystemSetting::set('site_tagline', 'Pusat Informasi & Kontribusi Warga Tenggarong - Kutai Kartanegara', 'general');
        SystemSetting::set('contact_email', 'redaksi@habaretam.id', 'contact');
        SystemSetting::set('contact_phone', '(0541) 661234', 'contact');
        SystemSetting::set('contact_whatsapp', '6281255551234', 'contact');
        SystemSetting::set('office_address', 'Jl. Wolter Monginsidi No. 12, Tenggarong, Kutai Kartanegara, Kalimantan Timur', 'contact');
        SystemSetting::set('studio_broadcast_active', '1', 'studio');
        SystemSetting::set('studio_partner_name', 'PT Surya Citra Media (SCM) Studio Hub', 'studio');

        // Initial Audit Log
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'system_initialized',
            'target_type' => 'system',
            'target_id' => 1,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'SeedRunner/1.0',
            'metadata' => ['message' => 'Database initialized with official Tenggarong & Kukar dataset.'],
            'created_at' => now(),
        ]);
    }
}
