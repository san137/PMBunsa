<?php
session_start();
if(!isset($_SESSION['user'])){
    $_SESSION['user'] = "Demo User";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* ================= SIDEBAR ================= */
        #sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #343a40;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        #sidebar .nav-link {
            color: white;
            border-radius: 8px;
        }

        #sidebar .nav-link:hover {
            background: #495057;
        }

        /* ================= CONTENT ================= */
        #content {
            margin-left: 250px;
            padding: 30px;
        }

        /* ================= MOBILE ================= */
        @media (max-width: 992px) {

            #sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            #content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<!-- ================= SIDEBAR ================= -->
<div id="sidebar" class="text-white">

    <div class="mb-4">
        <h5 class="fw-bold">Dashboard Admin</h5>
    </div>

    <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item">
            <a href="data_mahasiswa.php" class="nav-link">Data Mahasiswa</a>
        </li>
        <li class="nav-item">
            <a href="soal.php" class="nav-link">Soal</a>
        </li>
        <li class="nav-item">
            <a href="monitoring_hasil_tes.php" class="nav-link">Hasil Tes</a>
        </li>
        <li class="nav-item">
            <a href="verifikasi_daftar_ulang.php" class="nav-link">Verifikasi Daftar Ulang</a>
        </li>
    </ul>

    <div class="mt-auto">
        <hr>
        <a href="../auth/logout.php" class="nav-link text-danger">Logout</a>
    </div>

</div>



</body>
</html>
