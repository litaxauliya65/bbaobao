<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'penjual') {
    header("Location: index.php#login-modal");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard Penjual</title></head>
<body style="font-family: sans-serif; padding: 40px;">
    <h1>Halaman Penjual / Mitra UMKM 🏪</h1>
    <p>Selamat datang, <b><?= $_SESSION['nama']; ?></b> (<?= $_SESSION['email']; ?>)</p>
    <a href="logout.php">Logout / Keluar</a>
</body>
</html>