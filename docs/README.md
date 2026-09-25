# Habar Etam V2 — Project Documentation

## 1. Project Overview

**Habar Etam** (`habaretam.id`) adalah portal informasi lokal untuk masyarakat Tenggarong dan Kutai Kartanegara (Kukar). Platform ini menggabungkan:

- portal informasi lokal,
- User-Generated Content (UGC),
- direktori warga dan usaha lokal,
- layanan informasi Smart City,
- pengaduan warga melalui **Lapor Etam**,
- dan feed data untuk kebutuhan redaksi / Studio PT SCM.

Tujuan utama proyek bukan membuat marketplace besar, tetapi membangun **platform informasi lokal yang hidup karena kontribusi warga dan dikelola melalui moderasi redaksi**.

## 2. Technology Stack

Gunakan:

- Laravel 12
- PHP 8.3+
- MySQL
- Tailwind CSS v3
- Vite
- NPM untuk package frontend tambahan yang benar-benar dibutuhkan
- Blade untuk rendering server-side
- JavaScript hanya untuk interaksi yang membutuhkan client-side behavior

### Larangan / batasan

Jangan menggunakan:

- Laravel Breeze
- Filament
- Blade `x-component`
- UI kit admin besar yang membuat tampilan menjadi generik
- dependency yang tidak memiliki alasan fungsional jelas

Gunakan Blade layouts dan partials biasa untuk menjaga struktur sederhana dan mudah dipelihara.

## 3. Existing Project Structure

Project berada pada:

```text
habar-etam-v2/
├── assets/
├── docs/
└── ...
```

Folder `assets/` sudah tersedia dan harus dianggap sebagai sumber aset resmi proyek. Jangan mengganti aset yang sudah tersedia dengan aset placeholder tanpa alasan.

## 4. Core Roles

### Tamu

Tidak perlu login untuk mengakses informasi publik.

Hak akses dibatasi pada:

- melihat konten publik,
- melihat direktori,
- membaca informasi Smart City,
- melihat event,
- melihat listing Jual Cepat,
- membaca informasi pengaduan yang memang dipublikasikan.

Tamu tidak dapat melakukan submission.

Jika tamu mencoba melakukan aksi yang membutuhkan akun:

> Login diperlukan untuk melanjutkan.

Jika belum memiliki akun, sediakan jalur registrasi.

### Member

Member adalah warga yang memiliki akun.

Member dapat:

- mengelola profil,
- membuat submission,
- mengirim loker,
- mengirim produk/jasa,
- mengirim informasi kuliner,
- membuat event,
- membuat komunitas,
- membuat listing Jual Cepat,
- mengirim pengaduan Lapor Etam.

Aksi yang membutuhkan status **Warga Terverifikasi** hanya dapat dilakukan setelah proses verifikasi selesai.

### Admin

Admin mengelola seluruh platform:

- verifikasi warga,
- moderasi konten,
- pengaduan,
- Smart City,
- redaksi,
- export,
- user management,
- audit log,
- pengaturan sistem.

## 5. Core Modules

### UGC

1. Bursa Kerja Lokal
2. Produk & Jasa
3. Kuliner Lokal
4. Event & Kegiatan
5. Klub & Komunitas
6. Jual Cepat

### Smart City

1. Kontak Darurat
2. Harga Pangan & Pasar
3. Lingkungan & Infrastruktur
4. Budaya & Pariwisata Kukar

### Lapor Etam

Pengaduan warga dengan:

- kategori,
- judul,
- deskripsi,
- lokasi,
- foto/video,
- status proses.

Status:

```text
Menunggu Verifikasi
        ↓
Diproses Redaksi
        ↓
Masuk Agenda Live
        ↓
Selesai
```

## 6. Important Product Principle

Habar Etam harus terasa seperti **platform lokal yang benar-benar digunakan warga**, bukan template marketplace, dashboard SaaS, atau website pemerintahan generik.

Semua keputusan desain dan implementasi harus mempertimbangkan:

- konteks Tenggarong,
- kemudahan akses smartphone,
- kecepatan menemukan informasi,
- kepercayaan pengguna,
- moderasi konten,
- dan kejelasan status informasi.
