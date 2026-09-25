# Habar Etam V2 — Data & Architecture

## 1. Architecture

Gunakan arsitektur Laravel konvensional yang sederhana:

```text
Browser
   ↓
Routes
   ↓
Controllers
   ↓
Form Requests
   ↓
Services (jika logic cukup kompleks)
   ↓
Eloquent Models
   ↓
MySQL
```

Blade digunakan untuk presentation.

Jangan membuat service layer untuk setiap CRUD sederhana. Gunakan service hanya ketika logic memang membutuhkan pemisahan, misalnya:

- verifikasi NIK,
- moderation workflow,
- publication workflow,
- export,
- encryption/decryption handling.

## 2. Main Tables

Struktur awal yang direkomendasikan:

```text
users
profiles
verifications

jobs
businesses
culinary_places
events
communities
quick_sales

reports
report_media

emergency_contacts
market_prices
environment_points
cultural_destinations

submissions / moderation_logs
notifications
audit_logs
```

Struktur dapat disesuaikan saat implementasi berdasarkan kebutuhan relasi sebenarnya.

## 3. Users

Minimum:

```text
id
name
email
phone
password
role
status
email_verified_at
created_at
updated_at
```

Role:

```text
admin
member
```

Guest tidak disimpan sebagai user.

## 4. Profiles

Pisahkan data profil dari credentials.

Contoh:

```text
id
user_id
display_name
date_of_birth
avatar
bio
address
created_at
updated_at
```

## 5. Verification

Contoh:

```text
id
user_id
nik_encrypted
nik_hash
date_of_birth
nik_region_valid
birth_date_valid
status
verified_by
verified_at
rejection_reason
created_at
updated_at
```

### NIK Security

Jangan menyimpan NIK plaintext.

Gunakan encryption application-level Laravel.

Jika pencarian berdasarkan NIK diperlukan, gunakan hash/index terpisah untuk kebutuhan exact lookup.

Jangan menggunakan NIK sebagai primary key.

## 6. Submission Ownership

Setiap konten UGC harus memiliki:

```text
user_id
status
moderated_by
moderated_at
rejection_reason
published_at
created_at
updated_at
```

Gunakan foreign key dan index pada kolom yang sering difilter.

## 7. Quick Sales

Contoh:

```text
quick_sales
- id
- user_id
- title
- slug
- description
- price
- condition
- location_name
- latitude
- longitude
- contact_phone
- status
- published_at
- expires_at
- created_at
- updated_at
```

Media sebaiknya berada di tabel terpisah:

```text
quick_sale_media
- id
- quick_sale_id
- path
- sort_order
```

## 8. Reports

```text
reports
- id
- user_id
- category
- title
- description
- latitude
- longitude
- address
- status
- moderated_by
- moderated_at
- created_at
- updated_at
```

Media:

```text
report_media
- id
- report_id
- path
- type
```

## 9. Status Management

Gunakan enum PHP atau constant yang konsisten.

Jangan menyebarkan string status mentah ke seluruh controller dan Blade.

Contoh:

```text
pending
approved
rejected
published
expired
sold
```

Untuk Lapor Etam:

```text
pending_verification
processing_editorial
live_agenda
resolved
```

## 10. Audit Log

Catat aktivitas penting admin:

```text
id
user_id
action
target_type
target_id
metadata
ip_address
user_agent
created_at
```

Contoh action:

```text
member_verified
submission_approved
submission_rejected
report_status_changed
quick_sale_removed
user_suspended
```

Metadata tidak boleh menyimpan NIK plaintext atau password.

## 11. File Upload

Jangan menyimpan file upload langsung sebagai blob database.

Simpan:

- path,
- filename,
- mime type bila diperlukan,
- size,
- owner/reference.

Validasi:

- MIME type,
- extension,
- ukuran,
- image dimensions bila relevan.

Video memiliki limit yang lebih ketat daripada gambar.

## 12. Authorization

Gunakan:

- middleware,
- policies,
- gates jika diperlukan.

Jangan hanya menyembunyikan tombol di Blade.

Authorization harus tetap ditegakkan di server.

Contoh:

```text
Member A tidak boleh mengedit submission milik Member B.
Member biasa tidak boleh membuka route admin.
Guest tidak boleh memanggil endpoint submission.
```
