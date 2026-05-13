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

## 🔄 Panduan Git Workflow (Push & Pull)
Untuk mencegah konflik kode (merge conflict), seluruh anggota tim DIWAJIBKAN bekerja di branch masing-masing (misal: `ansyah`, `cahya`, `anaqi`, `ilyas`). Jangan pernah ngoding langsung di branch `master`!

A. Cara Push (Menyimpan & Menggabungkan Pekerjaanmu ke Master)
Jalankan perintah ini saat kamu selesai mengerjakan suatu fitur di branch kamu:
1. `git add .` (Menyimpan semua perubahan)
2. `git commit -m "tipe: deskripsi perubahan kamu"` (Memberi pesan update)
   Gunakan awalan (tipe) berikut agar history rapi:
   * `feat:` (feature) - Jika menambah fitur/halaman baru. (Contoh: feat: tambah halaman login)
   * `fix:` (bug fix) - Jika memperbaiki error/bug. (Contoh: fix: perbaiki tabel yang hilang)
   * `chore:` (chore) - Jika mengurus hal teknis/maintenance seperti install library, update gitignore.
   * `docs:` (documentation) - Jika mengubah README atau dokumentasi.
   * `style:` (style) - Jika hanya merapikan CSS, spasi, atau format kode tanpa ubah logika.
3. `git push origin nama-branch-kamu` (Mengunggah branch kamu ke GitHub)
4. `git checkout master` (Pindah ke branch utama)
5. `git merge nama-branch-kamu` (Menggabungkan kodemu ke master)
6. `git push origin master` (Mengunggah master terbaru ke GitHub)
7. `git checkout nama-branch-kamu` (Kembali ke branch-mu untuk siap ngoding lagi)

B. Cara Pull (Mengambil Update Terbaru Teman dari Master)
Jalankan perintah ini SEBELUM kamu mulai ngoding agar kodemu tidak tertinggal:
1. `git checkout master` (Pindah ke branch utama)
2. `git pull origin master` (Menarik kode terbaru dari GitHub)
3. `php artisan view:clear` (WAJIB! Membersihkan cache tampilan Laravel)
4. `git checkout nama-branch-kamu` (Kembali ke branch kamu)
5. `git merge master` (Memasukkan update terbaru tadi ke dalam branch kamu)

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

## 📚 LARAVEL COURSE GoDigi Dev
Panduan arsitektur proyek untuk memudahkan anggota tim memahami alur kerja aplikasi.

1. Konsep Utama:
Menggunakan arsitektur MVC (Model - View - Controller) dengan Routes sebagai pintu gerbangnya.

2. Analogi Proyek = Restoran:
A. routes/web.php (Sang Pelayan)
- Pelayan yang menerima pesanan (URL) dari pengunjung, lalu mengarahkannya ke tempat yang tepat.

B. app/Http/Controllers (Sang Koki di Dapur)
- Pelayan menyerahkan pesanan ke Koki (Controller).
- Di sinilah tempat menulis logika bisnis, seperti: "Ambil data cireng dari database, lalu hitung pembayarannya, baru kirim ke halaman".

C. resources/views (Piring & Presentasi Makanan)
- Menampilkan hasil akhir ke pembeli (HTML, CSS, JS) dengan akhiran .blade.php.
- Koki (Controller) akan memberikan data (misal daftar menu), dan views ini yang menyusun agar rapi lewat komponen-komponen.
- Pembagian Folder Views:
  * layouts/: Tempat menyimpan file master layout (app.blade.php untuk pelanggan, admin.blade.php untuk CMS). Tugasnya menyediakan kerangka HTML dan ruang kosong dengan perintah @yield('content') yang nanti diisi halaman lain.
  * pages/: Tempat menyimpan halaman utuh yang dilihat pelanggan (misal index.blade.php, produk.blade.php). File di sini tidak butuh tag HTML dasar lagi, cukup meminjam kerangka dari layouts pakai @extends('layouts.app') lalu mengisi ruang spesifik pakai @section('content').
  * components/: Tempat potongan UI kecil (Template) yang bisa dipakai berulang (contoh: navbar.blade.php, footer.blade.php). Dipanggil di dalam pages menggunakan perintah @include('components.namaKomponen').
  * admin/: Berfungsi seperti pages namun dipisah khusus untuk ekosistem admin, yang dilihat oleh penjual/karyawan setelah login.

D. app/Models & database/ (Gudang Bahan Baku)
- app/Models/: Ibarat buku catatan yang mengenali struktur tabel MySQL (misal: Model Product untuk tabel produk).
- database/migrations/: Fitur keren Laravel. Tanpa buka phpMyAdmin untuk bikin tabel, cukup nulis struktur tabel pakai kode PHP di sini, lalu run `php artisan migrate`, dan tabel otomatis tercipta di MySQL.

E. Folder Penting Lainnya
- public/: Ibarat etalase depan. Semua file gambar, maskot, global.css, ikon, dll harus ditaruh di dalam public/assets atau public/css.
- .env: Ibarat brankas rahasia. Tempat menyimpan password database dan Key Midtrans.

3. Aturan Emas Tanda Kutip (Quotes) di Laravel Blade:
- Untuk mencegah error layar putih (ParseError), JANGAN mencampuradukkan tanda kutip ganda (") secara sembarangan.
- ATURAN: Jika Anda sedang berada di dalam atribut HTML yang menggunakan kutip ganda (contoh: class="..."), maka semua logika PHP di dalam {{ }} WAJIB 100% menggunakan kutip tunggal (').
- Contoh Salah: <a class="navItem {{ request()->is("admin/dashboard") ? "active" : "" }}">
- Contoh Benar: <a class="navItem {{ request()->is('admin/dashboard') ? 'active' : '' }}">

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
