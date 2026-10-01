# Product Manager 

Aplikasi web inventaris produk berbasis **PHP (PDO)** dan **MySQL** yang dirancang khusus untuk mengelola data produk perlengkapan alat belajar secara *real-time*, efisien, dan terstruktur. Proyek ini dibangun untuk memenuhi standar pengembangan aplikasi web modern dengan antarmuka yang bersih serta sistem keamanan yang ketat.

---

## 1. Deskripsi Proyek
Product Manager adalah sistem manajemen inventaris berbasis web yang memudahkan penggunanya dalam melakukan pencatatan, pemantauan, dan pengelolaan stok barang secara digital. Berfokus pada tema perlengkapan alat belajar (seperti buku, alat tulis, dan peralatan sekolah), aplikasi ini dilengkapi dengan dashboard analitik otomatis untuk memantau nilai aset serta mendeteksi ketersediaan stok secara langsung.

---

## 2. Tujuan Proyek
Adapun beberapa tujuan utama dari pengembangan aplikasi Product Manager ini adalah:
* **Digitalisasi Manajemen Stok**: Mengganti sistem pencatatan inventaris alat belajar manual ke dalam bentuk sistem digital berbasis web yang lebih cepat, minim kesalahan, dan terpusat.
* **Optimalisasi Pengelolaan Katalog**: Memudahkan admin atau pengguna dalam menambah, memperbarui, memantau, dan menghapus data produk alat belajar secara efisien melalui fitur CRUD yang interaktif.
* **Keamanan Data dan Transaksi**: Menerapkan standar keamanan tingkat lanjut seperti perlindungan terhadap serangan *CSRF* dan pencegahan *SQL Injection* untuk menjamin integritas data inventaris.
* **Memenuhi Standar Akademik**: Disusun sebagai bentuk pemenuhan tugas dan proyek pengembangan perangkat lunak berbasis web dengan penerapan arsitektur pemrograman yang bersih (*Clean Code*).

---

## 3. Kriteria dan Fitur Utama
Aplikasi ini dikembangkan dengan memenuhi berbagai kriteria fungsional dan teknis untuk memastikan performa yang optimal:

| No | Fitur | Deskripsi Implementasi |
| :--- | :--- | :--- |
| 1 | **Create** | Menambah data perlengkapan alat belajar baru dengan validasi panjang nama (min. 3 karakter), harga/stok non-negatif, dan cek duplikasi nama. |
| 2 | **Read** | Menampilkan katalog produk secara *real-time* dalam bentuk Card Layout responsif dan tabel data yang rapi. |
| 3 | **Update** | Mengubah data produk berbasis ID dengan tetap menerapkan aturan validasi ketat pada sisi server. |
| 4 | **Delete** | Menghapus data produk secara aman dari sistem database berdasarkan parameter ID yang spesifik. |

* **Keamanan Enterprise**:
  * **CSRF Protection**: Melindungi setiap form pengiriman data dari eksploitasi berbahaya menggunakan modul token keamanan khusus (`includes/csrf.php`).
  * **SQL Injection Prevention**: Seluruh kueri basis data menggunakan *Prepared Statements* pada objek PDO untuk keamanan data yang maksimal.

---

## 4. Struktur Proyek & Penjelasan Direktori
Berikut adalah rincian hierarki file dan direktori yang terdapat di dalam proyek `product-manager`:

product-manager/
├── assets/
│   └── style.css          # Berkas gaya antarmuka (stylesheet) tema Dark/Indigo modern
├── config/
│   └── database.php       # Berkas konfigurasi utama untuk koneksi database menggunakan PDO
├── includes/
│   └── csrf.php           # Helper modul keamanan dan validasi token CSRF
├── database.sql           # Berkas skema tabel MySQL dan data awal perlengkapan alat belajar
├── index.php              # Halaman utama, dashboard statistik, dan katalog produk
├── create.php             # Antarmuka form dan logika backend untuk menambah produk baru
├── edit.php               # Antarmuka form dan logika backend untuk mengubah data produk
├── delete.php             # Proses backend untuk mengeksekusi penghapusan data produk
└── README.md              # Dokumentasi lengkap proyek

---

## 5. Alur Kerja Sistem (Workflow)
1. **Inisialisasi & Koneksi Database (`config/database.php`)**: Koneksi ke server database dikelola secara terpusat menggunakan PHP Data Objects (PDO) yang dilengkapi penanganan error *try-catch* demi keamanan dan kestabilan sistem.
2. **Pemuatan Skema Database (`database.sql`)**: Struktur tabel `products` diinisialisasi untuk menampung data penting seperti ID, nama barang, kategori, harga, stok, dan waktu pembuatan data otomatis.
3. **Dashboard & Pemrosesan Statistik (`index.php`)**: Saat halaman utama diakses, sistem mengeksekusi *query* agregat untuk menghitung jumlah total varian produk dan total nilai inventaris secara dinamis.
4. **Operasi Data (Tambah, Ubah, Hapus)**: Setiap aksi perubahan data divalidasi dengan cermat di sisi server sebelum disimpan atau dieksekusi pada database MySQL.

---

## 6. Struktur Tabel Database (`database.sql`)
Berikut adalah rincian kolom yang digunakan pada tabel `products` di dalam database:

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | INT (Auto Increment) | ID unik produk sebagai *Primary Key* |
| `name` | VARCHAR(255) | Nama produk perlengkapan alat belajar |
| `category` | VARCHAR(100) | Kategori barang (Contoh: Alat Tulis, Buku, dll.) |
| `price` | INT | Harga satuan barang dalam bentuk angka (Rupiah) |
| `stock` | INT | Jumlah ketersediaan stok barang di inventaris |
| `created_at` | TIMESTAMP | Waktu pencatatan data secara otomatis |

---

## 7. Teknologi yang Digunakan (Tech Stack)
* **Backend Language**: PHP (Native / Object-Oriented approach with PDO)
* **Database Management**: MySQL / MariaDB (dijalankan melalui local server XAMPP)
* **Frontend Styling & UI**: HTML5, CSS3 (`assets/style.css`), Google Fonts (Inter), Flexbox & CSS Grid
* **Development Environment**: Visual Studio Code

---

## 8. Contoh Data Awal Perlengkapan Alat Belajar (Dummy Data)
Berikut adalah daftar sampel data produk alat belajar yang otomatis dimuat ke dalam database dalam bentuk tabel:

| Nama Produk | Kategori | Harga Satuan | Jumlah Stok |
| :--- | :--- | :--- | :--- |
| Buku Tulis Kiky | Alat Tulis | Rp 5.000 | 20 Pcs |
| Pulpen Standard AE7 | Alat Tulis | Rp 3.500 | 50 Pcs |
| Penggaris Besi 30cm | Alat Ukur | Rp 4.000 | 15 Pcs |
| Correction Tape Joyko | Peralatan | Rp 8.000 | 25 Pcs |
| Buku Gambar A4 | Buku | Rp 6.500 | 10 Pcs |
| Pensil 2B Faber Castell | Alat Tulis | Rp 4.500 | 30 Pcs |
| Penghapus Karet Joyko | Peralatan | Rp 2.000 | 40 Pcs |
| Spidol Warna Joyko | Alat Tulis | Rp 15.000 | 12 Pcs |

---

## 9. Prasyarat Sistem (Prerequisites)
Sebelum menjalankan aplikasi ini, pastikan perangkat Anda telah terpasang perangkat lunak berikut:
* **Web Server**: Apache (terintegrasi dalam XAMPP versi terbaru).
* **Database**: MySQL / MariaDB.
* **PHP Engine**: Minimal PHP versi 8.0 atau yang lebih baru dengan ekstensi PDO aktif.
* **Browser**: Google Chrome, Mozilla Firefox, atau Microsoft Edge versi terbaru.

---

## 10. Cara Menjalankan Proyek (Installation Guide)
Bagi Anda yang ingin menjalankan proyek ini di komputer lokal, ikuti langkah-langkah di bawah ini:
1. Pastikan aplikasi **XAMPP** sudah terinstal dan layanan **Apache** serta **MySQL** telah diaktifkan (*Start*).
2. Unduh atau kumpulkan folder proyek `product-manager` ke dalam direktori server lokal Anda (biasanya terletak di `C:\xampp\htdocs\`).
3. Buka browser, akses **phpMyAdmin** (`http://localhost/phpmyadmin/`), lalu buat database baru dengan nama `product_manager_db`.
4. Pilih database tersebut, masuk ke menu **Import**, lalu pilih dan unggah file `database.sql` yang ada di dalam folder proyek.
5. Buka tab browser baru dan jalankan aplikasi dengan mengakses tautan berikut:
   ```text
   http://localhost/product-manager/
