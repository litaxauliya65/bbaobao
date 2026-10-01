<?php
session_start();
include "databse.php";

if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

$username = $_SESSION['username'];

// Ambil id pelanggan
$query_user = mysqli_query($koneksi, "
    SELECT * FROM user 
    WHERE username = '$username'
");

$data_user = mysqli_fetch_assoc($query_user);

if (!$data_user) {
    echo "User tidak ditemukan.";
    exit;
}

$id_user = $data_user['id'];

// Ambil semua pesanan pelanggan
$query_pesanan = mysqli_query($koneksi, "
    SELECT * FROM tb_transaksi
    WHERE id_pelanggan = '$id_user'
    ORDER BY tanggal DESC, id_transaksi DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesanan Saya - Liya Chocolate House</title>

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

        .judul {
            color: #2B1710;
            font-weight: bold;
            margin-bottom: 25px;
        }


        /* PESANAN */

        .card-pesanan {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .id-pesanan {
            font-weight: bold;
            color: #6B3E23;
        }

        .tanggal {
            color: #777;
            font-size: 14px;
        }

        .total {
            color: #6B3E23;
            font-weight: bold;
            font-size: 18px;
        }

        .btn-detail {
            display: inline-block;
            background: #6B3E23;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-detail:hover {
            background: #2B1710;
            color: white;
        }

        .kosong {
            background: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ddd;
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

    <a href="pesanan_saya.php" class="active">
        Pesanan Saya
    </a>

    <a href="log.php">
        Keluar
    </a>

</div>


<!-- CONTENT -->

<div class="content">

    <h2 class="judul">
        Pesanan Saya
    </h2>


    <?php if (mysqli_num_rows($query_pesanan) > 0): ?>


        <?php while ($pesanan = mysqli_fetch_assoc($query_pesanan)): ?>

            <div class="card-pesanan">

                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <div>

                        <div class="id-pesanan">
                            Pesanan #<?= $pesanan['id_transaksi']; ?>
                        </div>

                        <div class="tanggal">
                            <?= date('d-m-Y', strtotime($pesanan['tanggal'])); ?>
                        </div>

                    </div>


                    <div class="total">

                        Rp <?= number_format(
                            $pesanan['total_harga'],
                            0,
                            ',',
                            '.'
                        ); ?>

                    </div>

                </div>


                <hr>


                <p>
                    Jumlah Produk:
                    <b><?= $pesanan['jumlah']; ?> produk</b>
                </p>


                <a href="detail_pesanan.php?id=<?= $pesanan['id_transaksi']; ?>"
                   class="btn-detail">

                    Lihat Detail

                </a>

            </div>


        <?php endwhile; ?>


    <?php else: ?>


        <div class="kosong">

            <h5>Belum Ada Pesanan 🤎</h5>

            <p>
                Pesanan yang kamu lakukan akan muncul di sini.
            </p>

        </div>


    <?php endif; ?>


</div>


</body>
</html>