<?php
session_start();
require_once "../config/database.php";

/* =======================
   CEK LOGIN ADMIN
======================= */



/* =======================
   AMBIL DATA PESERTA
======================= */
$data = mysqli_query($koneksi, "
    SELECT nomor_tes, email, nilai, status_lulus, nomor_daftar_ulang
    FROM users
    WHERE status_lulus IS NOT NULL
    ORDER BY nilai DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Monitoring Hasil Tes PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #ffffff, #a5a5a5);
    min-height: 100vh;
    color: #fff;
}

/* CARD & GLASS */
.glass {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px;
}

/* TABLE */
.table-responsive {
    border-radius: 15px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: rgba(0,0,0,0.35);
    color: #fff;
    text-align: center;
}

.table tbody td {
    color: #020202;
    vertical-align: middle;
}

.table tbody tr {
    background: rgba(255,255,255,0.10);
    transition: 0.3s;
}

.table tbody tr:hover {
    background: rgba(255,255,255,0.25);
    transform: scale(1.01);
}

/* BADGE */
.badge {
    font-size: 0.85rem;
    padding: 8px 12px;
}

/* SUMMARY CARD */
.summary {
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    font-weight: bold;
    transition: 0.3s;
}

.summary:hover {
    transform: translateY(-5px);
}

.summary.total {
    background: linear-gradient(135deg, #2f00ff, #5500ff);
}

.summary.lulus {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.summary.gagal {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
}
</style>
</head>

<body>
      <?php include 'sidebar.php'; ?>
<div id="content" class="main-content">
<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3> Monitoring Hasil Tes PMB</h3>
</div>

<div class="glass mb-4 table-responsive">
<table class="table table-borderless text-center">
<thead>
<tr>
    <th>No</th>
    <th>Nomor Tes</th>
    <th>Email</th>
    <th>Nilai</th>
    <th>Status</th>
    <th>No. Daftar Ulang</th>
</tr>
</thead>
<tbody>

<?php if (mysqli_num_rows($data) == 0): ?>
<tr>
    <td colspan="6" class="text-muted">Belum ada peserta</td>
</tr>
<?php endif; ?>

<?php $no=1; while($u=mysqli_fetch_assoc($data)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($u['nomor_tes']); ?></td>
    <td><?= htmlspecialchars($u['email']); ?></td>
    <td class="fw-bold">
        <?= $u['nilai']; ?>
    </td>
    <td>
        <?php if ($u['status_lulus'] === 'lulus'): ?>
            <span class="badge bg-success">LULUS</span>
        <?php else: ?>
            <span class="badge bg-danger">TIDAK LULUS</span>
        <?php endif; ?>
    </td>
    <td>
        <?= $u['nomor_daftar_ulang'] 
            ? htmlspecialchars($u['nomor_daftar_ulang']) 
            : '-' ?>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

<?php
$total = mysqli_num_rows($data);
$lulus = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) total FROM users WHERE status_lulus='lulus'"
))['total'];
$gagal = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT COUNT(*) total FROM users WHERE status_lulus='tidak_lulus'"
))['total'];
?>

<!-- SUMMARY -->
<div class="row g-3">
    <div class="col-md-4">
        <div class="summary total">
            Total Peserta<br>
            <h2><?= $total ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary lulus">
            Lulus<br>
            <h2><?= $lulus ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="summary gagal">
            Tidak Lulus<br>
            <h2><?= $gagal ?></h2>
        </div>
    </div>
</div>
</div>

</div>
</body>
</html>
