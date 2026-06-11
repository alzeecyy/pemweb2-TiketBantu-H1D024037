<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            // Menghubungkan berkas ke Tiket terkait
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');

            $table->string('file_path'); // Lokasi penyimpanan file di storage
            $table->string('file_name'); // Nama asli file saat diunggah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};