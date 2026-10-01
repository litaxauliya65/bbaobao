
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

include "databse.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produk - Liya Chocolate House</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #F3E4C8;
            font-family: 'Jost', sans-serif;
            color: #2B1710;
        }

        .content {
            margin-left: 260px;
            padding: 25px;
            min-height: 100vh;
        }

        .top-title {
            background: #2B1710;
            color: #F3E4C8;
            padding: 25px;
            border-radius: 18px;
            margin-bottom: 25px;
        }

        .top-title h1 {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 700;
            margin: 0;
        }

        .top-title p {
            margin: 5px 0 0;
            color: #C9975B;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 700;
            color: #2B1710;
            margin-bottom: 20px;
        }

        .card-food {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            background: #fffaf0;
            box-shadow: 0 5px 15px rgba(43, 23, 16, 0.12);
            transition: 0.3s;
            height: 100%;
        }

        .card-food:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(43, 23, 16, 0.2);
        }

        .card-food-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .card-food .card-body {
            padding: 18px;
        }

        .card-food .card-title {
            color: #2B1710;
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
        }

        .desc-text {
            font-size: 14px;
            min-height: 45px;
        }

        .harga {
            color: #6B3E23;
            font-weight: 700;
            font-size: 18px;
        }

        .btn-coklat {
            background: #6B3E23;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 18px;
        }

        .btn-coklat:hover {
            background: #2B1710;
            color: white;
        }

        .kategori-menu {
            margin-bottom: 30px;
        }

        .kategori-menu .btn {
            border-radius: 20px;
            margin: 4px;
        }

        @media (max-width: 800px) {

            .content {
                margin-left: 0;
                padding: 15px;
            }

        }

    </style>
</head>

<body>

<?php
include "side_bar.php";
?>

<div class="content">

    <div class="top-title">

        <h1>Produk</h1>

        <p>
            Pilih produk coklat favoritmu 🍫
        </p>

    </div>

    <h2 class="section-title">
        Produk Kami 🍫
    </h2>

<div class="kategori-menu">

    <a href="produk.php" class="btn btn-coklat">
        Semua
    </a>

    <?php
    $kategori = mysqli_query($koneksi, "SELECT * FROM tb_kategori");

    while ($kat = mysqli_fetch_assoc($kategori)) {
    ?>

        <a
            href="produk.php?id_kategori=<?= $kat['id_kategori']; ?>"
            class="btn btn-outline-dark"
        >
            <?= $kat['nama_kategori']; ?>
        </a>

    <?php
    }
    ?>

</div>

    <div class="row g-4">

        <?php

        if (isset($_GET['id_kategori'])) {

    $id_kategori = $_GET['id_kategori'];

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk 
         WHERE id_kategori = '$id_kategori'"
    );

} else {

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM tb_produk"
    );

}

        while ($data = mysqli_fetch_assoc($query)) {

        ?>

        <div class="col-lg-4 col-md-6">

            <div class="card card-food">

                <img
                    src="img/<?= $data['poto']; ?>"
                    class="card-food-img"
                    alt="<?= $data['nama']; ?>"
                >

                <div class="card-body">

                    <h5 class="card-title fw-bold">
                        <?= $data['nama']; ?>
                    </h5>

                    <p class="card-text text-muted desc-text">
                        <?= $data['deskripsi']; ?>
                    </p>

                    <p class="harga">
                        Rp<?= number_format($data['harga'], 0, ',', '.'); ?>
                    </p>

                    <a
                        href="keranjang_aksi.php?id=<?= $data['id_produk']; ?>"
                        class="btn btn-coklat"
                    >
                        Tambah
                    </a>

                </div>

            </div>

        </div>

        <?php
        }
        ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>