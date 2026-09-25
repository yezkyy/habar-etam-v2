<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bursa Kerja (Job Vacancies)
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('company');
            $table->string('employment_type')->default('Purna Waktu'); // Purna Waktu, Paruh Waktu, Kontrak, Magang, Freelance
            $table->string('location')->default('Tenggarong');
            $table->string('salary_range')->nullable();
            $table->longText('description');
            $table->text('requirements')->nullable();
            $table->date('deadline')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone');
            $table->string('contact_email')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // 2. Produk & Jasa (Businesses & UMKM)
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->default('Jasa & UMKM'); // Kuliner & Olahan, Jasa & Servis, Kerajinan & Kriya, Fashion, Toko Kelontong, dll
            $table->longText('description');
            $table->text('address');
            $table->string('location_district')->default('Tenggarong');
            $table->string('phone_whatsapp');
            $table->string('instagram')->nullable();
            $table->string('photo')->nullable();
            $table->string('operating_hours')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // 3. Kuliner Lokal (Culinary Places)
        Schema::create('culinary_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('culinary_type')->default('Kuliner Khas & Cafe'); // Khas Kutai, Cafe & Kopi, Rumah Makan, Seafood Mahakam, Jajanan Pasar
            $table->string('price_range')->default('Rp 15.000 - Rp 50.000');
            $table->longText('description');
            $table->text('address');
            $table->string('location_district')->default('Tenggarong');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('phone_whatsapp')->nullable();
            $table->string('photo')->nullable();
            $table->string('operating_hours')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // 4. Event & Kegiatan (Events)
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Budaya & Kesenian'); // Budaya & Adat, Olahraga, Musik & Seni, Festival, Edukasi & Seminar, Komunitas
            $table->string('organizer');
            $table->date('start_date')->index();
            $table->date('end_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('location_name');
            $table->text('location_address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->longText('description');
            $table->string('contact_phone')->nullable();
            $table->string('poster_image')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // 5. Klub & Komunitas (Communities)
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('interest_category')->default('Seni & Budaya'); // Seni & Budaya, Olahraga, Otomotif, Hobi & Kreatif, Sosial Kemanusiaan, IT & Digital
            $table->longText('description');
            $table->string('activity_schedule')->nullable();
            $table->string('base_location')->default('Tenggarong');
            $table->string('contact_person')->nullable();
            $table->string('contact_phone');
            $table->string('social_media')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['draft', 'pending', 'published', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        // 6. Jual Cepat (Quick Sales) & Media
        Schema::create('quick_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Elektronik'); // Gadget & HP, Komputer & Laptop, Kendaraan & Motor, Elektronik Rumah, Perabot Rumah, Hobi & Koleksi, Lainnya
            $table->decimal('price', 15, 2);
            $table->enum('condition', ['Baru', 'Bekas - Seperti Baru', 'Bekas - Normal/Bagus', 'Bekas - Apa Adanya'])->default('Bekas - Seperti Baru');
            $table->longText('description');
            $table->string('location_name')->default('Tenggarong');
            $table->string('contact_phone');
            $table->string('contact_whatsapp');
            $table->enum('status', ['pending', 'published', 'sold', 'expired', 'rejected'])->default('pending')->index();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('quick_sale_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quick_sale_id')->constrained('quick_sales')->cascadeOnDelete();
            $table->string('path');
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_sale_media');
        Schema::dropIfExists('quick_sales');
        Schema::dropIfExists('communities');
        Schema::dropIfExists('events');
        Schema::dropIfExists('culinary_places');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('job_vacancies');
    }
};
