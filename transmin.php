<?php
session_start();
include "databse.php"; // Sesuaikan nama file koneksi Anda

// Cek status login admin
if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

// 1. Hitung Total Transaksi
$q_total_trx = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_transaksi");
$total_trx = mysqli_fetch_assoc($q_total_trx)['total'];

// 2. Hitung Total Pendapatan
$q_total_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_harga) AS total FROM tb_transaksi");
$total_pendapatan = mysqli_fetch_assoc($q_total_pendapatan)['total'] ?? 0;

// 3. Hitung Jumlah Produk
$q_total_produk = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_produk");
$total_produk = mysqli_fetch_assoc($q_total_produk)['total'];

// 4. Ambil 10 Transaksi Terakhir
$query_recent = mysqli_query($koneksi, "
    SELECT t.*, u.username 
    FROM tb_transaksi t 
    JOIN user u ON t.id_pelanggan = u.id 
    ORDER BY t.tanggal DESC 
    LIMIT 10
") or die("Error Query: " . mysqli_error($koneksi));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UMKM</title>
    <style>
        /* Menggunakan font standar (sans-serif) */
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Navbar Top */
        .navbar {
            background-color: #007bff;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            margin: 0;
            font-size: 20px;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            background-color: #dc3545;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Container Utama */
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Kartu Statistik */
        .cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .card {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card h4 {
            margin: 0 0 10px 0;
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
        }
        .card .number {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        /* Tabel Transaksi */
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .table-container h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 18px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            background-color: #f8f9fa;
            color: #555;
        }
        
        /* Tombol Cetak */
        .btn-cetak {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }
        .btn-cetak:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Navbar Header -->
    <div class="navbar">
        <h2>Dashboard Admin UMKM</h2>
        <div>
            <span style="margin-right: 15px;">Halo, <b><?= htmlspecialchars($_SESSION['username']); ?></b></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        
        <!-- Statistik Ringkas -->
        <div class="cards">
            <div class="card">
                <h4>Total Transaksi</h4>
                <div class="number"><?= number_format($total_trx, 0, ',', '.'); ?></div>
            </div>
            <div class="card">
                <h4>Total Pendapatan</h4>
                <div class="number">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
            </div>
            <div class="card">
                <h4>Jumlah Produk</h4>
                <div class="number"><?= number_format($total_produk, 0, ',', '.'); ?></div>
            </div>
        </div>

        <!-- Tabel Transaksi Terbaru -->
        <div class="table-container">
            <h3>Transaksi Terbaru</h3>
            <table>
                <thead>
                    <tr>
                        <th>No. TRX</th>
                        <th>Tanggal</th>
                        <th>Kasir / Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($query_recent) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($query_recent)): ?>
                            <tr>
                                <td>#TRX-<?= $row['id_transaksi']; ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])); ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <!-- Tombol Mengarah ke Halaman Cetak Struk -->
                                    <a href="cetak.php?id=<?= $row['id_transaksi']; ?>" target="_blank" class="btn-cetak">
                                        Cetak Struk
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888;">Belum ada data transaksi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>