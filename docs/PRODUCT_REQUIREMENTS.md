# Habar Etam V2 — Product Requirements

## 1. Product Direction

Habar Etam adalah portal lokal berbasis kontribusi warga.

Pengguna tidak hanya membaca informasi, tetapi dapat ikut mengisi ekosistem melalui submission yang dimoderasi.

Alur fundamental:

```text
Tamu
  ↓
Membaca informasi
  ↓
Ingin berkontribusi
  ↓
Login / Registrasi
  ↓
Validasi NIK
  ↓
Verifikasi Warga
  ↓
Submission
  ↓
Moderasi Admin
  ↓
Publikasi
```

## 2. Public Website

### Beranda

Beranda harus menjadi pintu masuk ke berbagai kebutuhan lokal, bukan sekadar hero besar.

Prioritas konten:

1. navigasi utama,
2. informasi lokal yang sedang relevan,
3. akses cepat ke kategori utama,
4. Jual Cepat,
5. event,
6. Lapor Etam,
7. Smart City,
8. konten lokal terbaru.

Hero tidak boleh mendominasi seluruh halaman.

### Bursa Kerja

Menampilkan lowongan lokal.

Data minimum:

- posisi,
- perusahaan/pemberi kerja,
- deskripsi,
- lokasi,
- tipe pekerjaan,
- tenggat waktu,
- kontak,
- status.

### Produk & Jasa

Direktori usaha, UMKM, freelancer, dan penyedia jasa lokal.

Data minimum:

- nama usaha,
- deskripsi,
- kategori,
- alamat,
- foto,
- WhatsApp,
- lokasi,
- jam operasional jika tersedia.

### Kuliner

Direktori tempat makan lokal.

Data minimum:

- nama,
- jenis kuliner,
- deskripsi,
- rentang harga,
- alamat,
- koordinat,
- foto,
- kontak.

Review tidak boleh dibuat sebagai sistem rating kompleks pada tahap awal. Prioritaskan informasi yang berguna.

### Event

Menampilkan kegiatan publik.

Data:

- nama event,
- tanggal,
- waktu,
- lokasi,
- deskripsi,
- penyelenggara,
- kontak,
- poster/foto,
- status.

### Komunitas

Direktori komunitas lokal.

Data:

- nama,
- deskripsi,
- bidang/minat,
- jadwal,
- lokasi,
- narahubung,
- kontak,
- media sosial.

### Jual Cepat

**Jual Cepat berbeda dari Produk & Jasa.**

Tujuan:

> Membantu warga Tenggarong menjual barang secara cepat dengan harga menarik.

Contoh:

- HP,
- laptop,
- furniture,
- elektronik,
- kendaraan,
- barang pribadi lainnya.

Data:

- judul,
- harga,
- kondisi,
- deskripsi,
- foto,
- lokasi,
- kontak,
- pemilik,
- tanggal listing,
- status.

Status:

```text
Menunggu Moderasi
Published
Terjual
Expired
Ditolak
```

Jangan membangun sistem checkout, keranjang, payment gateway, escrow, atau marketplace transaction pada tahap ini.

### Smart City

#### Kontak Darurat

Menampilkan:

- nama instansi,
- jenis layanan,
- nomor telepon,
- WhatsApp bila ada,
- alamat,
- status aktif.

#### Harga Pangan

Menampilkan harga komoditas berdasarkan pasar dan tanggal.

Contoh:

```text
Komoditas: Cabai Rawit
Harga: Rp xx.xxx / kg
Pasar: Pasar Tangga Arung
Tanggal: ...
```

Riwayat harga dapat tersedia di halaman detail.

#### Lingkungan & Infrastruktur

Menampilkan informasi:

- titik rawan,
- kondisi infrastruktur,
- informasi kebencanaan,
- status muka air,
- lokasi,
- waktu update,
- sumber informasi.

#### Budaya & Pariwisata

Menampilkan:

- destinasi,
- tradisi,
- ritual adat,
- sejarah,
- informasi Kesultanan Kutai Kartanegara,
- lokasi,
- foto.

### Lapor Etam

Pengaduan hanya dapat dibuat oleh member yang memenuhi syarat verifikasi.

Data:

- kategori,
- judul,
- deskripsi,
- lokasi,
- latitude,
- longitude,
- foto,
- video,
- tanggal laporan,
- pelapor.

Status:

1. Menunggu Verifikasi
2. Diproses Redaksi
3. Masuk Agenda Live
4. Selesai

Jangan menampilkan NIK atau informasi pribadi sensitif pelapor kepada publik.

## 3. Authentication & Registration

Form registrasi:

- nama lengkap,
- email,
- WhatsApp,
- password,
- password confirmation,
- NIK,
- tanggal lahir.

### NIK

NIK harus:

- terdiri dari 16 digit,
- divalidasi format,
- divalidasi struktur wilayah sesuai kebutuhan Kukar,
- disimpan terenkripsi.

Validasi wilayah berdasarkan prefix administratif yang telah ditentukan untuk Kukar.

**Catatan implementasi:** jangan menganggap prefix NIK saja sebagai bukti bahwa seseorang benar-benar penduduk aktif. Prefix hanya validasi struktur/wilayah. Status akhir tetap melalui proses verifikasi internal.

### Tanggal Lahir

Sistem dapat mengambil informasi tanggal lahir dari digit NIK sesuai aturan NIK Indonesia dan membandingkannya dengan tanggal lahir yang dimasukkan pengguna.

Jika tidak cocok:

```text
Data tanggal lahir tidak sesuai dengan NIK.
```

Jangan membocorkan detail parsing NIK kepada pengguna secara berlebihan.

### Status akun

```text
pending
verified
rejected
suspended
```

Akun pending belum memiliki akses penuh terhadap fitur kontribusi.

## 4. Submission Workflow

Semua UGC mengikuti pola:

```text
Member
  ↓
Create Submission
  ↓
Draft
  ↓
Submit
  ↓
Pending Moderation
  ↓
Admin Review
  ├── Reject
  ├── Request Revision
  └── Approve
          ↓
      Published
```

Setiap submission harus memiliki:

- author/member,
- status,
- created_at,
- updated_at,
- published_at jika sudah dipublikasikan.

## 5. Guest-to-Member Conversion

Jangan membuat guest melihat halaman login tanpa konteks.

Ketika guest mencoba melakukan aksi restricted:

```text
Aksi ini membutuhkan akun warga.

[Masuk]
[Daftar sebagai Warga]
```

Jika pengguna berasal dari URL submission, setelah login/registrasi sebaiknya diarahkan kembali ke aksi awal apabila aman dan masih valid.

## 6. Content Trust

Konten yang berasal dari warga harus memiliki indikator:

- Terverifikasi
- Menunggu moderasi
- Informasi diperbarui
- Sumber / pengirim bila memang layak ditampilkan

Jangan menggunakan badge berlebihan.
