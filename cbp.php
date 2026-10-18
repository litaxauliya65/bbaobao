<?php
session_start();
include "databse.php";

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: log.php");
    exit();
}

// Mengambil data pengguna
$query = mysqli_query($koneksi, "SELECT * FROM user ORDER BY username ASC");

if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard Admin</title>

    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="dashboard.css" rel="stylesheet">

    <style>
        body {
            background-color: #fff;
            font-size: 14px;
        }

        .navbar-brand {
            font-size: 16px;
            font-weight: 500;
        }

        main {
            min-width: 0;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-top: 15px;
            table-layout: auto;
            vertical-align: middle;
        }

        .table th {
            white-space: nowrap;
            font-weight: 600;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 12px 10px;
            vertical-align: middle;
        }

        .table td {
            overflow-wrap: anywhere;
        }

        .table td:first-child {
            width: 45px;
            white-space: nowrap;
        }

        .table td:last-child {
            min-width: 125px;
            white-space: nowrap;
        }

        .table td:last-child .btn {
            margin: 2px 0;
        }

        .judul-tabel {
            margin: 10px 0 15px;
            font-size: 28px;
            font-weight: 500;
        }

        .password-text {
            white-space: nowrap;
            letter-spacing: 2px;
            color: #555;
        }

        @media (max-width: 767px) {
            main {
                padding: 15px !important;
            }

            .judul-tabel {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-white" href="#">
        Liya Chocolate house
    </a>

    <button class="nav-link px-3 text-white d-md-none"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebarMenu"
            aria-label="Buka menu">
        ☰
    </button>
</header>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="sidebar border-end col-md-3 col-lg-2 p-0 bg-body-tertiary">
            <div class="offcanvas-md offcanvas-start bg-body-tertiary"
                 tabindex="-1"
                 id="sidebarMenu">

                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Menu Admin</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            data-bs-target="#sidebarMenu"
                            aria-label="Tutup">
                    </button>
                </div>

                <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active d-flex align-items-center gap-2" href="index.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="m8 3.293 6 6V15h-4V9H6v6H2V9.293l6-6z"/>
                                    <path d="m7.293 1.5-6 6 .707.707L8 2.207l6 6 .707-.707-6-6a1 1 0 0 0-1.414 0z"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" href="cbp.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M4 1h6l4 4v10H2V1h2zm0 1v12h9V5h-4V2H4zm6 0v2h2l-2-2z"/>
                                </svg>
                                Profil
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" href="transaksi.php">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2l.4 2H15a.5.5 0 0 1 .49.6l-1.5 6A.5.5 0 0 1 13.5 10H4a.5.5 0 0 1-.49-.4L1.6 2H.5a.5.5 0 0 1-.5-.5zM4.4 9h8.7l1.25-5H3.4l1 5zM5 12a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm7 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
                                </svg>
                                Transaksi
                            </a>
                        </li>
                    </ul>

                    <hr>

                    <ul class="nav flex-column mb-auto">
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center gap-2" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M6 2h5a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H6v-1h5V3H6V2z"/>
                                    <path d="M7.854 5.146a.5.5 0 0 1 0 .708L6.707 7H14v1H6.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2a.5.5 0 0 1 .708 0z"/>
                                    <path d="M2 1h2v14H2z"/>
                                </svg>
                                Sign out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <div class="container-fluid px-0">
                <h2 class="judul-tabel">Daftar Pelanggan</h2>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Nama Lengkap</th>
                                <th>Alamat</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;

                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td>
                                        <span class="password-text"><?php echo htmlspecialchars($row['pasword']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                                    <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                                    <td>
                                        <a href="reset_password.php?id=<?php echo (int)$row['id']; ?>"
                                           class="btn btn-sm btn-warning text-white"
                                           onclick="return confirm('Yakin ingin mereset password akun ini?')">
                                            Reset
                                        </a>

                                        <a href="hapus3.php?id=<?php echo (int)$row['id']; ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin ingin menghapus data pelanggan ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Belum ada data pelanggan.
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
</div>

<script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>