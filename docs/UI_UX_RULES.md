# Habar Etam V2 — UI/UX Rules

## 1. Design Goal

Habar Etam harus terlihat seperti **produk digital lokal yang dirancang dengan sengaja**, bukan hasil generator AI, template Tailwind, atau dashboard SaaS yang diganti warna.

Target visual:

- editorial,
- lokal,
- modern,
- terpercaya,
- sedikit berkarakter,
- mudah digunakan di smartphone.

Hindari desain yang terlalu glossy.

## 2. Brand Palette

Identitas utama berasal dari logo Habar Etam.

### Brand colors

```text
Gold        #F5B51B
Gold Dark   #D99400
Black       #111111
Black Soft  #1A1A1A
White       #FFFFFF
```

Gold adalah accent utama.

Hitam menjadi fondasi visual.

Putih menjadi ruang bernapas dan surface utama.

Gunakan warna abu-abu hanya sebagai warna fungsional untuk border, muted text, disabled state, dan background sekunder.

Jangan menambahkan gradient neon, purple, cyan, pink, atau palette SaaS modern sebagai warna brand.

## 3. Avoid AI-Slop Patterns

Jangan gunakan:

- hero dengan gradient besar dan headline generik,
- tiga atau empat floating glass cards tanpa fungsi,
- terlalu banyak rounded cards,
- glassmorphism berlebihan,
- gradient text,
- blob background generik,
- icon dalam lingkaran untuk setiap menu,
- statistik besar yang tidak bermakna,
- layout identik dengan template SaaS,
- semua section dibuat card,
- shadow berat,
- animasi pada semua elemen,
- heading seperti "Empowering the Future of..." tanpa konteks lokal.

Desain harus mempunyai alasan.

## 4. Local Identity

Karakter Tenggarong/Kukar harus muncul secara subtil.

Gunakan:

- fotografi lokal jika tersedia,
- nama lokasi nyata,
- konteks kegiatan masyarakat,
- pola editorial yang terasa seperti portal lokal,
- elemen gold yang terinspirasi dari identitas logo.

Jangan menempelkan ornamen budaya secara berlebihan hanya untuk membuat website terlihat "lokal".

## 5. Typography

Prioritas:

- font sans-serif yang bersih,
- heading kuat tetapi tidak terlalu besar,
- body text mudah dibaca.

Gunakan maksimal dua keluarga font.

Hierarchy:

```text
Display
H1
H2
H3
Body
Small
Caption
```

Jangan membuat setiap heading uppercase.

## 6. Layout

Gunakan grid yang konsisten.

Desktop:

```text
max-width sekitar 1200–1280px
```

Mobile:

- satu kolom untuk konten utama,
- CTA mudah disentuh,
- tabel diubah menjadi card/list bila tidak nyaman,
- jangan memaksa desktop UI menjadi mobile.

Whitespace harus cukup.

Tidak semua area harus penuh.

## 7. Navigation

Public navbar:

- logo Habar Etam,
- menu inti,
- pencarian jika relevan,
- login/profil,
- CTA kontribusi.

Jangan memasukkan seluruh modul ke navbar.

Gunakan kategori / halaman eksplorasi untuk informasi yang lebih dalam.

## 8. Cards

Card hanya digunakan ketika informasi memang merupakan unit terpisah.

Contoh tepat:

- listing Jual Cepat,
- event,
- lowongan,
- tempat kuliner.

Hindari:

```text
Card → Card → Card → Card
```

untuk seluruh halaman.

Gunakan kombinasi:

- editorial list,
- split layout,
- table,
- compact rows,
- section blocks.

## 9. CTA

Primary CTA:

- Gold background
- Black text

Secondary CTA:

- white/transparent
- black border

Danger:

- gunakan red hanya untuk status/error yang membutuhkan perhatian.

CTA harus menjelaskan aksi:

```text
Kirim Pengaduan
Pasang Jual Cepat
Lihat Lowongan
Daftar Sebagai Warga
```

Hindari CTA generik:

```text
Get Started
Learn More
Explore Now
```

kecuali memang konteksnya sesuai.

## 10. Status

Status harus mudah dipahami.

Contoh:

```text
Menunggu Verifikasi
Diproses Redaksi
Dipublikasikan
Selesai
Ditolak
Terjual
Expired
```

Gunakan warna status secara fungsional. Gold tetap menjadi brand accent, bukan warna untuk semua status.

## 11. Forms

Form harus:

- memiliki label,
- helper text bila perlu,
- validation message,
- error state,
- required indicator,
- input yang cukup besar untuk mobile.

Jangan mengandalkan placeholder sebagai label.

## 12. Maps

Untuk lokasi:

- gunakan peta hanya ketika lokasi memang penting,
- sediakan alamat/text fallback,
- jangan membuat peta menjadi elemen dekoratif.

## 13. Images

Foto harus terasa sebagai bagian dari konten, bukan dekorasi random.

Gunakan rasio konsisten per jenis konten.

Jangan melakukan crop agresif pada foto warga atau produk jika informasi penting dapat hilang.

## 14. Motion

Animasi harus subtle.

Boleh:

- hover,
- fade,
- small translate,
- modal transition,
- loading state.

Jangan:

- animasi terus-menerus,
- parallax berlebihan,
- text flying,
- section entrance yang menghambat scanning.

Jika menggunakan GSAP/Lenis, gunakan hanya pada area yang memang mendapatkan manfaat.

## 15. Admin CMS UI

CMS berbeda dari public website.

Admin:

- lebih padat,
- lebih informatif,
- fokus workflow,
- sidebar jelas,
- table kuat,
- filter mudah digunakan.

Tetap gunakan brand:

- black sidebar,
- gold active state,
- white content area.

Jangan membuat admin penuh decorative elements.

## 16. Accessibility

Minimum:

- keyboard accessible,
- focus state jelas,
- contrast memadai,
- button bukan hanya icon,
- alt text untuk gambar,
- form error terhubung dengan input,
- touch target nyaman.

## 17. Responsive Priority

Prioritas:

```text
Mobile → Tablet → Desktop
```

Habar Etam harus nyaman ketika dibuka dari:

- browser smartphone,
- link WhatsApp,
- link TikTok,
- perangkat desktop.

## 18. Design Review Checklist

Sebelum halaman dianggap selesai, cek:

- Apakah layout terlihat seperti template?
- Apakah setiap card memang diperlukan?
- Apakah CTA jelas?
- Apakah halaman dapat dipahami tanpa animasi?
- Apakah mobile layout dirancang ulang, bukan sekadar diperkecil?
- Apakah gold digunakan secara terkontrol?
- Apakah informasi lokal menjadi fokus?
- Apakah whitespace cukup?
- Apakah halaman terasa seperti produk Habar Etam?
