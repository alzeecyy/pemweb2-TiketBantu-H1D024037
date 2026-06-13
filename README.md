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

- **Role Admin**:
  - Email: `admin@tiketbantu.com`
  - Nama: `Admin TiketBantu`
- **Role Agen**:
  - Email: `agen.jaringan@tiketbantu.com` (Nama: `Agen Jaringan`)
  - Email: `agen.hardware@tiketbantu.com` (Nama: `Agen Hardware`)
  - Email: `agen.software@tiketbantu.com` (Nama: `Agen Software`)
  - Email: `agen.fasilitas@tiketbantu.com` (Nama: `Agen Fasilitas`)
- **Role User/Pelapor**:
  - Email: `user@tiketbantu.com`
  - Nama: `Pelapor Umum`

---

## 📈 Bukti Kesesuaian Spesifikasi Dosen & Fitur SLA

Aplikasi **TiketBantu** telah dirancang untuk memenuhi spesifikasi pengujian dosen dengan rincian implementasi sebagai berikut:

### 1. SLA Tracker (Waktu Penyelesaian)
- **Lokasi Kode**: [ticket-show.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-show.blade.php#L162-L173) di bagian kanan bawah sidebar "Detail Status".
- **Database**: Menyimpan kolom `closed_at` di tabel `tickets` yang akan otomatis terisi `timestamp` saat status diubah menjadi **Selesai** atau **Ditutup**.
- **Perhitungan Rentang**: Menggunakan interval waktu Carbon antara `created_at` (saat tiket dibuat) dan `closed_at` (saat tiket diselesaikan).
- **Format Output**: Ditampilkan dalam rentang presisi berupa format: `X hari, Y jam, Z menit` (contoh: `2 hari, 4 jam, 15 menit`).
- **Statistik SLA**: Terdapat metrik **SLA Success Rate** pada [ticket-index.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-index.blade.php#L48-L58) yang menghitung persentase tiket yang telah berhasil diselesaikan/ditutup dari total keseluruhan aduan.

### 2. Tantangan Khusus Livewire 3/4
- **Komentar Realtime (Livewire Island)**: Diimplementasikan di [ticket-comments.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-comments.blade.php). Komponen menggunakan direktif `lazy` agar dimuat secara asinkron (Island khusus komentar) lengkap dengan placeholder shimmer loading skeleton saat proses pemuatan asinkron.
- **Drag-and-Drop Reordering (`wire:sortable`)**: Terintegrasi di [ticket-index.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-index.blade.php#L125) dengan direktif `wire:sortable="handleSort"`, `wire:sortable.item`, dan `wire:sortable.handle` untuk mengubah urutan penanganan tiket secara langsung ke database.
- **Badge Status & Prioritas Client-Side (`wire:show`)**: Terintegrasi di [ticket-show.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-show.blade.php#L106-L138) menggunakan direktif optimistic UI `wire:show="status === 'baru'"` dll. untuk merender status dan prioritas secara instan dari sisi klien.

---

## 📋 Tabel Pemenuhan Kriteria & Spesifikasi Soal

| No | Spesifikasi Tugas / Perintah Soal | Status | Lokasi Berkas Utama & Baris Kode |
| :-: | --------------------------------- | :---: | -------------------------------- |
| **1** | **Multi-Role (Admin, Agen, User)** | ✅ | [DatabaseSeeder.php](file:///c:/laragon/www/TiketBantu/database/seeders/DatabaseSeeder.php#L23-L75) (Seeding Akun)<br>[web.php](file:///c:/laragon/www/TiketBantu/routes/web.php#L28) (Proteksi Route) |
| **2** | **CRUD Tiket (Judul, Deskripsi, Kategori, dsb)** | ✅ | [TicketCreate.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketCreate.php) (Create)<br>[TicketIndex.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketIndex.php) (Read)<br>[TicketEdit.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketEdit.php) (Update)<br>[TicketShow.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketShow.php#L126) (Delete) |
| **3** | **Workflow Status (Baru, Diproses, Selesai, Ditutup)** | ✅ | [TicketShow.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketShow.php#L87-L94) (Logika closed_at)<br>[TicketEdit.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketEdit.php#L72) (Validasi workflow) |
| **4** | **Pencarian & Filter (Status, Prioritas, Kategori)** | ✅ | [TicketIndex.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketIndex.php#L80-L103) (Query reaktif) |
| **5** | **Upload Lampiran Valid (Max 5MB)** | ✅ | [TicketCreate.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketCreate.php#L32) (Validasi & Upload) |
| **6** | **Komentar Realtime (Island - Tantangan Khusus)** | ✅ | [TicketComments.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketComments.php#L31-L55) (Lazy Loading Placeholder)<br>[ticket-show.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-show.blade.php#L92) (Komponen `lazy`) |
| **7** | **Drag-and-Drop Sorting (Tantangan Khusus)** | ✅ | [ticket-index.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-index.blade.php#L125) (`wire:sortable`) |
| **8** | **Badge Client-side (Tantangan Khusus)** | ✅ | [ticket-show.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-show.blade.php#L106-L138) (`wire:show` dinamis) |
| **9** | **Hak Akses Agen Terproteksi** | ✅ | [TicketIndex.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketIndex.php#L69-L77) (Hanya melihat tiket miliknya & unassigned)<br>[TicketShow.php](file:///c:/laragon/www/TiketBantu/app/Livewire/TicketShow.php#L30-L32) (Proteksi abort 403) |
| **10** | **Fitur Nilai Tambah (SLA Tracker)** | ✅ | [ticket-show.blade.php](file:///c:/laragon/www/TiketBantu/resources/views/livewire/ticket-show.blade.php#L162-L173) (SLA diff format) |

---

## 🎬 Video Demo

[![Video Demo TiketBantu](https://img.youtube.com/vi/_QkFm23BhLQ/maxresdefault.jpg)](https://youtu.be/_QkFm23BhLQ)

🔗 **Tonton Video Demo**: [https://youtu.be/_QkFm23BhLQ](https://youtu.be/_QkFm23BhLQ)
