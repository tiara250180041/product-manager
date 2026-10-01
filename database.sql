-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS product_manager_db;

-- Gunakan database
USE product_manager_db;

-- Buat tabel products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Masukkan data khusus perlengkapan alat belajar
INSERT INTO products (name, category, price, stock) VALUES
('Buku Tulis Kiky', 'Alat Tulis', 5000, 20),
('Pulpen Standard AE7', 'Alat Tulis', 3500, 50),
('Penggaris Besi 30cm', 'Alat Ukur', 4000, 15),
('Correction Tape Joyko', 'Peralatan', 8000, 25),
('Buku Gambar A4', 'Buku', 6500, 10),
('Pensil 2B Faber Castell', 'Alat Tulis', 4500, 30),
('Penghapus Karet Joyko', 'Peralatan', 2000, 40),
('Spidol Warna Joyko', 'Alat Tulis', 15000, 12);