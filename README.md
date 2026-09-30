# Product Manager

Aplikasi web inventaris produk berbasis **PHP (PDO)** dan **MySQL** yang dirancang untuk mengelola data produk, memantau statistik stok secara *real-time*, serta melakukan operasi CRUD (Create, Read, Update, Delete) secara lengkap, aman, dan terstruktur dengan antarmuka bertema *Dark Mode Enterprise*.

## 📂 Struktur Proyek & Penjelasan File (Directory Structure)

product-manager/
├── assets/
│   └── style.css          # Berkas gaya antarmuka (stylesheet) dengan tema Dark/Indigo modern
├── config/
│   └── database.php       # Berkas konfigurasi utama untuk koneksi database menggunakan PDO
├── includes/
│   └── csrf.php           # Modul keamanan untuk menghasilkan dan memvalidasi token CSRF
├── database.sql           # Berkas skema database MySQL serta data awal (dummy data)
├── schema.php             # Skrip PHP otomatisasi untuk membuat database & tabel secara instan
├── index.php              # Halaman utama (Dashboard Statistik, Ringkasan, & Tabel Daftar Produk)
├── create.php             # Form antarmuka & logika backend untuk menambah produk baru
├── edit.php               # Form antarmuka & logika backend untuk mengubah data produk
├── delete.php             # Proses backend untuk menghapus data produk berdasarkan ID
└── README.md              # Dokumentasi lengkap proyek

## 🔄 Alur Kerja Sistem (Workflow)

1. Inisialisasi & Koneksi Database (`config/database.php`):
   - Aplikasi terhubung ke server MySQL menggunakan **PDO (PHP Data Objects)** melalui konfigurasi terpusat dengan penanganan error (*try-catch*) yang aman dan responsif.
2. Skema & Otomatisasi Database (`schema.php` & `database.sql`):
   - Database `product_manager_db` beserta tabel `products` diinisialisasi melalui file SQL atau dijalankan secara otomatis via file `schema.php` di browser.
3. Dashboard Utama & Statistik (`index.php`):
   - Saat halaman diakses, sistem melakukan *query* ke database untuk menghitung statistik penting secara otomatis: 
     - **Total Jenis Produk:** Jumlah keseluruhan varian item produk yang terdaftar.
     - **Total Nilai Aset:** Kalkulasi otomatis perkalian harga dan stok seluruh produk (`Harga × Stok`).
     - **Stok Kritis (Low Stock Alert):** Indikator peringatan jika terdapat produk dengan jumlah stok di bawah 5 item.
   - Seluruh data produk ditarik dan ditampilkan dalam bentuk tabel HTML terstruktur secara urut kronologis, lengkap dengan tombol aksi **Edit** dan **Hapus**.
4. Tambah Produk Baru (`create.php`):
   - Pengguna mengisi form interaktif (Nama, Kategori, Harga, dan Stok).
   - Data divalidasi dan diamankan menggunakan token keamanan dari `includes/csrf.php` untuk mencegah serangan *CSRF*, lalu disimpan ke database melalui *prepared statement* PDO.
5. Ubah Data Produk (`edit.php`):
   - Pengguna memilih produk yang ingin diperbarui. Form akan terisi data lama secara otomatis, kemudian data baru disimpan kembali ke database.
6. Hapus Produk (`delete.php`):
   - Ketika tombol hapus dikonfirmasi, sistem memvalidasi permintaan dan mengeksekusi perintah `DELETE` SQL berdasarkan parameter `id` produk yang dipilih.

## ✨ Fitur Utama (Features)

1. Dashboard Statistik Otomatis:
   - Kalkulasi real-time untuk total jenis produk, total nilai aset, dan peringatan stok kritis.
2. Manajemen Data Lengkap (CRUD):
   - **Create:** Input data produk baru dengan validasi form ketat.
   - **Read:** Menampilkan daftar produk secara terstruktur dan kronologis.
   - **Update:** Memperbarui informasi detail produk dengan mudah.
   - **Delete:** Menghapus data produk secara permanen dari database.
3. Keamanan Enterprise:
   - **CSRF Protection:** Melindungi setiap form dari eksploitasi pengiriman data berbahaya menggunakan modul `includes/csrf.php`.
   - **SQL Injection Prevention:** Seluruh kueri menggunakan *Prepared Statements* pada **PDO**.

## 🛠️ Teknologi yang Digunakan (Tech Stack)

- **Backend Language:** PHP (Native / PDO)
- **Database Management:** MySQL / MariaDB (via XAMPP)
- **Frontend Styling:** HTML5, CSS3 (`assets/style.css`), Google Fonts (Inter)
- **Local Web Server:** Apache (XAMPP)

## ⚙️ Panduan Instalasi & Menjalankan Proyek (Installation Guide)

1. Pindahkan Folder Proyek:
   - Letakkan seluruh folder proyek ke dalam direktori server lokal XAMPP Anda: `C:\xampp\htdocs\product-manager/`
2. Nyalakan Server XAMPP:
   - Buka aplikasi **XAMPP Control Panel**, lalu klik **Start** pada modul **Apache** dan **MySQL**.
3. Konfigurasi Database:
   - Buka browser dan akses `http://localhost/phpmyadmin/`.
   - Buat database baru dengan nama `product_manager_db`.
   - **Opsi A:** Import file `database.sql` yang ada di dalam folder proyek.
   - **Opsi B:** Akses skrip otomatis melalui browser pada alamat: `http://localhost/product-manager/schema.php`
4. Jalankan Aplikasi:
   - Buka tab browser baru dan ketikkan alamat utama aplikasi: `http://localhost/product-manager/`