<?php
session_start();
require_once "../config/database.php";

/* ==============================
   CEK LOGIN & AKSES TES
============================== */
if (!isset($_SESSION['user_id']) || !isset($_SESSION['akses_tes'])) {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ==============================
   AMBIL DATA USER
============================== */
$userQuery = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userQuery);

if (!$user) {
    header("Location: dashboard.php");
    exit;
}

/* ==============================
   CEK SUDAH TES ATAU BELUM
============================== */
if ($user['status_lulus'] !== null) {
    header("Location: dashboard.php");
    exit;
}

/* ==============================
   SIMPAN SOAL KE SESSION (ANTI BERUBAH SAAT REFRESH)
============================== */
if (!isset($_SESSION['soal_acak'])) {
    $soalQuery = mysqli_query($koneksi, "SELECT * FROM soal ORDER BY RAND()");
    $_SESSION['soal_acak'] = mysqli_fetch_all($soalQuery, MYSQLI_ASSOC);
}

$soal = $_SESSION['soal_acak'];
$total_soal = count($soal);

/* ==============================
   TIMER SERVER (ANTI REFRESH)
============================== */
if (!isset($_SESSION['waktu_mulai'])) {
    $_SESSION['waktu_mulai'] = time();
}

$durasi = 60 * 60; // 30 menit
$sisa_waktu = $durasi - (time() - $_SESSION['waktu_mulai']);

if ($sisa_waktu <= 0) {
    header("Location: hasil_tes.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tes Seleksi PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --primary:#6f42c1;
}
body{
    background:#f4f0fb;
}
.no-select{
    user-select:none;
}
.card{
    border-radius:16px;
    box-shadow:0 10px 30px rgba(111,66,193,.15);
}
.btn-primary{
    background:var(--primary);
    border:none;
}
.btn-primary:hover{
    background:#5a34a1;
}
.progress{
    height:25px;
}
.progress-bar{
    background:var(--primary);
}
.modal-header{
    background:var(--primary);
    color:#fff;
}
</style>
</head>

<body class="no-select">

<div class="container mt-4">

<!-- HEADER -->
<div class="card mb-4">
<div class="card-body d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Tes Seleksi PMB</h4>
    <div class="fw-bold text-danger">
        ⏱️ <span id="timer"></span>
    </div>
</div>
</div>

<!-- PROGRESS -->
<div class="progress mb-3">
  <div class="progress-bar progress-bar-striped progress-bar-animated"
       id="progressBar" style="width:0%">
    0%
  </div>
</div>

<form id="formTes" method="POST" action="hasil_tes.php">

<?php $no=1; foreach($soal as $s): ?>
<div class="card mb-3">
<div class="card-body">

    <h6 class="fw-bold mb-3">
        <?= $no++; ?>. <?= htmlspecialchars($s['pertanyaan']); ?>
    </h6>

    <?php foreach(['A','B','C','D'] as $o): ?>
    <div class="form-check mb-1">
        <input class="form-check-input jawaban"
               type="radio"
               name="jawaban[<?= $s['id']; ?>]"
               value="<?= $o ?>"
               required>
        <label class="form-check-label">
            <?= htmlspecialchars($s['opsi_'.strtolower($o)]); ?>
        </label>
    </div>
    <?php endforeach; ?>

</div>
</div>
<?php endforeach; ?>

<button type="button" class="btn btn-primary w-100 py-2" onclick="cekJawaban()">
    Kirim Jawaban
</button>

</form>
</div>

<!-- MODAL KONFIRMASI -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Simpan Jawaban</h5>
      </div>
      <div class="modal-body text-center">
        <p class="fs-5">Yakin ingin menyimpan jawaban?</p>
        <small class="text-muted">Jawaban tidak dapat diubah.</small>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" onclick="submitTes()">Ya, Simpan</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ================= TIMER ================= */
let waktu = <?= $sisa_waktu ?>;
const timerEl = document.getElementById("timer");

const timer = setInterval(() => {
    let m = Math.floor(waktu / 60);
    let d = waktu % 60;
    timerEl.innerText = `${m}:${d < 10 ? '0'+d : d}`;
    waktu--;

    if (waktu < 0) {
        clearInterval(timer);
        document.getElementById("formTes").submit();
    }
}, 1000);

/* ================= PROGRESS ================= */
const radios = document.querySelectorAll(".jawaban");
const progressBar = document.getElementById("progressBar");

radios.forEach(r => {
    r.addEventListener("change", () => {
        let terjawab = document.querySelectorAll("input[type=radio]:checked").length;
        let persen = Math.round((terjawab / <?= $total_soal ?>) * 100);
        progressBar.style.width = persen + "%";
        progressBar.innerText = persen + "%";
    });
});

/* ================= VALIDASI ================= */
function cekJawaban(){
    let terjawab = document.querySelectorAll("input[type=radio]:checked").length;
    if (terjawab < <?= $total_soal ?>) {
        alert("Masih ada soal yang belum dijawab!");
        return;
    }
    new bootstrap.Modal(document.getElementById("modalKonfirmasi")).show();
}

function submitTes(){
    document.getElementById("formTes").submit();
}

/* ================= SECURITY ================= */
document.addEventListener("contextmenu", e => e.preventDefault());
document.addEventListener("keydown", e => {
    if (e.key === "F5" || (e.ctrlKey && e.key === "r")) e.preventDefault();
});

/* ================= FULLSCREEN ================= */
document.documentElement.requestFullscreen().catch(()=>{});
</script>

</body>
</html>
