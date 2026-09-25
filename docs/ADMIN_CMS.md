# Habar Etam V2 — Admin CMS Specification

## 1. CMS Objective

CMS digunakan oleh tim internal untuk:

- mengelola warga,
- memverifikasi akun,
- memoderasi konten,
- mengelola data Smart City,
- memproses pengaduan,
- menyiapkan data untuk redaksi,
- dan menjaga kualitas informasi.

CMS bukan aplikasi SaaS generik. UI harus fokus pada pekerjaan admin.

## 2. Sidebar

Gunakan struktur:

```text
DASHBOARD
├── Dashboard
└── Aktivitas Terbaru

WARGA
├── Data Member
├── Verifikasi Warga
└── Role & Permission

KONTEN WARGA
├── Moderasi Submission
├── Bursa Kerja
├── Produk & Jasa
├── Kuliner
├── Event
├── Komunitas
└── Jual Cepat

LAPOR ETAM
├── Pengaduan
└── Peta Pengaduan

SMART CITY
├── Kontak Darurat
├── Harga Pangan
├── Lingkungan & Infrastruktur
└── Budaya & Pariwisata

REDAKSI
├── Feed Studio SCM
└── Export Data

SISTEM
├── Notifikasi
├── Audit Log
└── Pengaturan
```

Banner/Hero CMS dan manajemen kategori **tidak diperlukan**.

## 3. Dashboard

Dashboard menampilkan informasi yang membantu admin menentukan pekerjaan berikutnya.

Prioritas:

- Pending verification
- Pending moderation
- Pengaduan baru
- Jual Cepat menunggu moderasi
- Event terbaru
- aktivitas admin terbaru

Gunakan angka yang punya konteks.

Contoh:

```text
12
Menunggu Verifikasi

8
Submission Perlu Ditinjau

4
Pengaduan Baru
```

Jangan memenuhi dashboard dengan puluhan card statistik.

## 4. Data Member

Tabel:

- Nama
- Email
- WhatsApp
- Status
- Status verifikasi
- Jumlah submission
- Bergabung
- Action

Fitur:

- search,
- filter,
- detail,
- suspend,
- activate.

NIK jangan ditampilkan penuh.

## 5. Verifikasi Warga

Tampilan review harus membantu admin memeriksa data tanpa membuat NIK terekspos berlebihan.

Informasi:

- nama,
- tanggal lahir,
- NIK masked,
- hasil validasi wilayah,
- hasil kecocokan tanggal lahir,
- tanggal registrasi.

Action:

```text
Approve
Reject
```

Reject harus meminta alasan.

## 6. Moderasi Submission

Sediakan inbox moderasi.

Filter:

- tipe konten,
- status,
- tanggal,
- author.

Detail review harus menyediakan:

- preview konten,
- data pengirim,
- media,
- lokasi,
- history moderasi,
- action.

Action:

```text
Approve
Reject
Request Revision
```

## 7. Jual Cepat

Admin dapat:

- approve listing,
- reject listing,
- edit data yang diperlukan untuk moderasi,
- tandai terjual,
- nonaktifkan,
- hapus konten yang melanggar aturan.

Admin harus dapat melihat:

- pemilik,
- barang,
- harga,
- kondisi,
- foto,
- lokasi,
- kontak.

## 8. Lapor Etam

Gunakan status workflow:

```text
Menunggu Verifikasi
↓
Diproses Redaksi
↓
Masuk Agenda Live
↓
Selesai
```

Admin dapat:

- membuka detail,
- melihat lokasi,
- melihat bukti,
- mengubah status,
- memberikan catatan internal,
- menandai selesai.

Peta pengaduan digunakan untuk melihat persebaran laporan.

## 9. Smart City Management

### Kontak Darurat

CRUD sederhana.

### Harga Pangan

Input:

- pasar,
- komoditas,
- harga,
- satuan,
- tanggal.

Jangan menghapus historical data ketika harga berubah. Tambahkan record baru agar histori tetap tersedia.

### Lingkungan

Input:

- jenis informasi,
- lokasi,
- status,
- deskripsi,
- waktu update,
- sumber.

### Budaya & Pariwisata

Input konten editorial dengan media dan lokasi bila relevan.

## 10. Feed Studio PT SCM

Feed menampilkan data terbaru yang dapat digunakan redaksi.

Filter:

- jenis konten,
- tanggal,
- status.

Contoh source:

```text
Jual Cepat
Event
Bursa Kerja
Lapor Etam
Kontak Darurat
Harga Pangan
```

Berikan aksi:

```text
View
Copy
Export
Mark as Used
```

Jika tracking penggunaan konten terlalu kompleks untuk MVP, `Mark as Used` dapat ditunda.

## 11. Export

Format utama:

- CSV
- XLSX jika diperlukan

Export harus mengikuti permission.

Jangan pernah memasukkan:

- password,
- NIK plaintext,
- token,
- data sensitif yang tidak dibutuhkan.

## 12. Notifications

Notifikasi internal admin:

- member baru menunggu verifikasi,
- submission baru,
- laporan baru,
- konten membutuhkan review.

Tidak perlu membuat sistem notification yang kompleks pada MVP.

## 13. Audit Log

Admin dapat melihat:

- actor,
- action,
- target,
- timestamp.

Audit log tidak boleh dapat diedit melalui UI biasa.

## 14. Pengaturan

Hanya pengaturan operasional yang memang dibutuhkan:

- nama website,
- logo,
- kontak,
- WhatsApp,
- email,
- social links,
- alamat,
- informasi footer.

Jangan membuat CMS settings menjadi konfigurasi database serbaguna.
