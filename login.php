<?php
session_start();
include 'databse.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $pasword  = $_POST['pasword'];


    $query  = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($pasword === $row['pasword'] || password_verify($pasword, $row['pasword'])) {
            
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: index.php");
                exit();
            } elseif ($row['role'] === 'pelanggan') {
                header("Location: album1.php");
                exit();
            } else {
                $error = "Role tidak dikenali!";
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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Login</title>
</head>
<body>
    <h2>Silakan Login</h2>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="pasword" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Buat Akun Baru</a></p>
</body>
</html>