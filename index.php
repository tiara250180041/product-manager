<?php
// index.php - Product Manager
session_start();
require_once 'config/database.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch products from database (Urut normal dari ID terkecil ke terbesar)
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}

// Calculate stats
$totalProduk = count($products);
$totalNilaiAset = array_sum(array_map(function($p) {
    return $p['price'] * $p['stock'];
}, $products));
$stokKritis = count(array_filter($products, function($p) {
    return $p['stock'] < 5;
}));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Manager - Dashboard Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #090d16;
            --bg-surface: #111827;
            --border-color: #374151;
            --text-main: #f9fafb;
            --text-muted: #9ca3af;
            --primary: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.15);
        }

        * { box-sizing: border-box; }

        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 40px 24px;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
        }

        .header-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #111827 100%);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 16px;
            padding: 30px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .header-title h1 {
            margin: 0 0 6px 0;
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, #ffffff, #c7d2fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-title p {
            margin: 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .btn-add {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4);
            transition: all 0.25s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 28px;
        }

        .stat-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .stat-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin: 0;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--accent-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #818cf8;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }

        .table-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background-color: #161e2e;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 16px 20px;
            font-size: 14px;
            border-bottom: 1px solid rgba(55, 65, 81, 0.5);
            color: #e5e7eb;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: rgba(255, 255, 255, 0.015); }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-safe {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-crit {
            background-color: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .action-group {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
        }

        .btn-edit {
            background-color: rgba(56, 189, 248, 0.1);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.2);
        }
        .btn-edit:hover { background-color: rgba(56, 189, 248, 0.2); }

        .btn-delete {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .btn-delete:hover { background-color: rgba(239, 68, 68, 0.2); }

        @media (max-width: 900px) {
            .stats-grid { grid-template-columns: 1fr; }
            .header-card { flex-direction: column; align-items: flex-start; gap: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header-card">
            <div class="header-title">
                <h1>Product Manager</h1>
                <p>Sistem Inventaris dan Manajemen Stok Produk Berbasis Web Modern</p>
            </div>
            <a href="create.php" class="btn-add">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                Tambah Produk
            </a>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Total Jenis Produk</h3>
                    <div class="stat-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                </div>
                <p class="stat-value"><?php echo $totalProduk; ?> <span style="font-size: 16px; font-weight: 500; color: var(--text-muted);">Item</span></p>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Total Nilai Aset</h3>
                    <div class="stat-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <p class="stat-value" style="font-size: 22px;">Rp <?php echo number_format($totalNilaiAset, 0, ',', '.'); ?></p>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <h3 class="stat-title">Stok Kritis (&lt; 5)</h3>
                    <div class="stat-icon" style="background: <?php echo $stokKritis > 0 ? 'rgba(239, 68, 68, 0.15)' : 'var(--accent-glow)'; ?>; color: <?php echo $stokKritis > 0 ? '#f87171' : '#818cf8'; ?>;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <p class="stat-value" style="color: <?php echo $stokKritis > 0 ? '#f87171' : '#34d399'; ?>;"><?php echo $stokKritis; ?> <span style="font-size: 16px; font-weight: 500; color: var(--text-muted);">Item</span></p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Produk</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Satuan</th>
                            <th>Stok</th>
                            <th>Status Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">Belum ada data produk tersedia.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($products as $row): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><code style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; font-size: 13px; color: #a5b4fc;">PRD-<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></code></td>
                                <td><strong><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['category'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                                <td><strong><?php echo $row['stock']; ?></strong></td>
                                <td>
                                    <?php if ($row['stock'] < 5): ?>
                                        <span class="badge badge-crit">⚠️ Kritis</span>
                                    <?php else: ?>
                                        <span class="badge badge-safe">✓ Aman</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-group">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit">Edit</a>
                                        <form action="delete.php" method="POST" style="margin:0;" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="btn-action btn-delete" style="border: 1px solid rgba(239, 68, 68, 0.2); cursor:pointer;">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>