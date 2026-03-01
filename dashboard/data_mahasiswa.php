<?php
require_once "../config/database.php";

/* =======================
   TAMBAH USER (ADMIN)
======================= */
if (isset($_POST['tambah'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password_plain = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 6);
    $password_hash  = password_hash($password_plain, PASSWORD_DEFAULT);
    $nomor_tes = "PMB" . date("Y") . rand(1000, 9999);

    $query = mysqli_query($koneksi, "
        INSERT INTO users 
        (email, password, password_plain, nomor_tes, role, status)
        VALUES
        ('$email', '$password_hash', '$password_plain', '$nomor_tes', 'user', 'belum_tes')
    ");

    if (!$query) die("Gagal menambah user: " . mysqli_error($koneksi));
    header("Location: data_mahasiswa.php");
    exit;
}

/* =======================
   EDIT USER
======================= */
if (isset($_POST['edit'])) {
    $id    = $_POST['id'];
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    mysqli_query($koneksi, "UPDATE users SET email='$email' WHERE id='$id'");
    header("Location: data_mahasiswa.php");
    exit;
}

/* =======================
   HAPUS USER
======================= */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");
    header("Location: data_mahasiswa.php");
    exit;
}

/* =======================
   AMBIL DATA USER
======================= */
$users = mysqli_query($koneksi, "
    SELECT * FROM users 
    WHERE role='user' 
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Calon Mahasiswa</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #ffffff, #757575);
    min-height: 100vh;
    color: #fff;
}
.container {
    background: #fff;
    color: #333;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.table thead {
    background-color: #8a2be2;
    color: #fff;
}
.table tbody tr:hover {
    background-color: #f3e6ff;
}
.btn-purple {
    background-color: #8a2be2;
    color: #fff;
}
.btn-purple:hover {
    background-color: #7321c7;
}
.modal-header {
    background-color: #8a2be2;
    color: #fff;
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
<?php include 'sidebar.php'; ?>
<div id="content" class="main-content">

<div class="container mt-5">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Data Calon Mahasiswa</h3>
</div>

<button class="btn btn-purple mb-4" data-bs-toggle="modal" data-bs-target="#tambahModal">
    + Tambah Calon Mahasiswa
</button>

<div class="table-responsive">
<table class="table table-bordered table-striped align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Nomor Tes</th>
    <th>Email</th>
    <th>Password</th>
    <th>Status Tes</th>
    <th>NIM</th>
    <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php $no = 1; while ($u = mysqli_fetch_assoc($users)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($u['nomor_tes']); ?></td>
    <td><?= htmlspecialchars($u['email']); ?></td>
    <td>
        <span class="badge bg-dark" style="cursor:pointer"
              onclick="this.innerText='<?= htmlspecialchars($u['password_plain']); ?>'">
            ••••••
        </span>
    </td>
    <td><?= htmlspecialchars($u['status']); ?></td>
    <td><?= htmlspecialchars($u['nim'] ?? '-'); ?></td>
    <td>
        <button class="btn btn-warning btn-sm mb-1"
                data-bs-toggle="modal"
                data-bs-target="#edit<?= $u['id']; ?>">
            Edit
        </button>
        <a href="?hapus=<?= $u['id']; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin hapus user ini?')">
           Hapus
        </a>
    </td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $u['id']; ?>" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">
<div class="modal-header">
    <h5 class="modal-title">Edit Mahasiswa</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <input type="hidden" name="id" value="<?= $u['id']; ?>">
    <label>Email</label>
    <input type="email" name="email" class="form-control"
           value="<?= htmlspecialchars($u['email']); ?>" required>
</div>
<div class="modal-footer">
    <button type="submit" name="edit" class="btn btn-purple">Simpan</button>
</div>
</form>
</div>
</div>
</div>
<?php endwhile; ?>

</tbody>
</table>
</div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">
<div class="modal-header">
    <h5 class="modal-title">Tambah Calon Mahasiswa</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <label>Email</label>
    <input type="email" name="email" class="form-control mb-2" required>
    <small class="text-muted">
        Password akan digenerate otomatis dan ditampilkan di tabel.
    </small>
</div>
<div class="modal-footer">
    <button type="submit" name="tambah" class="btn btn-purple">Simpan</button>
</div>
</form>
</div>
</div>
</div>
</div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
