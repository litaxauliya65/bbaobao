<?php
session_start();
include "databse.php"; // Sesuaikan nama file koneksi database Anda

if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

if (isset($_POST['add'])) {

    $id_produk = $_GET['id'];
    $nama      = $_POST['hidden_nama'];
    $harga     = $_POST['hidden_harga'];
    $poto      = $_POST['hidden_poto'];
    $jumlah    = (int)$_POST['jumlah'];

    $username = $_SESSION['username'];

    if (isset($_SESSION["cart"][$id_produk])) {
        $_SESSION["cart"][$id_produk]['jumlah'] += $jumlah;
    } else {
        $_SESSION["cart"][$id_produk] = array(
            'id'     => $id_produk,
            'nama'   => $nama,
            'harga'  => $harga,
            'foto'   => $poto,
            'jumlah' => $jumlah
        );
    }
    $query = "INSERT INTO keranjang (username, id_produk, nama_produk, harga, jumlah, poto) 
              VALUES ('$username', '$id_produk', '$nama', '$harga', '$jumlah', '$poto')
              ON DUPLICATE KEY UPDATE jumlah = jumlah + $jumlah";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: keranjang.php?status=success");
        exit;
    } else {
        echo "Gagal menambahkan ke keranjang: " . mysqli_error($koneksi);
    }
}

if (isset($_GET["aksi"])) {
    if ($_GET["aksi"] == "hapus") {
        $id_produk = $_GET["id"];
        $username  = $_SESSION['username'];

        // Hapus dari Session
        if (isset($_SESSION["cart"][$id_produk])) {
            unset($_SESSION["cart"][$id_produk]);
        }

        // Hapus dari Database
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE username = '$username' AND id_produk = '$id_produk'");

        echo '<script>alert("Produk berhasil dihapus.")</script>';
        echo '<script>window.location="keranjang.php"</script>';
        exit;

    } elseif ($_GET["aksi"] == "beli") {

        if (empty($_SESSION["cart"])) {
            echo '<script>alert("Keranjang Anda kosong!"); window.location="keranjang.php";</script>';
            exit;
        }

        $total = 0;
        foreach ($_SESSION["cart"] as $value) {
            $total += ($value["jumlah"] * $value["harga"]);
        }

        $username = $_SESSION['username'];
        $tanggal  = date("Y-m-d");

        // Simpan transaksi utama
        $query = mysqli_query(
            $koneksi,
            "INSERT INTO tb_transaksi(tanggal, id_pelanggan, total_harga) 
             VALUES ('$tanggal', '$username', '$total')"
        ) or die("Error insert transaksi: " . mysqli_error($koneksi));

        $id_transaksi = mysqli_insert_id($koneksi);

        // Simpan detail produk
        foreach ($_SESSION["cart"] as $value) {
            $id_produk = $value['id'];
            $jumlah    = $value['jumlah'];
            $sql       = "INSERT INTO tb_detail(id_transaksi, id_produk, jumlah) 
                          VALUES ('$id_transaksi', '$id_produk', '$jumlah')";
            mysqli_query($koneksi, $sql);
        }

        // Bersihkan keranjang di database untuk user ini
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE username = '$username'");

        // Kosongkan session keranjang
        unset($_SESSION["cart"]);

        echo "<script>window.location='cetak.php?id=" . $id_transaksi . "'</script>";
        exit;
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <script src="assets/js/color-modes.js"></script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.111.3">
    <title>IBox Online Shopping</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/carousel/">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="carousel.css" rel="stylesheet">
    <link href="headers.css" rel="stylesheet">
    <link href="jumbotrons.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                </a>
            </div>

            <div class="col-md-3 text-end">
                <a href="edit_profil.php" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                    </svg>
                </a>
                <a href="keranjang.php" class="btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16"> 
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" /> 
                    </svg> 
                </a> 
 
            </div> 
        </header> 
    </div> 
 
    <main> 
 
        <div class="container marketing"> 
            <h3 align="center"> Keranjangmu</h3> 
            <br> 
            <?php if (!empty($_SESSION["cart"])) { ?> 
                <table class="table"> 
                    <thead> 
                        <tr class="text-center align-middle"> 
                            <th scope="col">Produk</th> 
                            <th scope="col">Jumlah</th> 
                            <th scope="col">Harga</th> 
                            <th scope="col">Total Harga</th> 
                            <th scope="col">Aksi</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php 
                        $total = 0; 
                        foreach ($_SESSION["cart"] as $key => $value) { 
                        ?> 
                            <tr> 
                                <td> 
                                    <img src="img/<?= $value["foto"] ?>" height="100px"><?= $value["nama"] ?> 
                                </td> 
                                <td class="text-center align-middle"><?= $value["jumlah"] ?></td> 
                                <td class="text-center align-middle">Rp <?php echo number_format($value["harga"]) ?></td> 
                                <td class="text-center align-middle"> 
                                    Rp <?php echo number_format($value["jumlah"] * $value["harga"], 2); ?></td> 
                                <td class="text-center align-middle"> 
                                    <a href="keranjang.php?aksi=hapus&id=<?= $value["id"]; ?>"><span class="btn btn-danger">Hapus</span></a> 
                                </td> 
                            </tr> 
                        <?php 
                            $total = $total + ($value["jumlah"] * $value["harga"]); 
                        } 
                        ?> 
                        <tr> 
                            <td colspan="3" class="text-end align-middle">Grand Total</td> 
                            <th class="text-center align-middle">Rp <?php echo number_format($total, 2); ?></th> 
                            <td class="text-center align-middle"> 
                              <td class="text-center align-middle">

    <form action="cekot.php" method="POST">

        <input type="hidden" name="aksi" value="beli">

        <select name="metode_pembayaran" class="form-select mb-2" required>
            <option value="">Pilih Pembayaran</option>
            <option value="COD">COD</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="QRIS">QRIS</option>
        </select>

        <button type="submit" class="btn btn-outline-dark">
            Beli
        </button>

    </form>

</td>
                        </tr> 
                    </tbody> 
                </table> 
            <?php } else { ?> 
                <p align="center"> Belum Ada Produk di Keranjang </p> 
                <hr> 
            <?php } ?> 
 
        </div> 
 
 
        <!-- FOOTER --> 
        <footer class=" container"> 
            <p class="float-end"><a href="web_umkm.php">Back to top</a></p> 
            <p>&copy; 2017–2023 Company, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p> 
        </footer> 
    </main> 
 
 
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script> 
 
 
</body> 
 
</html>\