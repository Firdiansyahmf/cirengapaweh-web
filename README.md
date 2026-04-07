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

Admin Dashboard (CMS):
* Secure Authentication: Sistem login admin terproteksi.
* Manajemen Produk: CRUD menu Fastfood & Frozen Food.
* Manajemen Lokasi: Pengelolaan data cabang operasional.
* Manajemen Promo: Kontrol banner promo beserta masa berlakunya.

---

## 🤝 Aturan Kolaborasi Tim (Wajib Dibaca!)
Karena tim pengembang menggunakan sistem operasi yang berbeda (Linux & Windows), mohon patuhi 2 aturan emas berikut sebelum melakukan commit/push:

1. Konfigurasi Line Ending (Mencegah Error Git):
   * Pengguna Windows wajib menjalankan: git config --global core.autocrlf true
   * Pengguna Linux/Mac wajib menjalankan: git config --global core.autocrlf input
2. Case Sensitivity:
   * Linux sangat sensitif terhadap huruf besar/kecil. Selalu gunakan huruf kecil dan strip (-) untuk penamaan file gambar, CSS, dan JS (Contoh: logo-utama.png, BUKAN Logo Utama.png).

---

## ⚙️ Panduan Instalasi (Local Environment)

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan proyek ini di laptop masing-masing:

### 1. Persiapan Awal
Pastikan di komputer Anda sudah terinstal:
* PHP (Minimal versi 8.2+)
* Composer
* MySQL / MariaDB (XAMPP/MAMP/Native)
* Git

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
3. Buka file .env di VS Code, cari bagian DB_CONNECTION, lalu ubah menjadi seperti ini:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_cirengapaweh
   DB_USERNAME=root
   DB_PASSWORD=
   (Isi DB_PASSWORD jika MySQL Anda menggunakan password).
4. Jalankan perintah migrasi untuk membuat tabel-tabel bawaan:
   php artisan migrate

### 5. Jalankan Server
php artisan serve
Aplikasi sekarang dapat diakses melalui http://localhost:8000.

---

## 🛠️ Troubleshooting (Solusi Error Umum)

Jika Anda menemui error saat pertama kali menjalankan proyek, periksa daftar solusi berikut:

* Error: Failed to open stream... vendor/autoload.php
  * Penyebab: Folder vendor tidak ikut ter-upload ke Git, jadi mesin Laravel tidak ditemukan.
  * Solusi: Jalankan `composer install` di terminal.
* Error: No application encryption key has been specified
  * Penyebab: File .env belum memiliki kunci APP_KEY.
  * Solusi: Matikan server, jalankan `php artisan key:generate`, lalu nyalakan server lagi.
* Error: Database file at path [.../database.sqlite] does not exist
  * Penyebab: Laravel versi 11 mencari file SQLite bawaan, padahal kita menggunakan MySQL.
  * Solusi: Pastikan Anda sudah mengikuti Langkah 4 (Setup Database) di atas. Ubah DB_CONNECTION=sqlite menjadi DB_CONNECTION=mysql di file .env, lalu jalankan `php artisan migrate`.
* Error: ext-xml or ext-dom is missing (Khusus Pengguna Linux)
  * Penyebab: Ekstensi PHP bawaan Linux ada yang kurang.
  * Solusi: Jalankan `sudo apt install php8.4-xml` (sesuaikan versi PHP Anda), lalu ulangi `composer install`.
* Error: ParseError: unexpected end of file atau layar putih kosong
  * Penyebab: Ada kesalahan ketik pada file Blade (kutip ganda bertabrakan) atau cache sistem tersangkut.
  * Solusi: Jalankan perintah `php artisan view:clear` di terminal untuk membersihkan cache tampilan. Ingat aturan penulisan: Gunakan kutip ganda (") untuk atribut HTML, dan kutip tunggal (') di dalam logika kurung kurawal Laravel {{ }}.

---

## 📄 Lisensi & Hak Cipta

© 2026 GoDigi Dev. All rights reserved.

Proyek ini dikembangkan oleh tim GoDigi Dev dan merupakan hak cipta eksklusif. Source code ini diunggah untuk keperluan portofolio dan pemenuhan tugas perkuliahan.
