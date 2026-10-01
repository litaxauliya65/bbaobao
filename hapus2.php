<?php
session_start();
include 'databse.php';

// Cek apakah parameter id ada di URL
if (isset($_GET['id'])) {
    $id_keranjang = $_GET['id'];

    // Query hapus data berdasarkan id_keranjang
    $hapus = mysqli_query($koneksi, "DELETE FROM keranjang WHERE id_keranjang = '$id_keranjang'");

    if ($hapus) {
        echo "<script>
                alert('Produk berhasil dihapus!');
                window.location='keranjang.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus produk: " . mysqli_error($koneksi) . "');
                window.location='keranjang.php';
              </script>";
    }
} else {
    // Jika tidak ada ID, kembalikan ke keranjang
    header("Location: keranjang.php");
    exit;
}
?>