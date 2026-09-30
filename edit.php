<?php
require_once 'config/database.php';
require_once 'includes/csrf.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST['name']) ?? '';
    $category = trim($_POST['category']) ?? '';
    $price = trim($_POST['price']) ?? '';
    $stock = trim($_POST['stock']) ?? '';
    $description = trim($_POST['description']) ?? '';

    $stmt = $pdo->prepare("UPDATE products SET name = ?, category = ?, price = ?, stock = ?, description = ? WHERE id = ?");
    $stmt->execute([$name, $category, $price, $stock, $description, $id]);
    
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Produk</h1>
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token(); ?>">
            
            <label>Nama Produk:</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($product['name']); ?>" required><br><br>

            <label>Kategori:</label><br>
            <input type="text" name="category" value="<?= htmlspecialchars($product['category'] ?? ''); ?>" required><br><br>

            <label>Harga:</label><br>
            <input type="number" name="price" value="<?= htmlspecialchars($product['price']); ?>" required><br><br>

            <label>Stok:</label><br>
            <input type="number" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" required><br><br>

            <label>Deskripsi:</label><br>
            <textarea name="description"><?= htmlspecialchars($product['description']); ?></textarea><br><br>

            <button type="submit" class="btn">Update Produk</button>
            <a href="index.php">Kembali</a>
        </form>
    </div>
</body>
</html>