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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ticket_number')->unique()->index();
            $table->string('category'); // Jalan & Jembatan, Drainase & Banjir, Sampah & Kebersihan, Lampu & Penerangan, Fasilitas Publik, Ketertiban Umum, Pelayanan Publik
            $table->string('title');
            $table->longText('description');
            $table->text('address');
            $table->string('location_district')->default('Tenggarong');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->enum('status', [
                'pending_verification',  // Menunggu Verifikasi
                'processing_editorial', // Diproses Redaksi
                'live_agenda',          // Masuk Agenda Live
                'resolved',             // Selesai
                'rejected'              // Ditolak
            ])->default('pending_verification')->index();
            $table->text('admin_notes')->nullable()->comment('Internal notes for staff/editorial');
            $table->text('editorial_summary')->nullable()->comment('Broadcast script or public editorial briefing');
            $table->boolean('is_featured_live')->default(false)->index()->comment('Flagged for Studio SCM live broadcast');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('report_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('path');
            $table->enum('media_type', ['image', 'video'])->default('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_media');
        Schema::dropIfExists('reports');
    }
};
