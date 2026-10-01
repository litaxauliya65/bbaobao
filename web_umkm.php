<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "databse.php";

$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$username_safe = mysqli_real_escape_string($koneksi, $username);

$query_user = mysqli_query(
    $koneksi,
    "SELECT * FROM user WHERE username='$username_safe'"
);

$user = mysqli_fetch_assoc($query_user);
if (!empty($user['foto_profil'])) {
    $foto = $user['foto_profil'];
} else {
    $foto = "default.jpg";
}

$nama_user = isset($user['nama']) ? $user['nama'] : $username;
$role_user = isset($user['role']) ? $user['role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest');
$halaman = isset($halaman) ? $halaman : basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liya Chocolate House</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Jost', sans-serif;
            scroll-behavior: smooth;
        }

        h1, h2, h3, .logo {
            font-family: 'Cormorant Garamond', serif;
        }

        :root {
            --coklat-tua: #2B1710;
            --coklat-sedang: #6B3E23;
            --karamel: #C9975B;
            --krem: #fdf7ee;
            --coklat-pahit: #4A2410;
        }

        body {
            background:
                radial-gradient(ellipse at top left, rgba(201,151,91,.18), transparent 55%),
                radial-gradient(ellipse at bottom right, rgba(74,36,16,.35), transparent 55%),
                var(--coklat-tua);
            background-attachment: fixed;
            background-image: url('bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

      nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 60px;
    background: rgba(81, 55, 45, 0.95);
    backdrop-filter: blur(12px);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 100;
    /* border-bottom dihapus agar digantikan oleh bentuk lelehan */
}

/* Style untuk SVG Lelehan Navbar */
.nav-drip {
    position: absolute;
    top: 100%; /* Menempel pas di batas bawah navbar */
    left: 0;
    width: 100%;
    height: 35px; /* Sesuaikan tinggi lelehan */
    pointer-events: none; /* Agar tidak menghalangi klik di area bawah navbar */
}

.nav-drip path {
    fill: rgba(81, 55, 45, 0.95); /* Warna harus persis sama dengan background navbar */
}
        .logo {
            color: var(--krem);
            font-size: 26px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        nav ul {
            display: flex;
            list-style: none;
            align-items: center;
        }

        nav ul li {
            margin-left: 36px;
        }

        nav ul li a {
            color: var(--krem);
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: .3s;
        }

        nav ul li a:hover {
            color: var(--karamel);
        }

        .kata {
            height: 120vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            text-align: right;
            color: var(--krem);
            padding: 20px 80px;
            position: relative;
            background-image:
                linear-gradient(90deg, rgba(43,23,16,0) 0%, rgba(43,23,16,.35) 45%, rgba(43,23,16,.92) 78%),
                url('hero-coklat.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .kata span {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--karamel);
            margin-bottom: 18px;
        }

        .kata h1 {
            font-size: 68px;
            font-weight: 600;
            margin-bottom: 22px;
            line-height: 1.15;
        }

        .kata p {
            max-width: 520px;
            width: 90%;
            font-size: 18px;
            font-weight: 300;
            line-height: 1.9;
            margin-bottom: 38px;
            margin-left: auto;
            color: rgba(243,228,200,.87);
        }

        .kata a {
            text-decoration: none;
            background: transparent;
            border: 1px solid var(--karamel);
            color: var(--krem);
            padding: 14px 42px;
            font-size: 14px;
            letter-spacing: 2px;
            text-transform: uppercase;
            transition: .35s;
        }

        .kata a:hover {
            background: var(--karamel);
            color: var(--coklat-tua);
        }

        .drip {
            display: block;
            width: 100%;
            height: 90px;
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .drip path {
            fill: var(--krem);
        }

        /* ABOUT & CAROUSEL SLIDER SECTION */
        .about-promo-wrapper {
            display: flex;
            align-items: center;
            gap: 40px;
            padding: 70px 60px;
            background: linear-gradient(120deg, var(--coklat-pahit), var(--coklat-sedang));
            border-bottom: 1px solid rgba(201,151,91,.4);
        }

        .about-us-box {
            flex: 1;
            color: var(--krem);
        }

        .about-us-box span {
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--karamel);
            display: block;
            margin-bottom: 10px;
        }

        .about-us-box h2 {
            font-size: 42px;
            margin-bottom: 18px;
            color: var(--krem);
            line-height: 1.2;
        }

        .about-us-box p {
            font-size: 16px;
            line-height: 1.8;
            font-weight: 300;
            color: rgba(243,228,200,.87);
        }

        /* Original Carousel Slider Design */
        .carousel-box {
            flex: 1.5;
            max-width: 500px;
            width: 100%;
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(201,151,91,.3);
        }

        .carousel-slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
            width: 100%;
        }

        .slide {
            min-width: 100%;
            position: relative;
        }

        .slide img {
            width: 100%;
            height: 340px;
            object-fit: cover;
            display: block;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(43, 23, 16, 0.7);
            color: var(--krem);
            border: 1px solid var(--karamel);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .carousel-btn:hover {
            background: var(--karamel);
            color: var(--coklat-tua);
        }

        .carousel-btn.prev { left: 15px; }
        .carousel-btn.next { right: 15px; }

        .carousel-dots {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .dot.active {
            background: var(--karamel);
            width: 26px;
            border-radius: 10px;
        }

        .stats {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 70px;
            background: var(--coklat-tua);
            color: var(--krem);
            padding: 50px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(201,151,91,.2);
        }

        .stat h2 {
            font-size: 40px;
            color: var(--karamel);
        }

        .stat p {
            margin-top: 8px;
            font-size: 13px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* PRODUK */
        .produk {
            padding: 110px 60px;
            text-align: center;
            background: var(--krem);
        }

        .produk > span {
            display: block;
            font-size: 13px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--coklat-sedang);
            margin-bottom: 14px;
        }

        .produk h2 {
            font-size: 42px;
            font-weight: 600;
            color: var(--coklat-tua);
            margin-bottom: 60px;
        }

        .produk-tabs {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 55px;
        }

        .tab-btn {
            font-family: 'Jost', sans-serif;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--coklat-sedang);
            background: transparent;
            border: 1px solid var(--karamel);
            padding: 11px 26px;
            border-radius: 30px;
            cursor: pointer;
            transition: .3s;
        }

        .tab-btn:hover {
            background: rgba(201,151,91,.16);
        }

        .tab-btn.active {
            background: var(--coklat-sedang);
            color: var(--krem);
            border-color: var(--coklat-sedang);
        }

        .produk-kategori {
            margin-bottom: 70px;
        }

        .kategori-title {
            font-family: 'Jost', sans-serif;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--coklat-sedang);
            margin-bottom: 28px;
            padding-bottom: 14px;
            border-bottom: 1px solid rgba(201,151,91,.4);
            display: inline-block;
        }

        .produk-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 28px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .produk-card {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            text-align: left;
            border: 1px solid rgba(107,62,35,.10);
            box-shadow: 0 5px 18px rgba(43,23,16,.12);
            transition: .35s;
        }

        .produk-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(43,23,16,.22);
        }

        .gambar-wrap {
            width: 100%;
            height: 270px;
            overflow: hidden;
            background: linear-gradient(135deg, #efe0c4, #e4cfa5);
        }

        .gambar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: .5s;
        }

        .produk-card:hover .gambar-wrap img {
            transform: scale(1.05);
        }

        .produk-card h3 {
            margin: 18px 20px 7px;
            color: var(--coklat-tua);
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 600;
            line-height: 1.2;
        }

        .produk-card p {
            margin: 0 20px 20px;
            color: var(--coklat-sedang);
            font-size: 15px;
            font-weight: 600;
        }

        /* FOOTER */
        footer {
            background: var(--coklat-tua);
            color: rgba(243,228,200,.8);
            text-align: center;
            padding: 32px;
            font-size: 13px;
        }

        /* RESPONSIVE */
        .nav-toggle {
            display: none;
        }

        @media(max-width:1000px) {
            .about-promo-wrapper {
                flex-direction: column;
                padding: 50px 30px;
            }

            .slide img {
                height: 260px;
            }

            .produk-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:900px) {
            nav {
                padding: 18px 24px;
            }

            .kata h1 {
                font-size: 48px;
            }

            .produk {
                padding: 80px 24px;
            }
        }

        @media(max-width:750px) {
            .produk-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }

            .gambar-wrap {
                height: 240px;
            }

            .kata h1 {
                font-size: 40px;
            }

            .kata {
                padding: 20px 36px;
            }
        }

        @media(max-width:700px) {
            nav {
                flex-wrap: wrap;
            }

            .nav-toggle {
                display: block;
                background: none;
                border: none;
                color: var(--krem);
                font-size: 24px;
                cursor: pointer;
            }

            nav ul {
                width: 100%;
                display: none;
                flex-direction: column;
                align-items: flex-start;
                padding-top: 15px;
            }

            nav ul.open {
                display: flex;
            }

            nav ul li {
                width: 100%;
                margin: 0;
            }

            nav ul li a {
                display: block;
                padding: 12px 0;
            }
        }

        @media(max-width:500px) {
            .kata h1 {
                font-size: 32px;
            }

            .kata p {
                font-size: 15px;
                max-width: 100%;
            }

            .kata {
                padding: 20px 22px;
            }

            .produk h2 {
                font-size: 32px;
            }

            .produk-container {
                grid-template-columns: 1fr;
                max-width: 330px;
            }

            .gambar-wrap {
                height: 280px;
            }
            }
           /* REVIEW CAROUSEL SECTION */
.review {
    padding: 90px 60px;
    background: var(--coklat-tua);
    text-align: center;
    color: var(--krem);
}

.review .heading {
    font-size: 42px;
    font-weight: 600;
    margin-bottom: 40px;
    color: var(--krem);
}

.review .heading span {
    color: var(--karamel);
}

.review-slider {
    max-width: 700px;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
    padding: 10px 0 50px;
}

.review-wrapper {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.review-card {
    min-width: 100%;
    box-sizing: border-box;
    padding: 35px 25px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(201, 151, 91, 0.25);
    border-radius: 16px;
    backdrop-filter: blur(5px);
}

.review-card .user-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--karamel);
    margin: 0 auto 15px;
}

.review-card p {
    font-size: 16px;
    line-height: 1.7;
    color: rgba(243, 228, 200, 0.9);
    font-style: italic;
    margin-bottom: 20px;
    font-weight: 300;
}

.review-card h3 {
    font-size: 22px;
    color: var(--krem);
    margin-bottom: 8px;
}

.review-card .star {
    color: var(--karamel);
    font-size: 15px;
}

/* Navigasi Tombol Carousel Review */
.review-btn {
    position: absolute;
    top: 45%;
    transform: translateY(-50%);
    background: rgba(43, 23, 16, 0.8);
    color: var(--krem);
    border: 1px solid var(--karamel);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
}

.review-btn:hover {
    background: var(--karamel);
    color: var(--coklat-tua);
}

.review-btn.prev-rev { left: 5px; }
.review-btn.next-rev { right: 5px; }

/* Dots Review */
.review-dots {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
}

.rev-dot {
    width: 10px;
    height: 10px;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    cursor: pointer;
    transition: 0.3s;
}

.rev-dot.active {
    background: var(--karamel);
    width: 26px;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .review { padding: 60px 20px; }
    .review-slider { max-width: 100%; }
}
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav>
        <div class="logo">
            🍫 Liya Chocolate House
        </div>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            ☰
        </button>

       <ul id="navMenu">
    <li><a href="#">Beranda</a></li>
     <li>
    <a href="keranjang.php" style="position:relative;">
        <i class="fas fa-shopping-cart"></i>
        <?php if (isset($jumlah_keranjang) && $jumlah_keranjang > 0): ?>
            <span style="
                position:absolute;
                top:-8px;
                right:-10px;
                background:var(--karamel);
                color:var(--coklat-tua);
                font-size:10px;
                font-weight:600;
                width:16px;
                height:16px;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;">
                <?= $jumlah_keranjang ?>
            </span>
        <?php endif; ?>
    </a>
</li>
    <li><a href="#">Kontak</a></li>
    <?php if ($username): ?>
        <li>
            <a href="edit_profil.php" id="userDropdownToggle" style="display:flex;align-items:center;gap:8px;">
                <img src="img/<?= htmlspecialchars($foto) ?>" alt="foto"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;">
                <?= htmlspecialchars($nama_user) ?>
            </a>
        </li>
        <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
        <li><a href="log.php">Login</a></li>
        <li><a href="regrestasi.php">Daftar</a></li>
    <?php endif; ?>
</ul>
        <svg class="nav-drip" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M0,0 L1200,0 L1200,20 C1150,20 1130,50 1100,50 C1070,50 1050,15 1000,15 C950,15 930,65 890,65 C850,65 830,10 780,10 C730,10 710,45 670,45 C630,45 610,5 560,5 C510,5 490,55 450,55 C410,55 390,20 340,20 C290,20 270,70 220,70 C170,70 150,15 100,15 C50,15 30,35 0,35 Z"></path>
    </svg>
    </nav>

    <!-- HERO SECTION -->
    <section class="kata">
        <span>Liya Chocolate House</span>
        <h1>Manjakan Lidah dengan<br>Kelezatan Coklat Premium</h1>
        <p>Coklat pilihan dari biji kakao terbaik, diolah dengan resep artisan dan dikirim segar untuk menghadirkan momen manis di setiap hari Anda.</p>
        <a href="#produk">Lihat Koleksi</a>

        
    </section>

    <!-- ABOUT US & CAROUSEL SLIDER SECTION -->
    <section class="about-promo-wrapper">
        <div class="about-us-box">
            <span>Tentang Kami</span>
            <h2>Liya Chocolate House</h2>
            <p>Kami menghadirkan seni pengolahan coklat buatan tangan berkualitas tinggi. Menggunakan biji kakao pilihan terbaik, kami meracik kebahagiaan manis di setiap gigitan untuk menyempurnakan momen istimewa Anda.</p>
        </div>

       <div class="carousel-box">
            <div class="carousel-slides" id="carouselSlides">
                <div class="slide"><img src="desain/1.jpg" alt="Promo 1"></div>
                 <div class="slide"><img src="desain/2.jpg" alt="Promo 1"></div>
                  <div class="slide"><img src="desain/3.jpg" alt="Promo 1"></div>
                   <div class="slide"><img src="desain/4.jpg" alt="Promo 1"></div>
            </div>

            <button class="carousel-btn prev" id="prevBtn">❮</button>
            <button class="carousel-btn next" id="nextBtn">❯</button>

            <div class="carousel-dots" id="dotsContainer"></div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="stats">
        <div class="stat">
            <h2>80+</h2>
            <p>Varian Coklat</p>
        </div>
        <div class="stat">
            <h2>800+</h2>
            <p>Pelanggan Puas</p>
        </div>
        <div class="stat">
            <h2>30+</h2>
            <p>Kota Terjangkau</p>
        </div>
        <div class="stat">
            <h2>4.9★</h2>
            <p>Rating Pelanggan</p>
        </div>
    </section>

    <section class="produk" id="produk">
        <span>Koleksi</span>
        <h2>Produk Terlaris</h2>
        <div class="produk-kategori" data-kategori="batangan">
            <h3 class="kategori-title">Coklat Batangan</h3>
            <div class="produk-container">
              <a href="detail.php?id=8" class="produk-link">
    <div class="produk-card">
        <div class="gambar-wrap">
            <img src="coklat/1.jpg" alt="Chocolate Ruby">
        </div>

        <h3>Chocolate Ruby</h3>
        <p>⭐⭐⭐⭐</p>
    </div>
</a>
                  <a href="detail.php?id=12" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap">
                        <img src="coklat/2.jpg" alt="Milk Chocolate Klasik">
                    </div>
                    <h3>Chocolate Matcha</h3>
                    <p>⭐⭐⭐⭐⭐</p>
                </div>
    </a>
                    
                  <a href="detail.php?id=26" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="coklat/3.jpg" alt="White Chocolate Vanilla"></div>
                    <h3>Chocolate Sea Salt</h3>
                    <p>⭐⭐⭐⭐⭐</p>
                </div>
    </a>

    
                  <a href="detail.php?id=11" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="coklat/5.jpg" alt="Dark Chocolate Sea Salt"></div>
                    <h3>Chocolate Almond</h3>
                    <p>⭐⭐⭐⭐</p>
                </div>
    </a>
            </div>
        </div>

        <div class="produk-kategori" data-kategori="minuman">
            <h3 class="kategori-title">Makanan</h3>
            <div class="produk-container">
                
                  <a href="detail.php?id=18" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="makanan/palmer.jpg" alt="Palmer"></div>
                    <h3>Palmer</h3>
                    <p>⭐⭐⭐⭐⭐</p>
                </div>
    </a>

     <a href="detail.php?id=15" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="makanan/Cinnamon Roll.jpg" alt="Cinnamon Roll"></div>
                    <h3>Cinnamon Roll</h3>
                    <p>⭐⭐⭐⭐</p>
                </div>
    </a>
     <a href="detail.php?id=17" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="makanan/Butter Croissant.jpg" alt="Butter Crosissant">
                </div>
                    <h3>Butter Crosissant</h3>
                    <p>⭐⭐⭐⭐⭐</p>
                </div>
    </a>
     <a href="detail.php?id=19" class="produk-link">
                <div class="produk-card">
                    <div class="gambar-wrap"><img src="makanan/pistacio_crosiant.jpg" alt="pistacio_crosiant"></div>
                    <h3>Pistachio Croissant</h3>
                    <p>⭐⭐⭐⭐⭐</p>
                </div>
    </a>
            </div>
        </div>

      <div class="produk-kategori" data-kategori="pralin"> 
    <h3 class="kategori-title">Minuman</h3> 

    <div class="produk-container"> 

        <a href="detail.php?id=20" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="minuman/Classic Chocolate Milkshake.jpg" alt="Classic Chocolate Milkshake">
                </div> 
                <h3>Classic Chocolate</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 

        <a href="detail.php?id=21" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="minuman/Cookies & Cream Chocolate.jpg" alt="Cookies & Cream Chocolate">
                </div> 
                <h3>Cookies & Cream</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 

        <a href="detail.php?id=22" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="minuman/Iced Chocolate Coffee.jpg" alt="Iced Chocolate Coffee">
                </div> 
                <h3>Iced Chocolate</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 
 <a href="detail.php?id=27" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="minum.jpg" alt="peanuts">
                </div> 
                <h3>Peanuts cream choco</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 
    </div> 
</div>


<div class="produk-kategori" data-kategori="hampers"> 
    <h3 class="kategori-title">Hampers</h3> 

    <div class="produk-container"> 

        <a href="detail.php?id=23" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="hempres/Chocolate Luxury Hampers.jpg" alt="Chocolate Luxury Hampers">
                </div> 
                <h3>Luxury Hampers</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 


        <a href="detail.php?id=24" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="hempres/Sweet Cookies Hampers.jpg" alt="Sweet Cookies Hampers">
                </div> 
                <h3>Sweet Cookies Hampers</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 

        <a href="detail.php?id=25" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="hempres/Chocolate Teddy Gift Basket.jpg" alt="Chocolate Teddy Gift Basket">
                </div> 
                <h3>Chocolate Teddy Gift</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 
         <a href="detail.php?id=28" class="produk-link"> 
            <div class="produk-card"> 
                <div class="gambar-wrap">
                    <img src="makan.jpg" alt="rose">
                </div> 
                <h3>Bouqet rose</h3> 
                <p>⭐⭐⭐⭐⭐</p> 
            </div> 
        </a> 

    </div> 
</div>
    </section>
    <!-- Font Awesome untuk bintang (pastikan ada di <head>) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- SECTION REVIEW CAROUSEL -->
<section class="review" id="review">
    <h2 class="heading">Customer <span>Review</span></h2>

    <div class="review-slider">
        <div class="review-wrapper" id="reviewWrapper">
            
            <!-- Slide 1 -->
            <div class="review-card">
                <img src="desain/ian.jpg" class="user-img" alt="John Doe">
                <p>"Rasa Chocolate Ruby-nya benar-benar unik dan lezat! Rasa manis dan asam buahnya sangat pas. Penjual nya juga tipe saya sekali."</p>
                <h3>Christian Yu</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="review-card">
                <img src="desain/Joss.jpg" class="user-img" alt="Sarah Wijaya">
                <p>"Choco Brownie Shake-nya adalah minuman cokelat terbaik yang pernah saya coba. Kental, legit, dan tidak bikin enek sama sekali!"</p>
                <h3>Joss Wayar</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="review-card">
                <img src="desain/mingi.jpg" class="user-img" alt="Budi Santoso">
                <p>"Hampers Deluxe Floral Basket sangat cocok untuk hadiah ulang tahun sahabat saya. Tampilannya estetik dan cokelatnya rasa premium!"</p>
                <h3>Mingi Khairul</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="review-card">
                <img src="desain/baskara.jpg" class="user-img" alt="Budi Santoso">
                <p>"Hampers Deluxe Floral Basket sangat cocok untuk hadiah ulang tahun sahabat saya. Tampilannya estetik dan cokelatnya rasa premium!"</p>
                <h3>San</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="review-card">
                <img src="desain/san.jpg" class="user-img" alt="Budi Santoso">
                <p>"Hampers Deluxe Floral Basket sangat cocok untuk hadiah ulang tahun sahabat saya. Tampilannya estetik dan cokelatnya rasa premium!"</p>
                <h3>Baskara</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

            <div class="review-card">
                <img src="desain/rami.jpg" class="user-img" alt="Budi Santoso">
                <p>"Hampers Deluxe Floral Basket sangat cocok untuk hadiah ulang tahun sahabat saya. Tampilannya estetik dan cokelatnya rasa premium!"</p>
                <h3>Rami</h3>
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
            </div>

        </div>

        <!-- Tombol Navigasi -->
        <button class="review-btn prev-rev" id="prevRev">❮</button>
        <button class="review-btn next-rev" id="nextRev">❯</button>

        <!-- Indikator Dots -->
        <div class="review-dots" id="revDotsContainer"></div>
    </div>
</section>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© 2026 Liya Chocolate House · Menghadirkan Kebahagiaan Manis ke Rumah Anda 🍫</p>
    </footer>

    <script>
        /* CAROUSEL SLIDER LOGIC */
        const slides = document.getElementById('carouselSlides');
        const slideItems = document.querySelectorAll('.slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.getElementById('dotsContainer');

        let currentIndex = 0;
        const totalSlides = slideItems.length;

        // Create dots dynamically
        slideItems.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = document.querySelectorAll('.dot');

        function updateCarousel() {
            slides.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
        }

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
        });

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateCarousel();
        });

        // Auto play carousel every 4 seconds
        setInterval(() => {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateCarousel();
        }, 4000);

        /* FILTER PRODUK */
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                const target = this.getAttribute('data-target');

                document.querySelectorAll('.produk-kategori').forEach(kategori => {
                    if (target === 'semua' || kategori.getAttribute('data-kategori') === target) {
                        kategori.style.display = 'block';
                    } else {
                        kategori.style.display = 'none';
                    }
                });
            });
        });

        /* MOBILE MENU LOGIC */
        const navToggle = document.getElementById('navToggle');
        const navMenu = document.getElementById('navMenu');

        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('open');
        });

        document.querySelectorAll('#navMenu a').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('open');
            });
        });

        /* REVIEW CAROUSEL LOGIC */
const revWrapper = document.getElementById('reviewWrapper');
const revCards = document.querySelectorAll('.review-card');
const prevRev = document.getElementById('prevRev');
const nextRev = document.getElementById('nextRev');
const revDotsContainer = document.getElementById('revDotsContainer');

let revIndex = 0;
const totalRev = revCards.length;

// Generate dots secara dinamis
revCards.forEach((_, index) => {
    const dot = document.createElement('div');
    dot.classList.add('rev-dot');
    if (index === 0) dot.classList.add('active');
    dot.addEventListener('click', () => goToRevSlide(index));
    revDotsContainer.appendChild(dot);
});

const revDots = document.querySelectorAll('.rev-dot');

function updateRevCarousel() {
    revWrapper.style.transform = `translateX(-${revIndex * 100}%)`;
    revDots.forEach((dot, index) => {
        dot.classList.toggle('active', index === revIndex);
    });
}

function goToRevSlide(index) {
    revIndex = index;
    updateRevCarousel();
}

nextRev.addEventListener('click', () => {
    revIndex = (revIndex + 1) % totalRev;
    updateRevCarousel();
});

prevRev.addEventListener('click', () => {
    revIndex = (revIndex - 1 + totalRev) % totalRev;
    updateRevCarousel();
});

// Auto slide setiap 5 detik
setInterval(() => {
    revIndex = (revIndex + 1) % totalRev;
    updateRevCarousel();
}, 5000);
    </script>
</body>
</html>