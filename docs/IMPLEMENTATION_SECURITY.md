# Habar Etam V2 — Implementation & Security Rules

## 1. General Rule

Implementasikan fitur secara sederhana dan maintainable.

Jangan melakukan over-engineering sebelum kebutuhan nyata muncul.

Prioritas:

1. correctness,
2. security,
3. maintainability,
4. usability,
5. performance,
6. abstraction.

## 2. Laravel Structure

Gunakan:

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Middleware/
├── Models/
├── Policies/
└── Services/
```

Gunakan Form Request untuk validasi form yang cukup kompleks.

Jangan menaruh seluruh business logic di Blade.

## 3. Authentication

Buat authentication sendiri menggunakan Laravel primitives.

Jangan memasang Breeze.

Fitur minimum:

- login,
- register,
- logout,
- password hashing,
- session regeneration,
- forgot/reset password jika scope mengharuskan.

Password wajib menggunakan Laravel hashing.

## 4. Authorization

Gunakan policy/authorization server-side.

Contoh:

```text
admin can moderate
member can create own submission
member can update own draft
member cannot update another member's content
guest cannot create submission
```

Jangan mengandalkan route yang hanya disembunyikan dari UI.

## 5. CSRF

Semua state-changing browser form harus menggunakan CSRF protection Laravel.

Jangan mematikan CSRF middleware.

## 6. XSS

Semua user-generated content harus dianggap tidak terpercaya.

Hindari rendering HTML mentah dari user.

Untuk Blade:

```blade
{{ $content }}
```

lebih aman sebagai default daripada:

```blade
{!! $content !!}
```

HTML raw hanya boleh jika memang diperlukan dan telah disanitasi.

## 7. SQL Injection

Gunakan:

- Eloquent,
- Query Builder,
- parameter binding.

Jangan melakukan query SQL dengan string concatenation dari input user.

## 8. NIK Protection

NIK adalah data sensitif.

Rules:

- encryption at rest,
- jangan tampilkan NIK penuh,
- jangan log NIK plaintext,
- jangan masukkan NIK ke analytics,
- jangan masukkan NIK ke export biasa,
- jangan tampilkan NIK pada URL,
- gunakan authorization ketat.

Jika exact search dibutuhkan:

```text
encrypted value
+
blind/hash index
```

## 9. Date of Birth Validation

Validasi:

1. format tanggal,
2. parse tanggal dari NIK,
3. bandingkan dengan input user,
4. simpan hasil validasi,
5. kirim ke moderation apabila perlu review.

Jangan menganggap validasi algoritmik sebagai bukti identitas absolut.

## 10. File Upload Security

Untuk foto/video:

- whitelist MIME type,
- limit file size,
- generate safe filename,
- jangan percaya extension dari client,
- simpan di storage,
- jangan menjalankan uploaded file sebagai executable.

Image processing sebaiknya dilakukan server-side bila dibutuhkan.

## 11. Rate Limiting

Pasang rate limit pada:

- login,
- register,
- OTP jika nanti digunakan,
- upload,
- submission,
- report creation.

Jangan membuat endpoint publik tanpa perlindungan jika dapat disalahgunakan untuk spam.

## 12. Mass Assignment

Gunakan `$fillable` atau `$guarded` dengan sadar.

Jangan menerima seluruh request:

```php
Model::create($request->all());
```

untuk model sensitif.

Gunakan validated data:

```php
$data = $request->validated();
```

## 13. Slug

Slug publik harus:

- unique,
- tidak mengandung data sensitif,
- tidak menggunakan NIK,
- tidak menggunakan nomor telepon.

## 14. Moderation

Jangan langsung publish UGC.

Default:

```text
draft
→ pending
→ approved
→ published
```

Admin action harus dicatat pada audit log.

## 15. Error Handling

Production:

- `APP_DEBUG=false`
- jangan tampilkan stack trace,
- jangan bocorkan database credentials,
- jangan bocorkan path internal,
- log error server-side.

User harus menerima pesan yang aman dan jelas.

## 16. Environment

Secrets harus berada di `.env`.

Jangan commit:

```text
.env
database credentials
API keys
private keys
```

## 17. Performance

Prioritas:

- eager loading untuk relasi,
- pagination,
- index database,
- image optimization,
- lazy loading gambar,
- cache untuk data publik yang jarang berubah.

Jangan melakukan query database berulang di Blade.

## 18. Database

Gunakan migration untuk semua perubahan schema.

Jangan mengedit production database secara manual tanpa migration yang sesuai.

Index kolom yang sering digunakan untuk:

- status,
- user_id,
- published_at,
- created_at,
- foreign keys.

## 19. Logging

Log aktivitas teknis yang berguna.

Jangan log:

- password,
- NIK plaintext,
- session secret,
- token,
- informasi sensitif yang tidak diperlukan.

## 20. Deployment Checklist

Sebelum production:

```text
APP_ENV=production
APP_DEBUG=false
APP_URL=...
```

Lakukan:

```text
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

Pastikan:

- storage link tersedia,
- permission storage benar,
- database backup tersedia,
- queue/scheduler jika digunakan sudah dikonfigurasi,
- HTTPS aktif.

## 21. Definition of Done

Fitur dianggap selesai apabila:

- functional flow berjalan,
- validation lengkap,
- authorization benar,
- responsive,
- loading/empty/error state tersedia,
- tidak ada obvious security issue,
- tidak ada console error yang tidak perlu,
- query tidak menghasilkan N+1 yang jelas,
- UI mengikuti UI/UX rules,
- tidak menggunakan placeholder yang tertinggal,
- tidak membuat abstraction berlebihan.
