<?php
session_start();

// Jika user belum login, redirect ke login
if(!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data session
$nomorTes = isset($_SESSION['nomor_tes']) ? $_SESSION['nomor_tes'] : '-';
$email    = isset($_SESSION['email']) ? $_SESSION['email'] : 'User';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Navbar PMB</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #2c3e50;
      color: white;
      padding: 10px 20px;
    }
    .navbar .brand {
      font-size: 20px;
      font-weight: bold;
    }
    .navbar .right {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .navbar button {
      padding: 5px 12px;
      font-size: 14px;
      cursor: pointer;
      border: none;
      border-radius: 3px;
      background-color: #3498db;
      color: white;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">PMB</a>

    <div class="d-flex align-items-center ms-auto gap-3">
        <span class="text-white">Nomor Tes: <?= htmlspecialchars($nomorTes) ?></span>
        <form action="../auth/logout.php" method="POST" style="margin:0;">
            <button type="submit" name="logout" class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
  </div>
</nav>

</body>
</html>
