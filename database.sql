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

-- (Opsional) Masukkan data dummy awal untuk uji coba
INSERT INTO products (name, category, price, stock) VALUES
('Es Teh Manis Original', 'Minuman', 5000, 25),
('Es Teh Lemon', 'Minuman', 7000, 12),
('Es Teh Susu', 'Minuman', 8000, 3),
('French Fries', 'Snack', 12000, 8);