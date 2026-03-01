<?php
session_start();
require_once "../config/database.php";

/* ==========================
   CEK LOGIN
========================== */
if (!isset($_SESSION['user_id'])) {
        exit;
}

$user_id = $_SESSION['user_id'];

/* ==========================
   AMBIL DATA USER
========================== */
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    // user tidak ditemukan, logout
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,.05);
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: #fff;
        }
        h1 {
            color: #333;
        }
        .text-muted {
            color: #666;
        }
        .logout-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="card text-center">
    <h1>Welcome</h1>
    <p class="text-muted">
        <?= htmlspecialchars($user['nama'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
    </p>

    <p>Email: <?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>

    <a href="../auth/logout.php" class="btn btn-danger logout-btn">Logout</a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
