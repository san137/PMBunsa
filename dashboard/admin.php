<?php
session_start();
require_once "../config/database.php";

// ======================
// ERROR CHECK DATABASE
// ======================
if(!$koneksi){
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ======================
// QUERY DATA
// ======================

// Total semua pendaftar
$qTotal = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pendaftar");
if(!$qTotal){ die(mysqli_error($koneksi)); }
$total = mysqli_fetch_assoc($qTotal)['total'] ?? 0;

// Belum verifikasi
$qBelum = mysqli_query($koneksi, "
    SELECT COUNT(*) as total 
    FROM pendaftar 
    WHERE status_verifikasi='pending'
");
if(!$qBelum){ die(mysqli_error($koneksi)); }
$totalBelum = mysqli_fetch_assoc($qBelum)['total'] ?? 0;

// Sudah dapat NIM
$qNim = mysqli_query($koneksi, "
    SELECT COUNT(*) as total 
    FROM pendaftar 
    WHERE nim IS NOT NULL AND nim != ''
");
if(!$qNim){ die(mysqli_error($koneksi)); }
$totalNim = mysqli_fetch_assoc($qNim)['total'] ?? 0;

// ======================
// QUERY DATA BULANAN (FIXED)
// ======================
$qBulanan = mysqli_query($koneksi, "
    SELECT 
        DATE_FORMAT(created_at, '%M') as bulan,
        MONTH(created_at) as bulan_angka,
        COUNT(*) as jumlah
    FROM pendaftar
    WHERE created_at IS NOT NULL
    GROUP BY bulan_angka
    ORDER BY bulan_angka
");

if(!$qBulanan){
    die("Query Bulanan Error: " . mysqli_error($koneksi));
}

$bulan = [];
$jumlah = [];

while($row = mysqli_fetch_assoc($qBulanan)){
    $bulan[] = $row['bulan'];
    $jumlah[] = (int)$row['jumlah'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - PMB</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>

<style>
body{
    background: linear-gradient(135deg,#6a11cb,#8b80ff);
    font-family: 'Segoe UI', sans-serif;
    margin:0;
}
.main-content{
    margin-left:250px;
    padding:40px 30px;
}
.dashboard-title{
    color:white;
    font-weight:700;
}
.card{
    border:none;
    border-radius:20px;
    box-shadow:0 15px 40px rgba(0,0,0,.15);
}
.stat-card{
    color:white;
    padding:25px;
    border-radius:20px;
}
.bg-blue{ background: linear-gradient(45deg,#007bff,#00c6ff); }
.bg-red{ background: linear-gradient(45deg,#ff416c,#ff4b2b); }
.bg-green{ background: linear-gradient(45deg,#11998e,#38ef7d); }
.chart-card{ padding:30px; }
canvas{ max-height:300px; }
@media(max-width:992px){
    .main-content{ margin-left:0; padding:20px; }
}
</style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main-content">

<div class="text-center mb-5">
    <h2 class="dashboard-title">Dashboard Admin PMB</h2>
    <p class="text-white-50">Monitoring Statistik Pendaftaran Mahasiswa</p>
</div>

<div class="row g-4 mb-5">

    <div class="col-lg-4">
        <div class="stat-card bg-blue text-center">
            <h6>Total Pendaftar</h6>
            <h2><?= $total ?></h2>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card bg-red text-center">
            <h6>Belum Verifikasi</h6>
            <h2><?= $totalBelum ?></h2>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="stat-card bg-green text-center">
            <h6>Sudah Dapat NIM</h6>
            <h2><?= $totalNim ?></h2>
        </div>
    </div>

</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card chart-card">
            <h5 class="text-center mb-4">Statistik Verifikasi</h5>
            <canvas id="barChart"></canvas>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card chart-card">
            <h5 class="text-center mb-4">Distribusi Status</h5>
            <canvas id="donutChart"></canvas>
        </div>
    </div>
</div>

<div class="card chart-card mt-4">
    <h5 class="text-center mb-4">Pertumbuhan Pendaftar per Bulan</h5>
    <canvas id="lineChart"></canvas>
</div>

</div>

<script>

// ================= BAR =================
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Belum Verifikasi','Sudah Dapat NIM'],
        datasets: [{
            data: [<?= $totalBelum ?>, <?= $totalNim ?>]
        }]
    },
    options: { responsive:true }
});

// ================= DONUT =================
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Belum Verifikasi','Sudah Dapat NIM'],
        datasets: [{
            data: [<?= $totalBelum ?>, <?= $totalNim ?>]
        }]
    },
    options: { responsive:true }
});

// ================= LINE =================
new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($bulan) ?>,
        datasets: [{
            label:'Jumlah Pendaftar',
            data: <?= json_encode($jumlah) ?>,
            fill:true,
            tension:0.3
        }]
    },
    options: { responsive:true }
});

</script>

</body>
</html>
