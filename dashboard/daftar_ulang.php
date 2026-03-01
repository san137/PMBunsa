<?php
session_start();
require_once "../config/database.php";



$nomor_tes = mysqli_real_escape_string($koneksi, $_SESSION['nomor_tes']);

/* =======================
   AMBIL DATA USER
======================= */
$query = mysqli_query($koneksi, "
    SELECT 
        nomor_tes,
        email,
        status_lulus,
        nomor_daftar_ulang,
        status_daftar_ulang
    FROM users
    WHERE nomor_tes = '$nomor_tes'
");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}

$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("Data user tidak ditemukan.");
}

/* =======================
   VALIDASI STATUS
======================= */
if ($user['status_lulus'] !== 'lulus') {
    die("Anda belum lulus tes PMB.");
}

if ($user['status_daftar_ulang'] === 'selesai' || 
    $user['status_daftar_ulang'] === 'terverifikasi') {
    header("Location: user.php");
    exit;
}

/* =======================
   SIMPAN DAFTAR ULANG
======================= */
if (isset($_POST['simpan'])) {

    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp     = mysqli_real_escape_string($koneksi, $_POST['hp']);

    $simpan = mysqli_query($koneksi, "
        UPDATE users SET
            nama='$nama',
            alamat='$alamat',
            hp='$hp',
            status_daftar_ulang='selesai',
            tanggal_daftar_ulang=NOW()
        WHERE nomor_tes='$nomor_tes'
    ");

    if ($simpan) {
        header("Location: user.php?status=daftar_ulang_sukses");
        exit;
    } else {
        die("Gagal menyimpan data daftar ulang");
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar Ulang PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* ======================
   THEME UNGU PMB
====================== */
:root{
    --ungu:#6a11cb;
    --ungu-dark:#5a34a1;
}

body{
    background:#f4f0fb;
}

.card{
    border-radius:16px;
    box-shadow:0 10px 30px rgba(111,66,193,.15);
}

.btn-primary{
    background:var(--ungu);
    border:none;
}
.btn-primary:hover{
    background:var(--ungu-dark);
}

.form-control{
    border-radius:10px;
}
.form-control:focus{
    border-color:var(--ungu);
    box-shadow:0 0 0 .25rem rgba(111,66,193,.25);
}

.alert-success{
    background:#efe9fb;
    border:none;
    color:#4b2c91;
}

.fade-in{
    animation:fade .6s ease;
}
@keyframes fade{
    from{opacity:0;transform:translateY(10px)}
    to{opacity:1;transform:none}
}
</style>
</head>

<body>

<div class="container mt-5 fade-in">
<div class="card">
<div class="card-body p-4">

    <h3 class="text-center mb-4"> Daftar Ulang PMB</h3>

    <div class="alert alert-success text-center">
         Anda dinyatakan <b>LULUS</b> Tes PMB <br>
        Nomor Daftar Ulang:
        <h4 class="fw-bold mt-2">
            <?= htmlspecialchars($user['nomor_daftar_ulang']); ?>
        </h4>
        <small class="text-muted">
            Silakan lengkapi data berikut
        </small>
    </div>

    <form method="POST" id="formDaftarUlang">
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Alamat Lengkap</label>
            <textarea name="alamat" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">No. HP / WhatsApp</label>
            <input type="text" name="hp" class="form-control" required>
        </div>

        <button name="simpan" class="btn btn-primary w-100 py-2">
             Kirim Daftar Ulang
        </button>
    </form>

</div>
</div>
</div>

<script>
// animasi tombol submit
document.getElementById("formDaftarUlang").addEventListener("submit", ()=>{
    document.querySelector("button[name=simpan]").innerText = "⏳ Mengirim...";
});
</script>

</body>
</html>
