<?php

if (session_id() == '') {
    session_start();
}

include "databse.php";


// Cek login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}


$username = $_SESSION['username'];

$username = mysqli_real_escape_string($koneksi, $username);


// Ambil data user
$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM user WHERE username='$username'"
);

$user = mysqli_fetch_assoc($query_user);


// Kalau foto kosong
if (!empty($user['foto_profil'])) {
    $foto = $user['foto_profil'];
} else {
    $foto = "default.jpg";
}


// Menentukan menu aktif
$halaman = basename($_SERVER['PHP_SELF']);

?>

<style>

* {
    box-sizing: border-box;
}


/* SIDEBAR */

.sidebar {
    width: 260px;
    height: 100vh;

    position: fixed;
    left: 0;
    top: 0;

    background: #2B1710;

    padding: 20px 15px;

    color: #F3E4C8;

    overflow-y: auto;
}


/* LOGO */

.sidebar-logo {
    text-align: center;

    color: #C9975B;

    font-size: 22px;
    font-weight: bold;

    margin-bottom: 20px;
}


/* PROFIL */

.profile-box {
    background: #F3E4C8;

    border-radius: 15px;

    padding: 18px 10px;

    text-align: center;

    color: #2B1710;

    margin-bottom: 25px;
}


.profile-box img {
    width: 70px;
    height: 70px;

    border-radius: 50%;

    object-fit: cover;

    border: 3px solid #C9975B;
}


.profile-box h4 {
    margin: 8px 0 3px;

    font-size: 16px;
}


.profile-box p {
    margin: 0;

    font-size: 13px;

    color: #6B3E23;
}


/* EDIT PROFIL */

.edit-profile {
    display: inline-block;

    margin-top: 10px;

    padding: 7px 15px;

    border-radius: 20px;

    background: #C9975B;

    color: white;

    text-decoration: none;

    font-size: 12px;
}


.edit-profile:hover {
    background: #6B3E23;

    color: white;
}


/* JUDUL MENU */

.menu-title {
    font-size: 11px;

    color: #C9975B;

    margin: 18px 10px 8px;

    text-transform: uppercase;

    letter-spacing: 1px;
}


/* MENU */

.sidebar-menu {
    list-style: none;

    padding: 0;

    margin: 0;
}


.sidebar-menu li {
    margin-bottom: 5px;
}


.sidebar-menu a {
    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 15px;

    border-radius: 10px;

    color: #F3E4C8;

    text-decoration: none;

    transition: .2s;
}


.sidebar-menu a i {
    width: 20px;

    text-align: center;
}


/* MENU AKTIF */

.sidebar-menu a:hover,
.sidebar-menu a.active {
    background: #C9975B;

    color: #2B1710;
}


/* GARIS */

.sidebar-line {
    border: 0;

    border-top: 1px solid rgba(243,228,200,.2);

    margin: 20px 0;
}


/* LOGOUT */

.logout:hover {
    background: #6B3E23 !important;

    color: white !important;
}


@media(max-width:800px) {

    .sidebar {
        position: relative;

        width: 100%;

        height: auto;
    }

}

</style>


<div class="sidebar">


    <!-- LOGO -->

    <div class="sidebar-logo">
        🍫 Liya Chocolate
    </div>



    <!-- PROFIL -->

    <div class="profile-box">

        <img
            src="img/profil/<?php echo htmlspecialchars($foto); ?>"
            alt="Foto Profil"
        >


        <h4>
            <?php echo htmlspecialchars($user['nama']); ?>
        </h4>


        <p>
            <?php echo htmlspecialchars($user['role']); ?>
        </p>


        <a
            href="edit_profil.php"
            class="edit-profile"
        >
            <i class="fa-solid fa-pen"></i>
            Edit Profil
        </a>

    </div>



    <!-- MENU UTAMA -->

    <div class="menu-title">
        Menu Utama
    </div>


    <ul class="sidebar-menu">


        <li>

            <a
                href="dashboard.php"
                class="<?php echo ($halaman == 'dashboard.php') ? 'active' : ''; ?>"
            >

                <i class="fa-solid fa-house"></i>

                Dashboard

            </a>

        </li>


        <li>

            <a
                href="produk.php"
                class="<?php echo ($halaman == 'produk.php') ? 'active' : ''; ?>"
            >

                <i class="fa-solid fa-cookie-bite"></i>

                Produk

            </a>

        </li>


        <li>

            <a
                href="kategori.php"
                class="<?php echo ($halaman == 'kategori.php') ? 'active' : ''; ?>"
            >

                <i class="fa-solid fa-layer-group"></i>

                Kategori

            </a>

        </li>


        <li>

            <a
                href="transaksi.php"
                class="<?php echo ($halaman == 'transaksi.php') ? 'active' : ''; ?>"
            >

                <i class="fa-solid fa-cart-shopping"></i>

                Transaksi

            </a>

        </li>


        <li>

            <a
                href="pelanggan.php"
                class="<?php echo ($halaman == 'pelanggan.php') ? 'active' : ''; ?>"
            >

                <i class="fa-solid fa-users"></i>

                Pelanggan

            </a>

        </li>


    </ul>



    <hr class="sidebar-line">



    <!-- MENU LAINNYA -->

    <div class="menu-title">
        Lainnya
    </div>


    <ul class="sidebar-menu">


        <li>

            <a href="pengaturan.php">

                <i class="fa-solid fa-gear"></i>

                Pengaturan

            </a>

        </li>


        <li>

            <a
                href="logout.php"
                class="logout"
            >

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </a>

        </li>


    </ul>


</div>