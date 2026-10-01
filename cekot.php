<?php 
session_start(); 
include "databse.php"; // Sesuaikan nama file koneksi 
 
if (!isset($_SESSION['username'])) { 
    header("Location: log.php"); 
    exit; 
} 
 
// Cek jika aksi adalah 'beli'
if (isset($_POST["aksi"]) && $_POST["aksi"] == "beli") { 

    $metode_pembayaran = isset($_POST['metode_pembayaran']) ? $_POST['metode_pembayaran'] : '';
    // Cek metode pembayaran
    if (empty($metode_pembayaran)) {
        echo "<script>
            alert('Silakan pilih metode pembayaran!');
            window.location='keranjang.php';
        </script>";
        exit;
    }
 
    // 1. Cek apakah keranjang kosong 
    if (empty($_SESSION["cart"])) { 
        echo "<script>
            alert('Keranjang belanja kosong!');
            window.location='keranjang.php';
        </script>"; 
        exit; 
    } 
 
    $username = $_SESSION['username']; 
    $tanggal  = date("Y-m-d"); 
 
    $query_user = mysqli_query(
        $koneksi, 
        "SELECT id FROM user WHERE username = '$username'"
    ); 

    $data_user = mysqli_fetch_assoc($query_user); 
 
    if (!$data_user) { 
        echo "<script>
            alert('User tidak ditemukan!');
            window.location='log.php';
        </script>"; 
        exit; 
    } 
 
    $id_user = $data_user['id']; 
 
 
    // 2. Cek stok produk
    foreach ($_SESSION["cart"] as $item) { 

        $id_produk   = $item['id']; 
        $jumlah_beli = $item['jumlah']; 
 
        $cek_stok = mysqli_query(
            $koneksi, 
            "SELECT stok, nama FROM tb_produk WHERE id = '$id_produk'"
        ); 

        $data_produk = mysqli_fetch_assoc($cek_stok); 
 
        if ($data_produk['stok'] < $jumlah_beli) { 

            echo "<script> 
                alert('Stok produk " . $data_produk['nama'] . " tidak cukup! Sisa stok: " . $data_produk['stok'] . "'); 
                window.location='keranjang.php'; 
            </script>"; 

            exit; 
        } 
    } 
 
 
    // 3. Hitung Total Harga Belanja 
    $total = 0; 
 
    // Hitung jumlah semua produk yang dibeli 
    $jumlah = 0; 
 
    foreach ($_SESSION["cart"] as $value) { 

        $total += ($value["jumlah"] * $value["harga"]); 
 
        $jumlah += $value["jumlah"]; 
    } 
 
 
    // 4. Simpan ke Tabel Transaksi Utama 
    $query_transaksi = "INSERT INTO tb_transaksi  
                        (id_pelanggan, tanggal, total_harga, jumlah, metode_pembayaran)  
                        VALUES  
                        ('$id_user', '$tanggal', '$total', '$jumlah', '$metode_pembayaran')"; 
     
    if (mysqli_query($koneksi, $query_transaksi)) { 
         
        $id_transaksi = mysqli_insert_id($koneksi); 
 
 
        // 5. Simpan Detail Transaksi & KURANGI STOK PRODUK 
        foreach ($_SESSION["cart"] as $value) { 

            $id_produk   = $value['id']; 
            $jumlah_beli = $value['jumlah']; 
 
 
            // A. Simpan ke tb_detail 
            $sql_detail = "INSERT INTO tb_detail 
                           (id_transaksi, id_produk, jumlah)  
                           VALUES 
                           ('$id_transaksi', '$id_produk', '$jumlah_beli')"; 

            mysqli_query($koneksi, $sql_detail); 
 
 
            // B. Kurangi stok di tabel tb_produk 
            $sql_update_stok = "UPDATE tb_produk  
                                SET stok = stok - $jumlah_beli  
                                WHERE id = '$id_produk'"; 

            mysqli_query($koneksi, $sql_update_stok); 
        } 
 
 
        // 6. Bersihkan Keranjang di Database & Session 
        mysqli_query(
            $koneksi, 
            "DELETE FROM keranjang WHERE username = '$username'"
        ); 

        unset($_SESSION["cart"]); 
 
 
        // 7. Redirect ke Halaman Cetak / Struk 
        echo "<script> 
            alert('Transaksi berhasil!'); 
            window.location='cetak.php?id=" . $id_transaksi . "'; 
        </script>"; 

        exit; 
 
    } else { 

        echo "Gagal memproses transaksi: " . mysqli_error($koneksi); 

    } 
} 
?>