<?php
require_once 'config/database.php';

try {
    $products = [
        ['Buku Tulis Kiky', 5000, 20],
        ['Pulpen Standard AE7', 3500, 50],
        ['Penggaris Besi 30cm', 4000, 15],
        ['Correction Tape Joyko', 8000, 25],
        ['Buku Gambar A4', 6500, 10]
    ];

    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
    
    foreach ($products as $p) {
        $stmt->execute($p);
    }

    echo "<div style='font-family: Arial; text-align: center; margin-top: 50px;'>";
    echo "<h2 style='color: green;'>Yeay! Data produk berhasil diisi otomatis, sayang! 🎉</h2>";
    echo "<a href='index.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Kembali ke Daftar Produk</a>";
    echo "</div>";

} catch (PDOException $e) {
    echo "Gagal mengisi data: " . $e->getMessage();
}
?>