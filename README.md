# 🎟️ TiketBantu - Sistem Helpdesk & Tiket Pengaduan

> [!NOTE]
> **Proyek Tugas Akhir Pemrograman Web 2**
> - **Nama**: Cut Alzeena Rency Fadania
> - **NIM**: H1D024037
> - **Kelas**: Pemrograman Web 2 A
> - **Paket Soal**: Paket 7 — Sistem Helpdesk / Tiket Pengaduan

---

## 📢 Tentang TiketBantu
**TiketBantu** adalah aplikasi helpdesk modern berbasis web untuk mengelola aduan dan kendala teknis secara terpusat. Aplikasi ini dirancang interaktif untuk menghubungkan tiga peran penting: **Pelapor (User)** yang mengajukan masalah, **Petugas (Agen)** yang menyelesaikan kendala, dan **Administrator (Admin)** yang memegang kendali penuh atas sistem. 

Aplikasi ini dibangun menggunakan performa kilat **Laravel 13** dan interaktivitas reaktif dari **Livewire 4** dengan gaya desain premium glassmorphism.

---

## ⚡ Fitur Utama

### 👥 Sistem Multi-Role (3 Hak Akses)
- **Administrator (Admin)**: 
  - Melihat statistik global dan ringkasan seluruh tiket di sistem.
  - Melakukan manajemen penuh (CRUD) Kategori Kendala dan Akun Pengguna.
  - Menugaskan Agen/Petugas penanggung jawab ke tiket secara manual.
- **Petugas (Agen)**:
  - Dashboard khusus "**Tugas Saya**" untuk memantau tiket yang sedang ditangani.
  - Fitur "**Klaim Tiket**" untuk mengambil alih antrean aduan yang belum memiliki petugas.
  - Memperbarui status penanganan dan tingkat prioritas tiket.
- **Pelapor (User/Customer)**:
  - Membuat tiket aduan baru dengan lampiran berkas/bukti foto pendukung.
  - Memantau riwayat tiket aduan pribadi di halaman dashboard khusus.

### 🧩 Fitur Modern & Eksklusif Livewire 4
- **Drag & Drop Reordering (`wire:sort`)**: Admin dan Agen dapat mengatur urutan prioritas pengerjaan tiket dengan menyeret kartu tiket secara visual (didukung SortableJS).
- **Livewire Island (Komentar Realtime)**: Thread diskusi atau komentar pada halaman detail tiket terisolasi secara independen dan reaktif tanpa mengganggu reload halaman utama.
- **Pencarian & Filter Instan**: Cari judul aduan atau filter berdasarkan status, kategori, dan prioritas secara instan.

### 🎁 Fitur Nilai Tambah (Value Added)
- **SLA Tracker (Lama Penyelesaian)**: Sistem menghitung otomatis durasi pengerjaan tiket secara akurat (hari, jam, menit) sejak tiket dibuat hingga ditandai Selesai.
- **Upload File Valid**: Mendukung unggahan lampiran gambar/dokumen dengan batas ukuran maksimal 5MB dan proteksi format file (`jpg`, `jpeg`, `png`, `pdf`, `doc`, `docx`, `zip`, `txt`).
- **Notifikasi Email Otomatis**: Pelapor akan otomatis mendapatkan email notifikasi ketika status tiket mereka berubah.

---

## 🛠️ Kebutuhan Sistem
- PHP >= 8.3
- Composer >= 2.0
- Node.js >= 18.0 & NPM
- MySQL / MariaDB

---

## 💻 Langkah Instalasi & Uji Coba

1. **Unduh repositori**:
   ```bash
   git clone <repository-url>
   cd TiketBantu
   ```

2. **Instal dependensi Composer (PHP)**:
   ```bash
   composer install
   ```

3. **Instal dependensi NPM (Frontend)**:
   ```bash
   npm install
   ```

4. **Siapkan Konfigurasi Environment**:
   Salin file `.env.example` menjadi `.env` lalu buat database baru bernama `tiketbantu` di MySQL Anda.
   ```bash
   copy .env.example .env
   ```
   *Sesuaikan detail koneksi database di file `.env` jika username/password database Anda berbeda.*

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Migrasi Database & Seed Data Demo**:
   ```bash
   php artisan migrate --seed
   ```

7. **Compile Asset CSS & JS**:
   ```bash
   npm run build
   ```

8. **Nyalakan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Buka browser dan akses alamat `http://127.0.0.1:8000`.

---

## 🔑 Akun Demo Pengujian
Untuk mempermudah pengujian, gunakan akun demo di bawah ini dengan password default: `password`

- **Role Admin**: `admin@tiketbantu.com`
- **Role Agen**: `agen.jaringan@tiketbantu.com` *(Agen khusus penanganan Jaringan)*
- **Role User/Pelapor**: `user@tiketbantu.com`

---

## 📸 Demo Tampilan
*(Screenshot Section)*

*(Link Video Demo)*
