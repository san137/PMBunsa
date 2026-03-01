<?php
session_start();
require_once "../config/database.php";

/* =======================
   VERIFIKASI & GENERATE NIM
======================= */
if (isset($_GET['verifikasi'])) {
    $id = (int) $_GET['verifikasi'];

    $nim = "NIM-" . date("Y") . "-" . rand(1000, 9999);

    mysqli_query($koneksi, "
        UPDATE users SET
            status_daftar_ulang = 'terverifikasi',
            nim = '$nim'
        WHERE id = '$id'
    ");

    header("Location: verifikasi_daftar_ulang.php");
    exit;
}

/* =======================
   AMBIL DATA
======================= */
$data = mysqli_query($koneksi, "
    SELECT 
        id, nomor_tes, email, nama, alamat, hp,
        nomor_daftar_ulang, nim,
        status_daftar_ulang, tanggal_daftar_ulang
    FROM users
    WHERE status_daftar_ulang IN ('selesai','terverifikasi')
    ORDER BY tanggal_daftar_ulang DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Verifikasi Daftar Ulang</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ================= BODY ================= */
body{
    background: #f4f6f9;
    min-height: 100vh;
    color: #333;
    font-family: 'Segoe UI', sans-serif;
}

/* ================= CONTENT ================= */
#content {
    margin-left: 250px;
    padding: 30px;
    transition: 0.3s ease;
}

#content.expanded {
    margin-left: 80px;
}

/* ================= CARD / GLASS ================= */
.glass{
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* ================= TABLE ================= */
.table-responsive{
    border-radius: 12px;
    overflow-x: auto;
}

.table{
    margin-bottom: 0;
}

.table thead th{
    background: #343a40;
    color: #fff;
    text-align: center;
    font-weight: 500;
}

.table tbody td{
    color: #333;
    vertical-align: middle;
}

.table tbody tr{
    transition: 0.2s ease;
}

.table tbody tr:hover{
    background: #f1f3f5;
}

/* ================= BADGE ================= */
.badge{
    padding: 6px 10px;
    font-size: 0.75rem;
    border-radius: 6px;
}

/* ================= BUTTON ================= */
.btn-custom{
    background: #343a40;
    color: #fff;
    border: none;
}

.btn-custom:hover{
    background: #212529;
}

/* ================= MOBILE ================= */
@media (max-width: 992px) {

    #content {
        margin-left: 0;
        padding: 20px;
    }

    #sidebar {
        margin-left: 0 !important;
    }
}

</style>
</head>
<?php include 'sidebar.php'; ?>

<body>
     <!-- Isi Content -->
      <div id='content'>
    <div class="container-fluid py-4">
        <div class="row g-4">

           


<div class="d-flex justify-content-between align-items-center mb-4">
    <h3> Verifikasi Daftar Ulang</h3>
</div>

<div class="glass table-responsive">
<table class="table table-borderless text-center align-middle mb-0">
<thead>
<tr>
    <th>No</th>
    <th>No Tes</th>
    <th>Email</th>
    <th>Nama</th>
    <th>Alamat</th>
    <th>No HP</th>
    <th>No Daftar Ulang</th>
    <th>NIM</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php $no=1; while($u=mysqli_fetch_assoc($data)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($u['nomor_tes']); ?></td>
    <td><?= htmlspecialchars($u['email']); ?></td>
    <td><?= htmlspecialchars($u['nama']); ?></td>
    <td><?= htmlspecialchars($u['alamat'] ?? 'ALAMAT KOSONG'); ?></td>
    <td><?= htmlspecialchars($u['hp']); ?></td>
    <td class="fw-bold"><?= htmlspecialchars($u['nomor_daftar_ulang']); ?></td>
    <td class="fw-bold text-warning">
        <?= $u['nim'] ? htmlspecialchars($u['nim']) : '-' ?>
    </td>
    <td><?= $u['tanggal_daftar_ulang']; ?></td>
    <td>
        <?php if ($u['status_daftar_ulang'] === 'terverifikasi'): ?>
            <span class="badge bg-success">Terverifikasi</span>
        <?php else: ?>
            <span class="badge bg-warning text-dark">Menunggu</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if ($u['status_daftar_ulang'] === 'selesai'): ?>
            <a href="?verifikasi=<?= $u['id']; ?>"
               onclick="return confirm('Verifikasi dan buatkan NIM?')"
               class="btn btn-sm btn-purple">
                Verifikasi
            </a>
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

        </div>
    </div>
</div>
</div>
</body>
</html>
