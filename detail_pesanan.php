<?php
session_start();
include "databse.php";

if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID transaksi tidak ditemukan.";
    exit;
}

$id_transaksi = $_GET['id'];
$username = $_SESSION['username'];

// Ambil ID user
$query_user = mysqli_query($koneksi, "
    SELECT id, nama FROM user
    WHERE username = '$username'
");

$data_user = mysqli_fetch_assoc($query_user);

if (!$data_user) {
    echo "User tidak ditemukan.";
    exit;
}

$id_user = $data_user['id'];

// Ambil transaksi milik user
$query_transaksi = mysqli_query($koneksi, "
    SELECT *
    FROM tb_transaksi
    WHERE id_transaksi = '$id_transaksi'
    AND id_pelanggan = '$id_user'
");

$data_transaksi = mysqli_fetch_assoc($query_transaksi);

if (!$data_transaksi) {
    echo "Pesanan tidak ditemukan.";
    exit;
}

// Ambil detail produk
$query_detail = mysqli_query($koneksi, "
    SELECT d.*, p.nama, p.harga, p.poto
    FROM tb_detail d
    JOIN tb_produk p ON d.id_produk = p.id
    WHERE d.id_transaksi = '$id_transaksi'
");

// Hitung subtotal sebelum diskon
$total_awal = 0;

while ($item = mysqli_fetch_assoc($query_detail)) {
    $total_awal += $item['jumlah'] * $item['harga'];
}
mysqli_data_seek($query_detail, 0);

// Hitung diskon
$diskon = $total_awal - $data_transaksi['total_harga'];

if ($diskon < 0) {
    $diskon = 0;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Pesanan - Liya Chocolate House</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fdf7ee;
            color: #2B1710;
            font-family: Arial, sans-serif;
        }


        /* SIDEBAR */

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #2B1710;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            margin-top: 0;
            margin-bottom: 30px;
            color: #C9975B;
            font-size: 22px;
        }

        .sidebar p {
            margin-bottom: 25px;
            font-size: 14px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #6B3E23;
        }

        .sidebar .active {
            background: #6B3E23;
        }


        /* CONTENT */

        .content {
            margin-left: 260px;
            padding: 20px;
        }


        /* DETAIL PESANAN */

        .card-detail {
            max-width: 900px;
            background: white;
            border-radius: 8px;
            padding: 25px;
            border: 1px solid #ddd;
        }

        .judul {
            color: #2B1710;
            font-weight: bold;
        }

        .info-pesanan {
            background: #fdf7ee;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .produk {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .produk img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .nama-produk {
            font-weight: bold;
            color: #6B3E23;
        }

        .harga {
            color: #777;
            font-size: 14px;
        }

        .jumlah {
            font-size: 14px;
        }

        .subtotal {
            font-weight: bold;
            color: #6B3E23;
        }

        .total-box {
            margin-top: 20px;
            padding: 20px;
            background: #fdf7ee;
            border-radius: 8px;
        }

        .diskon {
            color: green;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            color: #2B1710;
        }

        .btn-kembali {
            display: inline-block;
            background: #6B3E23;
            color: white;
            padding: 9px 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .btn-kembali:hover {
            background: #2B1710;
            color: white;
        }


        /* RESPONSIVE */

        @media(max-width: 800px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                padding: 15px;
            }

        }

    </style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <h2>Liya Chocolate House</h2>

    <p>
        Halo, <?= htmlspecialchars($data_user['nama']); ?>
    </p>

    <a href="edit_profil.php">
        Edit Profil
    </a>

    <a href="pesanan.php" class="active">
        Pesanan Saya
    </a>

    <a href="log.php">
        Keluar
    </a>

</div>


<!-- CONTENT -->

<div class="content">

    <a href="edit_profil.php" class="btn-kembali">
        ← Kembali
    </a>


    <div class="card-detail">

        <h2 class="judul">
            Detail Pesanan
        </h2>


        <div class="info-pesanan">

            <p class="mb-1">
                <b>ID Pesanan:</b>
                #<?= $data_transaksi['id_transaksi']; ?>
            </p>

            <p class="mb-0">
                <b>Tanggal:</b>
                <?= date('d-m-Y', strtotime($data_transaksi['tanggal'])); ?>
            </p>

        </div>


        <h5 class="judul">
            Produk yang Dibeli
        </h5>


        <?php while ($produk = mysqli_fetch_assoc($query_detail)): ?>

            <div class="produk">

                <img src="img/<?= $produk['poto']; ?>"
                     alt="<?= htmlspecialchars($produk['nama']); ?>">


                <div style="flex: 1;">

                    <div class="nama-produk">
                        <?= htmlspecialchars($produk['nama']); ?>
                    </div>

                    <div class="harga">
                        Rp <?= number_format(
                            $produk['harga'],
                            0,
                            ',',
                            '.'
                        ); ?>
                    </div>

                    <div class="jumlah">
                        Jumlah: <?= $produk['jumlah']; ?>
                    </div>

                </div>


                <div class="subtotal">

                    Rp <?= number_format(
                        $produk['harga'] * $produk['jumlah'],
                        0,
                        ',',
                        '.'
                    ); ?>

                </div>

            </div>

        <?php endwhile; ?>


        <!-- TOTAL -->

        <div class="total-box">

            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">

                <span>Subtotal</span>

                <span>
                    Rp <?= number_format(
                        $total_awal,
                        0,
                        ',',
                        '.'
                    ); ?>
                </span>

            </div>


            <div class="diskon"
                 style="display: flex; justify-content: space-between; margin-bottom: 10px;">

                <span>Diskon</span>

                <span>
                    - Rp <?= number_format(
                        $diskon,
                        0,
                        ',',
                        '.'
                    ); ?>
                </span>

            </div>


            <hr>


            <div class="total"
                 style="display: flex; justify-content: space-between;">

                <span>TOTAL</span>

                <span>
                    Rp <?= number_format(
                        $data_transaksi['total_harga'],
                        0,
                        ',',
                        '.'
                    ); ?>
                </span>

            </div>

        </div>

    </div>

</div>


</body>
</html>