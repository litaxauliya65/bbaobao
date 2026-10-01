<?php
session_start();

include 'databse.php'; // Pastikan ejaan 'database.php' benar

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $pasword  = trim($_POST['pasword']);

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($pasword, $row['pasword']) || $pasword === $row['pasword']) {
            
            // Simpan SESSION secara LENGKAP
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: index.php");
                exit();
            } elseif ($row['role'] === 'pelanggan') {
                header("Location: web_umkm.php");
                exit();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        }

        .container {
            display: flex;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 800px;
            max-width: 90%;
            min-height: 450px;
        }

        /* Bagian Kiri (Form Login) */
        .login {
            flex: 1;
            padding: 40px;
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
            margin-bottom: 8px;
        }

        .login hr {
            border: none;
            height: 2px;
            background-color: rgb(72, 35, 35);
            margin-bottom: 12px;
        }

        .login p {
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .login label {
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
            text-transform: capitalize;
        }

        .login input {
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
        }

        .login input:focus {
            border-color: rgb(177, 145, 98);
        }

        .login button {
            padding: 12px;
            background-color: rgb(72, 35, 35);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .login button:hover {
            background-color: #604141;
        }

        .login .footer-text {
            margin-top: 15px;
            text-align: center;
        }

        .login .footer-text a {
            color: brown;
            text-decoration: none;
            font-size: 14px;
        }

        .login .footer-text a:hover {
            text-decoration: underline;
        }

        /* Bagian Kanan (Gambar/Video) */
        .right {
            flex: 1;
            display: flex;
        }

        .right video {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Agar gambar/video menyesuaikan area tanpa terdistorsi */
        }

        /* Responsif untuk layar HP */
        @media (max-width: 650px) {
            .container {
                flex-direction: column-reverse;
            }
            .right {
                height: 200px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Bagian Login (Kiri) -->
        <div class="login">
            <h1>Login</h1>
            <hr>
            <p>Silakan masuk ke akun Anda</p>

            <?php if (isset($error)) : ?>
                <p style="color: red;"><?= $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="pasword">Password:</label>
                <input type="password" id="pasword" name="pasword" required>

                <button type="submit" name="login">Login</button>
            </form>

            <p class="footer-text">Belum punya akun? <a href="regrestasi.php">Buat Akun Baru</a></p>
        </div>
        <div class="right">
            <video autoplay loop muted playsinline>
                <source src="nailong.mp4" type="video/mp4">
                Browser kamu tidak mendukung pemutaran video.
            </video>
        </div>
    </div>

</body>
</html>