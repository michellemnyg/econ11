<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            // 1. Data Pemohon (Dari Form)
            $table->string('nip', 18);
            $table->string('nama', 200);
            $table->text('jabatan')->nullable();
            $table->text('instansi')->nullable();
            $table->string('email', 200)->nullable();
            $table->string('no_hp', 50)->nullable();

            // 2. Data Sesi & Topik
            $table->foreignId('topik_id')->constrained('topiks');
            $table->text('deskripsi_masalah')->nullable();
            $table->date('tanggal');
            $table->string('sesi', 100); // Menyimpan format "Sesi 1 (09:00 - 09:45)"

            // 3. Status & Petugas (Admin Domain)
            // Sesuai dengan ConsultationStatus di TypeScript
            $table->foreignId('petugas_id')->nullable()->constrained('users')->nullOnDelete(); // Relasi ke admin/narasumber

            // 4. Integrasi Zoom S2S
            $table->string('zoom_meeting_id')->nullable();
            $table->text('zoom_link')->nullable();
            $table->string('zoom_passcode')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
