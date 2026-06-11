<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Pengaduan
            $table->text('description'); // Deskripsi Masalah
            
            // Prioritas & Status sesuai instruksi soal
            $table->enum('priority', ['low', 'medium', 'high'])->default('low');
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditutup'])->default('baru');
            
            // Foreign Keys (Menghubungkan ke tabel categories & users)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pelapor
            
            // Agent ID dibuat nullable karena saat tiket baru dibuat, belum ada agen yang ditugaskan
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null'); // Petugas
            
            // Kolom pendukung Tantangan Khusus Livewire 4 (wire:sort drag-and-drop)
            $table->integer('sort_order')->default(0);
            
            // Kolom pendukung Nilai Tambah (Hitung SLA / Lama Penyelesaian)
            $table->timestamp('closed_at')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};