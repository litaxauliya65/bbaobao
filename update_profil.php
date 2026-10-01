<?php

if (session_id() == '') {
    session_start();
}

include "databse.php";


/* CEK LOGIN */
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username_lama = $_SESSION['username'];

$nama = $_POST['nama'];
$username = $_POST['username'];
$alamat = $_POST['alamat'];
$no_hp = $_POST['no_hp'];
$password = $_POST['password'];


/* AMANKAN DATA */

$nama = mysqli_real_escape_string($koneksi, $nama);
$username = mysqli_real_escape_string($koneksi, $username);
$alamat = mysqli_real_escape_string($koneksi, $alamat);
$no_hp = mysqli_real_escape_string($koneksi, $no_hp);
$username_lama = mysqli_real_escape_string($koneksi, $username_lama);

$foto_baru = "";


/* CEK APAKAH ADA FOTO */

if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['name'] != "") {

    if ($_FILES['foto_profil']['error'] != 0) {
        echo "Upload foto gagal. Kode error: " . $_FILES['foto_profil']['error'];
        exit;
    }

    $nama_file = $_FILES['foto_profil']['name'];
    $tmp_file = $_FILES['foto_profil']['tmp_name'];

    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);

    $foto_baru = time() . "." . $ekstensi;

    $folder = "img/";

    if (!is_dir($folder)) {
        echo "Folder img tidak ditemukan!";
        exit;
    }

    if (!move_uploaded_file($tmp_file, $folder . $foto_baru)) {
        echo "Foto gagal dipindahkan ke folder img!";
        exit;
    }

}



/* JIKA PASSWORD DIISI */

if ($password != "") {

    $password = mysqli_real_escape_string(
        $koneksi,
        $password
    );


    /* PASSWORD + FOTO */

    if ($foto_baru != "") {

        $query = mysqli_query(
            $koneksi,

            "UPDATE user SET
            nama='$nama',
            username='$username',
            alamat='$alamat',
            no_hp='$no_hp',
            password='$password',
            foto_profil='$foto_baru'
            WHERE username='$username_lama'"
        );

    }


    /* PASSWORD TANPA FOTO */

    else {

        $query = mysqli_query(
            $koneksi,

            "UPDATE user SET
            nama='$nama',
            username='$username',
            alamat='$alamat',
            no_hp='$no_hp',
            password='$password'
            WHERE username='$username_lama'"
        );

    }

}


/* ============================
   JIKA PASSWORD TIDAK DIISI
============================ */

else {


    /* FOTO ADA */

    if ($foto_baru != "") {

        $query = mysqli_query(
            $koneksi,

            "UPDATE user SET
            nama='$nama',
            username='$username',
            alamat='$alamat',
            no_hp='$no_hp',
            foto_profil='$foto_baru'
            WHERE username='$username_lama'"
        );

    }


    /* FOTO TIDAK ADA */

    else {

        $query = mysqli_query(
            $koneksi,

            "UPDATE user SET
            nama='$nama',
            username='$username',
            alamat='$alamat',
            no_hp='$no_hp'
            WHERE username='$username_lama'"
        );

    }

}


/* ============================
   HASIL UPDATE
============================ */

if ($query) {


    /* UPDATE SESSION */

    $_SESSION['username'] = $username;


    echo "

    <script>

        alert('Profil berhasil diperbarui!');

        window.location='web_umkm.php';

    </script>

    ";

}


else {


    echo "

    <script>

        alert('Profil gagal diperbarui!');

        window.location='edit_profil.php';

    </script>

    ";

}

?>