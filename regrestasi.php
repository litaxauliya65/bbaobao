<?php
include 'databse.php';

$pesan = "";

if (isset($_POST['regrestasi'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat   = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_hp    = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $pasword  = mysqli_real_escape_string($koneksi, $_POST['pasword']); 
    
    $role     = mysqli_real_escape_string($koneksi, $_POST['role']);

    $cek_username = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username'");
    
    if (mysqli_num_rows($cek_username) > 0) {
        $pesan = "<p style='color: red; margin-bottom: 15px;'>Username sudah digunakan, silakan cari username lain!</p>";
    } else {
        $query = "INSERT INTO user (nama, alamat, no_hp, username, pasword, role) 
                  VALUES ('$nama', '$alamat', '$no_hp', '$username', '$pasword', '$role')";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                    alert('Akun berhasil dibuat! Silakan Login.');
                    window.location = 'log.php';
                  </script>";
            exit();
        } else {
            $pesan = "<p style='color: red; margin-bottom: 15px;'>Gagal membuat akun: " . mysqli_error($koneksi) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun Baru</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: rgb(72, 35, 35);
            padding: 20px;
        }

        .container {
            display: flex;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 850px;
            max-width: 100%;
            min-height: 500px;
        }

        /* Bagian Kiri (Form Registrasi) */
        .login {
            flex: 1;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login form {
            display: flex;
            flex-direction: column;
        }

        .login h1 {
            text-transform: capitalize;
            color: #333;
            margin-bottom: 4px;
            font-size: 24px;
        }

        .login hr {
            border: none;
            height: 2px;
            background-color: rgb(72, 35, 35);
            margin-bottom: 12px;
        }

        .login p.sub-title {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login label {
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        /* Styling input, textarea, & select agar seragam */
        .login input, 
        .login textarea, 
        .login select {
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            font-size: 14px;
            width: 100%;
            background-color: #fff;
        }

        .login textarea {
            resize: vertical;
            min-height: 60px;
        }

        .login input:focus, 
        .login textarea:focus, 
        .login select:focus {
            border-color: rgb(177, 145, 98);
        }

        .login button {
            padding: 12px;
            background-color: rgb(72, 35, 35);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 5px;
        }

        .login button:hover {
            background-color: #604141;
        }

        .login .footer-text {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }

        .login .footer-text a {
            color: brown;
            text-decoration: none;
            font-weight: bold;
        }

        .login .footer-text a:hover {
            text-decoration: underline;
        }

        /* Bagian Kanan (Video) */
        .right {
            flex: 1;
            display: flex;
            background-color: #000;
        }

        .right video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Responsif untuk layar HP */
        @media (max-width: 768px) {
            .container {
                flex-direction: column-reverse;
            }
            .right {
                height: 200px;
            }
            .login {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Kolom Form Registrasi -->
        <div class="login">
            <h1>Pendaftaran Akun</h1>
            <hr>
            <p class="sub-title">Silakan isi formulir di bawah ini untuk membuat akun baru.</p>

            <!-- Menampilkan Pesan Error jika ada -->
            <?php echo $pesan; ?>

            <form method="POST" action="">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

                <label>Alamat:</label>
                <textarea name="alamat" placeholder="Masukkan alamat lengkap" required></textarea>

                <label>No HP / WhatsApp:</label>
                <input type="number" name="no_hp" placeholder="Contoh: 08123456789" required>

                <label>Username:</label>
                <input type="text" name="username" placeholder="Masukkan username" required>

                <label>Password:</label>
                <input type="password" name="pasword" placeholder="Masukkan password" required>

                <label for="role">Role / Peran:</label>
                <select id="role" name="role" required>
                    <option value="">Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="pelanggan">Pelanggan</option>
                </select>

                <button type="submit" name="regrestasi">Daftar Sekarang</button>
            </form>

            <div class="footer-text">
                Sudah punya akun? <a href="log.php">Login di sini</a>
            </div>
        </div>

        <div class="right">
            <video autoplay muted loop playsinline>
                <source src="nailong.mp4" type="video/mp4">
                Browser Anda tidak mendukung tag video.
            </video>
        </div>
    </div>

</body>
</html>