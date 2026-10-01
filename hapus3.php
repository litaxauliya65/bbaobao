<?php
include 'databse.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($koneksi, $id);

    $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id = '$id'");

    if ($hapus) {
        header("Location: cbp.php");
        exit; 
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: index.php");
    exit;
}
?><?php
include 'databse.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($koneksi, $id);
    mysqli_query($koneksi, "DELETE FROM tb_transaksi WHERE id_pelanggan = '$id'");

    // 2. Hapus data user
    $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id = '$id'");

    if ($hapus) {
        header("Location: cbp.php");
        exit; 
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: index.php");
    exit;
}
?>