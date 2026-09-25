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
        // 1. Kontak Darurat (Emergency Contacts)
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('category', [
                'ambulans',
                'damkar',
                'polisi',
                'rumah_sakit',
                'puskesmas',
                'sar_bpbd',
                'pdam',
                'pln',
                'posko_bencana'
            ])->default('rumah_sakit')->index();
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('location_district')->default('Tenggarong');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 2. Harga Pangan & Pasar (Market Commodity Prices - Historical)
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('market_name')->index(); // Pasar Tangga Arung, Pasar Mangkurawang, dll
            $table->string('commodity_name')->index(); // Cabai Rawit, Beras Mayas, Daging Sapi Segar, dll
            $table->enum('category', [
                'sembako',
                'sayur_mayur',
                'daging_ikan',
                'bumbu_dapur',
                'telur_susu',
                'buah'
            ])->default('sembako')->index();
            $table->decimal('price', 12, 2);
            $table->decimal('previous_price', 12, 2)->nullable();
            $table->string('unit')->default('kg'); // kg, liter, ikat, butir, papan
            $table->date('recorded_date')->index();
            $table->string('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. Lingkungan & Infrastruktur (Environment & Infrastructure Status)
        Schema::create('environment_points', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('info_type', [
                'water_level',      // Status Muka Air Mahakam
                'flood_alert',      // Titik Rawan Banjir / Pasang
                'road_damage',      // Perbaikan / Kerusakan Jalan
                'weather_alert',    // Peringatan Cuaca Ekstrem
                'landslide_prone',  // Titik Longsor
                'infrastructure'    // Fasilitas & Jembatan
            ])->default('water_level')->index();
            $table->enum('severity', ['normal', 'warning', 'danger'])->default('normal')->index();
            $table->longText('description');
            $table->string('location_name');
            $table->string('location_district')->default('Tenggarong');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('source')->default('BPBD Kutai Kartanegara');
            $table->string('status_condition')->nullable(); // e.g. "TMA Mahakam 4.15m (Normal)"
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 4. Budaya & Pariwisata Kukar (Culture & Heritage Destinations)
        Schema::create('cultural_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', [
                'kesultanan',      // Kesultanan Kutai Kartanegara Ing Martadipura
                'museum_sejarah',  // Museum Mulawarman, Museum Kayu
                'wisata_alam',     // Pulau Kumala, Danau Murung, Ladaya
                'festival_adat',   // Erau Pelas Benua, Ritual Belian
                'kuliner_tradisi'  // Tradisi Kuliner Kutai
            ])->default('kesultanan')->index();
            $table->longText('description');
            $table->longText('historical_context')->nullable();
            $table->text('address');
            $table->string('location_district')->default('Tenggarong');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('cover_image')->nullable();
            $table->string('operating_info')->nullable(); // Jam buka / tiket
            $table->enum('status', ['published', 'draft'])->default('published')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultural_destinations');
        Schema::dropIfExists('environment_points');
        Schema::dropIfExists('market_prices');
        Schema::dropIfExists('emergency_contacts');
    }
};
