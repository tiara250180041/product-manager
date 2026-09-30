<?php
// delete.php - Proses Hapus Produk
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF Token
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postToken = $_POST['csrf_token'] ?? '';

    if (empty($postToken) || $postToken !== $sessionToken) {
        die('Validasi Keamanan Gagal (CSRF Token tidak valid).');
    }

    $id = $_POST['id'] ?? null;

    if (!empty($id)) {
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            
            // Redirect kembali ke index setelah sukses menghapus
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            die('Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}

// Jika diakses secara langsung tanpa POST, kembalikan ke index
header('Location: index.php');
exit;