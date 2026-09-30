<?php
// create.php - Tambah Produk
session_start();
require_once 'config/database.php';

// Generate CSRF token jika belum ada
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF Token dengan aman
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postToken = $_POST['csrf_token'] ?? '';

    if (empty($postToken) || $postToken !== $sessionToken) {
        $error = 'Validasi Keamanan Gagal (CSRF Token tidak valid).';
    } else {
        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = trim($_POST['price'] ?? '');
        $stock = trim($_POST['stock'] ?? '');

        if (empty($name) || $price === '' || $stock === '') {
            $error = 'Nama produk, harga, dan stok wajib diisi!';
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO products (name, category, price, stock) VALUES (?, ?, ?, ?)");
                $stmt->execute([$name, $category, $price, $stock]);
                
                header('Location: index.php');
                exit;
            } catch (PDOException $e) {
                $error = 'Gagal menyimpan ke database: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Product Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #090d16;
            --bg-surface: #111827;
            --border-color: #374151;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --primary: #6366f1;
        }
        * { box-sizing: border-box; }
        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 40px 24px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            width: 100%;
            max-width: 600px;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .form-header h1 {
            margin: 0 0 6px 0;
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(to right, #ffffff, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .form-header p {
            margin: 0 0 24px 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        .alert-error {
            background-color: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px 16px;
            background-color: #1f2937;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-main);
            font-size: 14px;
        }
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        .btn-submit {
            flex: 1;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
        }
        .btn-back {
            flex: 1;
            background-color: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Tambah Produk Baru</h1>
            <p>Masukkan detail informasi produk ke dalam sistem inventaris</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form action="create.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <div class="form-group">
                <label for="name">Nama Produk</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Buku Tulis Kiky">
            </div>

            <div class="form-group">
                <label for="category">Kategori</label>
                <input type="text" id="category" name="category" placeholder="Contoh: Alat Tulis">
            </div>

            <div class="form-group">
                <label for="price">Harga Satuan (Rp)</label>
                <input type="number" id="price" name="price" required min="0" placeholder="Contoh: 5000">
            </div>

            <div class="form-group">
                <label for="stock">Jumlah Stok</label>
                <input type="number" id="stock" name="stock" required min="0" placeholder="Contoh: 20">
            </div>

            <div class="btn-group">
                <a href="index.php" class="btn-back">Kembali</a>
                <button type="submit" class="btn-submit">Simpan Produk</button>
            </div>
        </form>
    </div>
</body>
</html>