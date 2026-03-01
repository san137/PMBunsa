<?php
session_start();
require_once "../config/database.php";

/* ==========================
   CEK LOGIN
========================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ==========================
   AMBIL DATA USER
========================== */
$result = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

/* ==========================
   GENERATE NOMOR TES JIKA KOSONG
========================== */
if (empty($user['nomor_tes'])) {

    do {
        $nomor_tes = 'TES' . str_pad(rand(1,9999),4,'0',STR_PAD_LEFT);
        $check = mysqli_query($koneksi, "SELECT id FROM users WHERE nomor_tes='$nomor_tes'");
    } while(mysqli_num_rows($check) > 0);

    mysqli_query($koneksi, "UPDATE users SET nomor_tes='$nomor_tes' WHERE id='$user_id'");
    $user['nomor_tes'] = $nomor_tes;
}

/* ==========================
   VERIFIKASI NOMOR TES
========================== */
if (isset($_POST['verifikasi'])) {

    $input = mysqli_real_escape_string($koneksi, $_POST['input_nomor_tes']);

    if ($input === $user['nomor_tes']) {

        $_SESSION['akses_tes'] = true;
        $_SESSION['waktu_mulai'] = time();

        header("Location: tes.php");
        exit;

    } else {
        $error = "Nomor Tes Salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --primary:#6a11cb;
    --secondary:#8b80ff;
}
body{
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    min-height:100vh;
     display: flex;
    flex-direction: column;
    min-height: 100vh; /* full height viewport */
}
.card{
    border-radius:16px;
    box-shadow:0 15px 40px rgba(0,0,0,.15);
}
.badge-status{
    font-size:1rem;
    padding:8px 14px;
}
.navbar{
    background:#fff;
}


.main-content {
    flex: 1; /* mengambil space yang tersisa */
}
</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
    
    <div class="container-fluid">

        <!-- BRAND -->
        <a class="navbar-brand fw-bold">PMB</a>

        <!-- RIGHT SIDE -->
        <div class="d-flex align-items-center gap-3 ms-auto">

            <!-- NOMOR TES -->
            <span class="badge bg-warning text-dark fs-6">
                Nomor Tes: <?= htmlspecialchars($user['nomor_tes']); ?>
            </span>

            <!-- LOGOUT -->
            <form action="../auth/logout.php" method="POST" class="mb-0">
                <button type="submit" name="logout" 
                        class="btn btn-danger btn-sm">
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>


<div class="main-content">

<div class="container d-flex justify-content-center align-items-center mt-5">
<div class="col-lg-6 col-md-8 col-sm-12">

<div class="card p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold">Selamat Datang</h3>
        <p class="text-muted"><?= htmlspecialchars($user['email']); ?></p>
    </div>

    <?php if (!$user['status_lulus']): ?>
        <div class="alert alert-info text-center">
            Anda belum mengikuti tes PMB
        </div>

        <button class="btn btn-primary w-100"
                data-bs-toggle="modal"
                data-bs-target="#modalTes">
            Mulai Tes
        </button>

    <?php elseif ($user['status_lulus'] === 'tidak_lulus'): ?>
        <div class="alert alert-danger text-center">
            Maaf, Anda <b>tidak lulus</b> tes PMB
        </div>

    <?php elseif ($user['status_lulus'] === 'lulus' && !$user['status_daftar_ulang']): ?>
        <div class="alert alert-success text-center">
            Selamat! Anda <b>LULUS</b> tes PMB
        </div>
        <a href="daftar_ulang.php" class="btn btn-success w-100">
            Daftar Ulang
        </a>

    <?php elseif ($user['status_daftar_ulang'] === 'selesai'): ?>
        <div class="alert alert-warning text-center">
            Daftar ulang telah dikirim<br>
            <b>Menunggu verifikasi admin</b>
        </div>

  <?php elseif ($user['status_daftar_ulang'] === 'terverifikasi'): ?>
    
    <div class="border border-success rounded p-4 text-center">
        <h4 class="text-success fw-bold mb-3">🎓 RESMI MAHASISWA</h4>
        
        <p class="mb-2">Nomor Induk Mahasiswa (NIM)</p>
        <h2 class="fw-bold text-primary">
            <?= htmlspecialchars($user['nim']); ?>
        </h2>

        <span class="badge bg-success badge-status mt-2 mb-4">
            Terverifikasi Admin
        </span>

        <div class="mt-3">
            <a href="../dashboard/univnus.php" class="btn btn-primary w-100">
                Masuk ke Halaman Utama Mahasiswa
            </a>
        </div>
    </div>

<?php endif; ?>

   

</div>
</div>
</div>

<!-- MODAL VERIFIKASI NOMOR TES -->
<div class="modal fade" id="modalTes" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Verifikasi Nomor Tes</h5>
      </div>

      <div class="modal-body">

        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
          <div class="mb-3">
            <label class="form-label">Masukkan Nomor Tes</label>
            <input type="text"
                   name="input_nomor_tes"
                   class="form-control"
                   required>
          </div>

          <button type="submit"
                  name="verifikasi"
                  class="btn btn-primary w-100">
              Verifikasi & Mulai
          </button>
        </form>

      </div>

    </div>
  </div>
  </div>
</div>
<?php include 'footer.php'?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
