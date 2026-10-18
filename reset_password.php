<?php
session_start();
include "databse.php";

// Pastikan yang mengakses adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: log.php");
    exit();
}

// Cek apakah parameter id tersedia di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mengosongkan password di database (menjadikannya NULL atau string kosong '')
    // Di sini kita set menjadi NULL agar status passwordnya kosong/belum diatur
    $stmt = mysqli_prepare($koneksi, "UPDATE user SET pasword = NULL WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect kembali ke index dengan status sukses
        header("Location: cbp.php?pesan=reset_sukses");
        exit();
    } else {
        echo "Gagal mengosongkan password: " . mysqli_error($koneksi);
    }
} else {
    header("Location: index.php");
    exit();
}
?>