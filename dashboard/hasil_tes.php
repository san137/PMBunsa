<?php
session_start();
require_once "../config/database.php";

/* =======================
   CEK LOGIN & DATA
======================= */


if (!isset($_POST['jawaban'])) {
    header("Location: tes.php");
    exit;
}

$nomor_tes    = mysqli_real_escape_string($koneksi, $_SESSION['nomor_tes']);
$jawaban_user = $_POST['jawaban'];

/* =======================
   HITUNG NILAI
======================= */
$total_soal = 0;
$benar = 0;

$q = mysqli_query($koneksi, "SELECT id, jawaban FROM soal");
while ($s = mysqli_fetch_assoc($q)) {
    $total_soal++;
    if (isset($jawaban_user[$s['id']]) && $jawaban_user[$s['id']] === $s['jawaban']) {
        $benar++;
    }
}

$salah = $total_soal - $benar;
$nilai = ($total_soal > 0) ? round(($benar / $total_soal) * 100) : 0;

/* =======================
   STATUS KELULUSAN
======================= */
$status_lulus = ($nilai >= 70) ? "lulus" : "tidak_lulus";
$nomor_daftar_ulang = null;

if ($status_lulus === "lulus") {
    $nomor_daftar_ulang = "DU-" . date("Y") . "-" . rand(10000,99999);
}

/* =======================
   UPDATE DATABASE
======================= */
mysqli_query($koneksi, "
    UPDATE users SET
        nilai='$nilai',
        status_lulus='$status_lulus',
        nomor_daftar_ulang=" . ($nomor_daftar_ulang ? "'$nomor_daftar_ulang'" : "NULL") . ",
        status='selesai_tes'
    WHERE nomor_tes='$nomor_tes'
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Hasil Tes PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ======================
   THEME HITAM PUTIH
====================== */
:root{
    --bs-primary:#000000;
    --bs-success:#000000;
    --bs-success-rgb:0,0,0;
}

body{
    background:#ffffff;
    color:#000000;
}

/* CARD */
.card{
    border-radius:12px;
    border:1px solid #e0e0e0;
    box-shadow:0 4px 15px rgba(0,0,0,.05);
    background:#ffffff;
}

/* BUTTON */
.btn-primary,
.btn-success{
    background:#000000;
    border:none;
    color:#ffffff;
}

.btn-primary:hover,
.btn-success:hover{
    background:#333333;
}

/* BADGE */
.badge-lulus{
    background:#000000;
    color:#ffffff;
}

/* SCORE */
.score{
    font-size:64px;
    font-weight:bold;
    color:#000000;
    animation: pop .6s ease;
}

@keyframes pop{
    0%{transform:scale(.5);opacity:0}
    100%{transform:scale(1);opacity:1}
}

/* ================= CONTENT ================= */
#content {
    margin-left: 250px;
    padding: 20px;
    transition: all 0.3s;
}

#content.expanded {
    margin-left: 80px;
}

/* ================= MOBILE ================= */
@media (max-width: 992px) {
    #sidebar {
        margin-left: -250px;
    }

    #sidebar.mobile-show {
        margin-left: 0;
    }

    #content {
        margin-left: 0;
    }
}

.toggle-btn {
    cursor: pointer;
}

</style>
</head>

<body>

<div class="container mt-5">
<div class="card">
<div class="card-body text-center">

    <h3 class="mb-4"> Hasil Tes PMB</h3>

    <!-- NILAI -->
    <div class="score mb-3"><?= $nilai ?></div>
    <p class="text-muted">Nilai Akhir</p>

    <!-- STATUS -->
    <?php if ($status_lulus === "lulus"): ?>
        <span class="badge badge-lulus fs-6 px-3 py-2 mb-3 d-inline-block">
             LULUS
        </span>
    <?php else: ?>
        <span class="badge bg-danger fs-6 px-3 py-2 mb-3 d-inline-block">
             TIDAK LULUS
        </span>
    <?php endif; ?>

    <!-- DETAIL -->
    <table class="table table-bordered mt-4">
        <tr>
            <th>Total Soal</th>
            <td><?= $total_soal ?></td>
        </tr>
        <tr>
            <th>Jawaban Benar</th>
            <td class="fw-bold text-success"><?= $benar ?></td>
        </tr>
        <tr>
            <th>Jawaban Salah</th>
            <td class="fw-bold text-danger"><?= $salah ?></td>
        </tr>
    </table>

    <?php if ($status_lulus === "lulus"): ?>
    <!-- DAFTAR ULANG -->
    <div class="card border-0 bg-light mt-4">
        <div class="card-body">
            <h5 class="text-primary">Nomor Daftar Ulang</h5>
            <h4 class="fw-bold"><?= htmlspecialchars($nomor_daftar_ulang); ?></h4>
            <small class="text-muted">Simpan nomor ini untuk proses daftar ulang</small>
        </div>
    </div>

    <a href="../dashboard/daftar_ulang.php"
       class="btn btn-success w-100 mt-3">
         Lanjut Daftar Ulang
    </a>

    <?php else: ?>
    <div class="alert alert-danger mt-4">
        Tetap semangat dan terus belajar!
    </div>
    <?php endif; ?>

    <a href="../dashboard/user.php"
       class="btn btn-outline-primary w-100 mt-3">
         Kembali ke Dashboard
    </a>

</div>
</div>
</div>

<script>
// animasi tambahan
document.addEventListener("DOMContentLoaded",()=>{
    document.querySelector(".score")?.classList.add("animate");
});
</script>

</body>
</html>
