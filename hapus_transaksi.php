<?php
session_start();
include "databse.php";

// Pastikan yang mengakses adalah admin (sudah login)
if (!isset($_SESSION['username'])) {
    header("Location: log.php");
    exit;
}

// Cek apakah parameter id tersedia di URL
if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Query untuk menghapus data transaksi berdasarkan id_transaksi
    $stmt = mysqli_prepare($koneksi, "DELETE FROM tb_transaksi WHERE id_transaksi = ?");
    mysqli_stmt_bind_param($stmt, "s", $id_transaksi); // Sesuaikan tipe data "s" jika id_transaksi berupa angka (gunakan "i")

    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil terhapus, kembalikan ke halaman dashboard/index
        header("Location: transaksi.php?pesan=hapus_sukses");
        exit();
    } else {
        echo "Gagal menghapus transaksi: " . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada ID, kembalikan langsung ke halaman utama
    header("Location: index.php");
    exit();
}
?>