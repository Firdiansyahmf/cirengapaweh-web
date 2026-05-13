# Cireng A'paweh - Web Ordering Platform & Admin Management 🥟🔥

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

Repositori ini berisi source code untuk proyek Ujian Akhir Semester (UAS) mata kuliah Pemrograman Web (Semester 4), Program Studi Software Engineering, Universitas Pendidikan Indonesia (UPI).

Proyek ini adalah platform pemesanan end-to-end untuk Cireng A'paweh, sebuah merek camilan khas Sunda modern. Terdiri dari halaman Customer-facing yang responsif dan Content Management System (Dashboard Admin) untuk mengelola operasional bisnis.

---

## 🚀 Fitur Utama
Customer Facing Web:
* Responsive UI/UX: Desain antarmuka Mobile-First menggunakan Native CSS Variables.
* Sistem Templating Blade: Komponen UI yang modular dan reusable (Hero, Promo, dll).
* Integrasi Payment Gateway: Pembayaran seamless via Midtrans (Dalam Pengembangan).
* Live Customer Support: Integrasi chatbot pintar yang otomatis mengarahkan pelanggan ke Live Chat realtime dengan penjual/CS jika dibutuhkan. Dilengkapi fitur untuk mengakhiri sesi obrolan (close session) ketika masalah pelanggan sudah terselesaikan.

Admin Dashboard (CMS):
* Secure Authentication: Sistem login admin terproteksi.
* Manajemen Produk: CRUD menu Fastfood & Frozen Food.
* Manajemen Lokasi: Pengelolaan data cabang operasional.
* Manajemen Promo: Kontrol banner promo beserta masa berlakunya.

---

## 🤝 Aturan Kolaborasi Tim (Wajib Dibaca!)
Karena tim pengembang menggunakan sistem operasi yang berbeda (Linux & Windows), mohon patuhi 2 aturan emas berikut sebelum melakukan commit/push:

1. Konfigurasi Line Ending (Mencegah Error Git):
   * Pengguna Windows wajib menjalankan: `git config --global core.autocrlf true`
   * Pengguna Linux/Mac wajib menjalankan: `git config --global core.autocrlf input`
2. Case Sensitivity:
   * Linux sangat sensitif terhadap huruf besar/kecil. Selalu gunakan huruf kecil dan strip (-) untuk penamaan file gambar, CSS, dan JS (Contoh: logo-utama.png, BUKAN Logo Utama.png).

---

## ⚙️ Panduan Instalasi (Local Environment)

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan proyek ini di laptop masing-masing:

### 1. Persiapan Awal
Pastikan di komputer Anda sudah terinstal:
* PHP (Wajib Minimal versi 8.4+) - Cek dengan perintah `php -v` di terminal.
* Composer (Versi terbaru)
* MySQL / MariaDB
* Git
* Catatan untuk Pengguna Windows: Sangat disarankan menggunakan Laravel Herd untuk menghindari konflik versi PHP dan ekstensi dibandingkan menggunakan XAMPP versi lama.

### 2. Clone & Install Dependensi
Buka terminal/Command Prompt dan jalankan:
git clone https://github.com/Firdiansyahmf/cirengapaweh-web.git
cd cirengapaweh-web

# Mengunduh semua library framework Laravel (Akan memunculkan folder /vendor)
composer install

### 3. Konfigurasi Environment & Keamanan
Laravel membutuhkan file konfigurasi lokal (.env) dan kunci keamanan aplikasi.

# Menggandakan file contoh env
cp .env.example .env

# Membuat Application Key
php artisan key:generate

### 4. Setup Database (MySQL)
1. Buka MySQL Anda (via terminal atau phpMyAdmin).
2. Buat database baru kosong dengan nama: db_cirengapaweh
3. Buka file .env di VS Code, cari bagian DB_CONNECTION, lalu ubah dengan menghubungi pemilik terlebih dahulu.
4. Jalankan perintah migrasi untuk membuat tabel-tabel bawaan:
   php artisan migrate

### 5. Jalankan Server
php artisan serve
Aplikasi sekarang dapat diakses melalui http://localhost:8000.

---

## 🛠️ Troubleshooting (Solusi Error Umum)

Jika Anda menemui error saat pertama kali menjalankan proyek, periksa daftar solusi berikut:

* Error: your php version (8.x.x) does not satisfy that requirement
  * Penyebab: Versi PHP di komputer Anda terlalu lama. Proyek ini membutuhkan PHP 8.4.
  * Solusi: Silakan upgrade PHP Anda, install ulang XAMPP terbaru, atau gunakan Laravel Herd.

* Error: ext-fileinfo, ext-openssl, atau pdo_mysql is missing (Khusus Pengguna Windows/XAMPP)
  * Penyebab: Ekstensi bawaan PHP Windows masih dinonaktifkan.
  * Solusi: Buka file `php.ini` (biasanya di `C:\xampp\php\php.ini`). Cari dan hapus tanda titik koma (`;`) pada baris berikut agar menjadi:
    `extension=fileinfo`
    `extension=mbstring`
    `extension=openssl`
    `extension=pdo_mysql`
    Simpan file, restart terminal, lalu jalankan `composer install` lagi. Dilarang menggunakan perintah `--ignore-platform-req`!

* Error: Call to undefined function Illuminate\Support\mb_split()
  * Penyebab: Ekstensi `mbstring` di PHP belum aktif.
  * Solusi: Ikuti langkah mengaktifkan ekstensi di `php.ini` seperti di atas, nyalakan `extension=mbstring`, save, lalu jalankan ulang `php artisan serve`.

* Error: Failed to open stream... vendor/autoload.php
  * Penyebab: Folder vendor tidak ikut ter-upload ke Git.
  * Solusi: Jalankan `composer install` di terminal.

* Error: Database file at path [.../database.sqlite] does not exist
  * Penyebab: Laravel mencari file SQLite bawaan, padahal kita menggunakan MySQL.
  * Solusi: Pastikan konfigurasi `.env` bagian `DB_CONNECTION=mysql` sudah benar dan port diatur ke `3306`.

* Error: ParseError: unexpected end of file atau layar putih kosong
  * Penyebab: Ada kesalahan ketik pada file Blade (kutip ganda bertabrakan) atau cache sistem tersangkut.
  * Solusi: Jalankan perintah `php artisan view:clear`. Ingat aturan penulisan: Gunakan kutip ganda (") untuk atribut HTML, dan kutip tunggal (') di dalam logika Blade {{ }}.

---

## 📄 Lisensi & Hak Cipta

© 2026 GoDigi Dev. All rights reserved.

Proyek ini dikembangkan oleh tim GoDigi Dev dan merupakan hak cipta eksklusif. Source code ini diunggah untuk keperluan portofolio dan pemenuhan tugas perkuliahan.
