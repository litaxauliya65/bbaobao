<?php
$host     = "localhost";
$user     = "root";
$password = "";          // Sesuaikan jika MySQL Anda menggunakan password
$database = "coklat";

$koneksi = mysqli_connect($host, $user, $password, $database);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>