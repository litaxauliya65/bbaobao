<?php 
session_start(); 
include "databse.php"; // Sesuaikan nama file koneksi Anda 
 
// 1. Cek Login 
if (!isset($_SESSION['username'])) { 
    header("Location: log.php"); 
    exit; 
} 
 
// 2. Cek Parameter ID Transaksi 
if (!isset($_GET['id'])) { 
    echo "ID Transaksi tidak ditemukan."; 
    exit; 
} 
 
$id_transaksi = $_GET['id']; 
 
// 3. Ambil Data Transaksi Utama & User 
$query_trx = mysqli_query($koneksi, " 
    SELECT t.*, u.username  
    FROM tb_transaksi t  
    JOIN user u ON t.id_pelanggan = u.id  
    WHERE t.id_transaksi = '$id_transaksi' 
") or die("Error Query Transaksi: " . mysqli_error($koneksi)); 
 
$data_trx = mysqli_fetch_assoc($query_trx); 
 
if (!$data_trx) { 
    echo "Transaksi tidak ditemukan."; 
    exit; 
} 
 
// 4. Ambil Detail Barang yang Dibeli 
$query_detail = mysqli_query($koneksi, " 
    SELECT d.*, p.nama, p.harga  
    FROM tb_detail d  
    JOIN tb_produk p ON d.id_produk = p.id  
    WHERE d.id_transaksi = '$id_transaksi' 
") or die("Error Query Detail: " . mysqli_error($koneksi)); 


// 5. Hitung Total Harga Sebelum Diskon
$total_awal = 0;

while ($item = mysqli_fetch_assoc($query_detail)) {
    $total_awal += $item['jumlah'] * $item['harga'];
}

// Kembalikan query detail ke awal agar bisa ditampilkan lagi
mysqli_data_seek($query_detail, 0);


// 6. Hitung Diskon
$diskon = $total_awal - $data_trx['total_harga'];

if ($diskon < 0) {
    $diskon = 0;
}
?> 
 
<!DOCTYPE html> 
<html lang="id"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Struk Pembelian #<?= $data_trx['id_transaksi']; ?></title> 
    <style> 
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            background-color: #f4f4f4; 
            padding: 20px; 
            color: #333; 
        } 

        .receipt { 
            max-width: 380px; 
            margin: 0 auto; 
            background: #fff; 
            padding: 20px; 
            border: 1px solid #ddd; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); 
        } 

        .header { 
            text-align: center; 
            border-bottom: 1px solid #ddd; 
            padding-bottom: 10px; 
            margin-bottom: 12px; 
        } 

        .header h3 { 
            margin: 0 0 5px 0; 
            font-size: 18px; 
        } 

        .header p { 
            margin: 0; 
            font-size: 13px; 
            color: #666; 
        } 

        .info { 
            font-size: 13px; 
            margin-bottom: 15px; 
        } 

        .info table { 
            width: 100%; 
        } 

        .info td { 
            padding: 3px 0; 
        } 

        .item-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 13px; 
        } 

        .item-table th { 
            border-bottom: 1px solid #333; 
            padding-bottom: 6px; 
            text-align: left; 
        } 

        .item-table td { 
            padding: 6px 0; 
            border-bottom: 1px solid #eee; 
        } 

        .item-table td.num { 
            text-align: right; 
        } 

        .total-section { 
            border-top: 1px solid #333; 
            margin-top: 12px; 
            padding-top: 10px; 
            font-weight: bold; 
            font-size: 14px; 
        } 

        .total-section table {
            width: 100%;
        }

        .total-section td {
            padding: 4px 0;
        }

        .diskon {
            color: #555;
        }

        .actions { 
            text-align: center; 
            margin-top: 20px; 
        } 

        .btn-print { 
            padding: 8px 16px; 
            background: #007bff; 
            color: #fff; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-size: 14px; 
        } 

        .btn-print:hover { 
            background: #0056b3; 
        } 
         
        /* Mode Cetak */ 
        @media print { 
            .actions { 
                display: none; 
            } 

            body { 
                background: none; 
                padding: 0; 
            } 

            .receipt { 
                box-shadow: none; 
                border: none; 
                width: 100%; 
                max-width: 100%; 
            } 
        } 
    </style> 
</head> 

<body> 
 
<div class="receipt"> 

    <div class="header"> 
        <h3>STRUK PEMBELIAN</h3> 
        <p>Toko Chocolate Saya</p> 
    </div> 
 
    <div class="info"> 
        <table> 
            <tr> 
                <td>No. TRX</td> 
                <td>: #TRX-<?= $data_trx['id_transaksi']; ?></td> 
            </tr> 

            <tr> 
                <td>Tanggal</td> 
                <td>: <?= date('d-m-Y', strtotime($data_trx['tanggal'])); ?></td> 
            </tr> 

         <tr>
    <td>Nama anda</td>
    <td>: <?= htmlspecialchars($data_trx['username']); ?></td>
</tr>

<tr>
    <td>Pembayaran</td>
    <td>: <?= htmlspecialchars($data_trx['metode_pembayaran']); ?></td>
</tr>
        </table> 
    </div> 
 
    <table class="item-table"> 
        <thead> 
            <tr> 
                <th>Item</th> 
                <th>Qty</th> 
                <th class="num">Subtotal</th> 
            </tr> 
        </thead> 

        <tbody> 

            <?php  
            while ($item = mysqli_fetch_assoc($query_detail)):  
                $subtotal = $item['jumlah'] * $item['harga']; 
            ?> 

                <tr> 
                    <td><?= htmlspecialchars($item['nama']); ?></td> 

                    <td>
                        <?= $item['jumlah']; ?> x 
                        <?= number_format($item['harga'], 0, ',', '.'); ?>
                    </td> 

                    <td class="num">
                        <?= number_format($subtotal, 0, ',', '.'); ?>
                    </td> 
                </tr> 

            <?php endwhile; ?> 

        </tbody> 
    </table> 
 
    <!-- TOTAL DAN DISKON -->
    <div class="total-section"> 

        <table> 

            <tr> 
                <td>SUBTOTAL</td> 

                <td style="text-align: right;">
                    Rp <?= number_format($total_awal, 0, ',', '.'); ?>
                </td> 
            </tr> 

            <tr class="diskon"> 
                <td>DISKON</td> 

                <td style="text-align: right;">
                    - Rp <?= number_format($diskon, 0, ',', '.'); ?>
                </td> 
            </tr> 

            <tr> 
                <td>TOTAL</td> 

                <td style="text-align: right;">
                    Rp <?= number_format($data_trx['total_harga'], 0, ',', '.'); ?>
                </td> 
            </tr> 

        </table> 

    </div> 
 
    <div class="header" style="border-bottom: none; border-top: 1px solid #ddd; margin-top: 15px; padding-top: 10px;"> 
        <p style="font-size: 12px;">Terima Kasih Telah Berbelanja!</p> 
    </div> 
 
    <div class="actions"> 
        <button onclick="window.print()" class="btn-print">
            Cetak/ Simpan
        </button> 
    </div> 

</div> 
 
</body> 
</html>