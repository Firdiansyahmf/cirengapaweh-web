# Cireng A'paweh - Web Ordering Platform & Admin Management 🥟🔥

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Midtrans](https://img.shields.io/badge/Midtrans-00A9E0?style=for-the-badge&logo=midtrans&logoColor=white)

Repositori ini berisi *source code* untuk proyek Ujian Akhir Semester (UAS) mata kuliah **Pemrograman Web** (Semester 4), Program Studi **Software Engineering**, Universitas Pendidikan Indonesia (UPI).

Proyek ini adalah platform pemesanan *end-to-end* untuk **Cireng A'paweh**, sebuah merek camilan khas Sunda modern. Terdiri dari halaman *Customer-facing* yang responsif dan *Content Management System* (Dashboard Admin) untuk mengelola operasional bisnis.

## 🚀 Fitur Utama
**Customer Facing Web:**
* **Responsive UI/UX:** Desain antarmuka *Mobile-First* menggunakan Native CSS Variables.
* **Sistem Templating Blade:** Komponen UI yang modular dan *reusable*.
* **Integrasi Payment Gateway:** Pembayaran *seamless* via *pop-up* (Snap JS) Midtrans.
* **Order Tracking System:** Pelacakan status pesanan secara *real-time*.

**Admin Dashboard (CMS):**
* **Secure Authentication:** Sistem login admin terproteksi dengan *Middleware* Laravel.
* **Manajemen Produk:** CRUD (Create, Read, Update, Delete) menu Fastfood & Frozen Food.
* **Manajemen Lokasi:** Pengelolaan data cabang operasional Cireng A'paweh.
* **Manajemen Promo:** Kontrol *banner* promo beserta masa berlakunya.
* **Manajemen Role/Admin:** Hak akses untuk Multi-level Admin & Superadmin.

## 🛠️ Tech Stack
* **Backend:** Laravel (PHP)
* **Frontend:** Native HTML5, Native CSS3, Vanilla JavaScript
* **Database:** MySQL (via Eloquent ORM)
* **3rd Party Services:** Midtrans Payment Gateway API

## 📂 Struktur Repositori Utama
* `public/` - Penyimpanan asset statis (CSS, JS, Images) untuk Web & Admin.
* `resources/views/layouts/` - Berisi *Master Layout* (`app.blade.php` & `admin.blade.php`).
* `resources/views/admin/` - Halaman khusus Dashboard CMS.
* `resources/views/pages/` - Halaman utama (*Landing Page*).

## ⚙️ Cara Menjalankan di Local Environment
1. Clone repositori ini:
   ```bash
   git clone https://github.com/Firdiansyahmf/cirengapaweh-web.git