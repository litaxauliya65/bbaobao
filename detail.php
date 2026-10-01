<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

include "databse.php";

$id = $_GET['id'];

$query = mysqli_query($koneksi, 
    "SELECT * FROM tb_produk WHERE id='$id'"
);

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Produk tidak ditemukan");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $data['nama']; ?> - Liya Chocolate House</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #F3E4C8;
            font-family: Arial, sans-serif;
        }

        .detail-container {
            padding: 50px;
        }

        .detail-box {
            background: white;
            padding: 30px;
            border-radius: 20px;
            max-width: 1000px;
            margin: auto;
        }

        .produk-img {
            width: 100%;
            max-height: 450px;
            object-fit: contain;
            border-radius: 15px;
        }

        .detail-box h1 {
            color: #2B1710;
            font-weight: bold;
        }

        .detail-box p {
            color: #4A2410;
        }

        .btn-keranjang {
            background: #6B3E23;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
        }

        .btn-keranjang:hover {
            background: #4A2410;
            color: white;
        }
    </style>
</head>

<body>

<div class="detail-container">

    <div class="detail-box">

        <div class="row align-items-center">

            <div class="col-md-6">

                <img src="img/<?= $data['poto']; ?>" 
                     class="produk-img" 
                     alt="<?= $data['nama']; ?>">

            </div>

            <div class="col-md-6">

                <h1><?= $data['nama']; ?></h1>

                <p>⭐⭐⭐⭐⭐</p>

                <p>
                    Rp <?= number_format($data['harga'], 0, ',', '.'); ?>
                </p>

                <p>
                    <?= $data['deskripsi']; ?>
                </p>

                <form action="keranjang.php?id=<?= $data['id']; ?>" method="POST">

                    <input type="hidden" 
                           name="hidden_nama" 
                           value="<?= $data['nama']; ?>">

                    <input type="hidden" 
                           name="hidden_harga" 
                           value="<?= $data['harga']; ?>">
                           
                    <input type="hidden" 
                           name="hidden_poto" 
                           value="<?= $data['poto']; ?>">

                    <div class="mb-3">

                        <label>Jumlah</label>

                        <input type="number" 
                               name="jumlah" 
                               value="1" 
                               min="1" 
                               class="form-control" 
                               style="width:100px;">

                    </div>

                    <button type="submit" 
                            name="add" 
                            class="btn-keranjang">
                        🛒 Tambah ke Keranjang
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>