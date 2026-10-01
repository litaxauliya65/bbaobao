<?php 

if (session_id() == '') { 
    session_start(); 
} 

include "databse.php"; 

if (!isset($_SESSION['username'])) { 
    header("Location: log.php"); 
    exit; 
} 

$username = $_SESSION['username']; 
$username = mysqli_real_escape_string($koneksi, $username); 

$query = mysqli_query( 
    $koneksi, 
    "SELECT * FROM user WHERE username='$username'" 
); 

$user = mysqli_fetch_assoc($query); 

?> 

<!DOCTYPE html> 
<html lang="id"> 

<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Edit Profil - Liya Chocolate House</title> 

    <style> 

        * { 
            box-sizing: border-box; 
        } 

        body { 
            margin: 0; 
            background: #f5f5f5; 
            font-family: Arial, sans-serif; 
            color: #333; 
        } 

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #2B1710;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            margin-top: 0;
            margin-bottom: 30px;
            color: #C9975B;
            font-size: 22px;
        }

        .sidebar p {
            margin-bottom: 25px;
            font-size: 14px;
        }

        .sidebar a {
            display: block;
            text-decoration: none;
            color: white;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 14px;
        }

        .sidebar a:hover {
            background: #6B3E23;
        }

        .sidebar .active {
            background: #6B3E23;
        }


        /* CONTENT */
        .content { 
            margin-left: 260px; 
            padding: 20px; 
        } 


        /* FORM */
        .form-box { 
            max-width: 600px; 
            background: white; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
        } 

        .form-box h2 { 
            margin-top: 0; 
            font-size: 20px; 
            border-bottom: 2px solid #6B3E23; 
            padding-bottom: 10px; 
            color: #6B3E23; 
        } 

        .form-group { 
            margin-bottom: 15px; 
        } 

        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            font-size: 14px; 
        } 

        input[type="text"], 
        input[type="password"], 
        input[type="file"] { 
            width: 100%; 
            padding: 8px; 
            border: 1px solid #ccc; 
            border-radius: 3px; 
            font-size: 14px; 
        } 

        input:focus { 
            outline: none; 
            border-color: #6B3E23; 
        } 

        .btn { 
            background: #6B3E23; 
            color: white; 
            border: none; 
            padding: 9px 18px; 
            border-radius: 3px; 
            cursor: pointer; 
            font-size: 14px; 
        } 

        .btn:hover { 
            background: #4a2b18; 
        } 

        .back { 
            display: inline-block; 
            margin-left: 5px; 
            padding: 8px 15px; 
            border-radius: 3px; 
            text-decoration: none; 
            background: #e0e0e0; 
            color: #333; 
            font-size: 14px; 
            border: 1px solid #ccc; 
        } 

        .back:hover { 
            background: #d0d0d0; 
        } 


        /* RESPONSIVE */
        @media(max-width: 800px) { 

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content { 
                margin-left: 0; 
                padding: 15px; 
            } 

        } 

    </style> 
</head> 

<body> 


<!-- SIDEBAR -->
<div class="sidebar">

    <h2>Liya Chocolate House</h2>

    <p>
        Halo, <?= htmlspecialchars($user['nama']); ?>
    </p>
    <a href="edit_profil.php" class="active">
        Edit Profil
    </a>

    <a href="pesanan.php">
        Pesanan Saya
    </a>

    <a href="log.php">
        Keluar
    </a>

</div>


<!-- CONTENT -->
<div class="content"> 

    <div class="form-box"> 

        <h2>Edit Profil</h2> 

        <form action="update_profil.php" method="POST" enctype="multipart/form-data"> 

            <!-- NAMA --> 
            <div class="form-group"> 
                <label>Nama</label> 

                <input type="text" 
                       name="nama" 
                       value="<?php echo isset($user['nama']) ? htmlspecialchars($user['nama']) : ''; ?>" 
                       required> 
            </div> 


            <!-- USERNAME --> 
            <div class="form-group"> 
                <label>Username</label> 

                <input type="text" 
                       name="username" 
                       value="<?php echo isset($user['username']) ? htmlspecialchars($user['username']) : ''; ?>" 
                       required> 
            </div> 


            <!-- ALAMAT --> 
            <div class="form-group"> 
                <label>Alamat</label> 

                <input type="text" 
                       name="alamat" 
                       value="<?php echo isset($user['alamat']) ? htmlspecialchars($user['alamat']) : ''; ?>"> 
            </div> 


            <!-- NO HP --> 
            <div class="form-group"> 
                <label>No. HP</label> 

                <input type="text" 
                       name="no_hp" 
                       value="<?php echo isset($user['no_hp']) ? htmlspecialchars($user['no_hp']) : ''; ?>"> 
            </div> 


            <!-- PASSWORD --> 
            <div class="form-group"> 
                <label>Password Baru</label> 

                <input type="password" 
                       name="password" 
                       placeholder="Kosongkan jika tidak diganti"> 
            </div> 


            <!-- FOTO PROFIL --> 
            <div class="form-group"> 
                <label>Foto Profil</label> 

                <input type="file" 
                       name="foto_profil" 
                       accept="image/*"> 
            </div> 


            <br> 

            <button type="submit" class="btn">
                Simpan
            </button> 

            <a href="web_umkm.php" class="back">
                Batal
            </a> 

        </form> 

    </div> 

</div> 

</body> 
</html>