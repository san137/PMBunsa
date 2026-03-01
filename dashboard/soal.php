<?php
require_once "../config/database.php";

/* =======================
   IMPORT CSV
======================= */
if (isset($_POST['import'])) {

    if ($_FILES['file_csv']['name'] != "") {

        $file = $_FILES['file_csv']['tmp_name'];
        $handle = fopen($file, "r");

        if ($handle !== FALSE) {

            $row = 0;

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                $row++;

                // Skip header jika ada
                if ($row == 1 && strtolower($data[0]) == "pertanyaan") {
                    continue;
                }

                if (count($data) < 6) continue;

                $pertanyaan = mysqli_real_escape_string($koneksi, $data[0]);
                $a = mysqli_real_escape_string($koneksi, $data[1]);
                $b = mysqli_real_escape_string($koneksi, $data[2]);
                $c = mysqli_real_escape_string($koneksi, $data[3]);
                $d = mysqli_real_escape_string($koneksi, $data[4]);
                $jawaban = strtoupper(trim($data[5]));

                if (!in_array($jawaban, ['A','B','C','D'])) continue;

                mysqli_query($koneksi, "
                    INSERT INTO soal (pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban)
                    VALUES ('$pertanyaan','$a','$b','$c','$d','$jawaban')
                ");
            }

            fclose($handle);
        }
    }

    header("Location: soal.php");
    exit;
}


/* =======================
   SIMPAN SOAL
======================= */
if (isset($_POST['simpan'])) {

    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($koneksi, $_POST['a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['d']);
    $jawaban = $_POST['jawaban'];

    mysqli_query($koneksi, "
        INSERT INTO soal (pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, jawaban)
        VALUES ('$pertanyaan','$a','$b','$c','$d','$jawaban')
    ");

    header("Location: soal.php");
    exit;
}


/* =======================
   UPDATE SOAL
======================= */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $a = mysqli_real_escape_string($koneksi, $_POST['a']);
    $b = mysqli_real_escape_string($koneksi, $_POST['b']);
    $c = mysqli_real_escape_string($koneksi, $_POST['c']);
    $d = mysqli_real_escape_string($koneksi, $_POST['d']);
    $jawaban = $_POST['jawaban'];

    mysqli_query($koneksi, "
        UPDATE soal SET
            pertanyaan='$pertanyaan',
            opsi_a='$a',
            opsi_b='$b',
            opsi_c='$c',
            opsi_d='$d',
            jawaban='$jawaban'
        WHERE id='$id'
    ");

    header("Location: soal.php");
    exit;
}


/* =======================
   HAPUS SOAL
======================= */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM soal WHERE id='$id'");
    header("Location: soal.php");
    exit;
}


/* =======================
   AMBIL DATA
======================= */
$soal = mysqli_query($koneksi, "SELECT * FROM soal ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Manajemen Soal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include 'sidebar.php'; ?>
<div id="content" class="main-content">
<div class="container py-5">

<h3 class="mb-4">Manajemen Soal Tes</h3>

<!-- IMPORT CSV -->
<div class="card p-4 mb-4">
    <h5>Import Soal dari CSV</h5>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file_csv" accept=".csv" class="form-control mb-3" required>
        <button name="import" class="btn btn-primary">Import CSV</button>
    </form>
</div>

<!-- FORM TAMBAH -->
<div class="card p-4 mb-4">
<form method="POST">
    <h5 class="mb-3">Tambah Soal Manual</h5>

    <textarea name="pertanyaan" class="form-control mb-2" placeholder="Pertanyaan" required></textarea>
    <input name="a" class="form-control mb-2" placeholder="Opsi A" required>
    <input name="b" class="form-control mb-2" placeholder="Opsi B" required>
    <input name="c" class="form-control mb-2" placeholder="Opsi C" required>
    <input name="d" class="form-control mb-2" placeholder="Opsi D" required>

    <select name="jawaban" class="form-control mb-3" required>
        <option value="">Jawaban Benar</option>
        <option value="A">A</option>
        <option value="B">B</option>
        <option value="C">C</option>
        <option value="D">D</option>
    </select>

    <button name="simpan" class="btn btn-success">Simpan Soal</button>
</form>
</div>

<!-- TABEL -->
<div class="card p-4">
<div class="table-responsive">
<table class="table table-bordered">
<thead>
<tr>
    <th width="60">No</th>
    <th>Pertanyaan</th>
    <th width="100">Jawaban</th>
    <th width="180">Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; while($s=mysqli_fetch_assoc($soal)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($s['pertanyaan']); ?></td>
    <td><b><?= $s['jawaban']; ?></b></td>
    <td>

        <button class="btn btn-warning btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#edit<?= $s['id']; ?>">
            Edit
        </button>

        <a href="?hapus=<?= $s['id']; ?>"
           onclick="return confirm('Hapus soal ini?')"
           class="btn btn-danger btn-sm">
            Hapus
        </a>

    </td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="edit<?= $s['id']; ?>" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<form method="POST">
<div class="modal-header">
    <h5 class="modal-title">Edit Soal</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">

    <input type="hidden" name="id" value="<?= $s['id']; ?>">

    <textarea name="pertanyaan" class="form-control mb-2" required><?= htmlspecialchars($s['pertanyaan']); ?></textarea>
    <input name="a" class="form-control mb-2" value="<?= htmlspecialchars($s['opsi_a']); ?>" required>
    <input name="b" class="form-control mb-2" value="<?= htmlspecialchars($s['opsi_b']); ?>" required>
    <input name="c" class="form-control mb-2" value="<?= htmlspecialchars($s['opsi_c']); ?>" required>
    <input name="d" class="form-control mb-2" value="<?= htmlspecialchars($s['opsi_d']); ?>" required>

    <select name="jawaban" class="form-control" required>
        <option value="A" <?= $s['jawaban']=='A'?'selected':''; ?>>A</option>
        <option value="B" <?= $s['jawaban']=='B'?'selected':''; ?>>B</option>
        <option value="C" <?= $s['jawaban']=='C'?'selected':''; ?>>C</option>
        <option value="D" <?= $s['jawaban']=='D'?'selected':''; ?>>D</option>
    </select>

</div>
<div class="modal-footer">
    <button name="update" class="btn btn-primary">Update</button>
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

</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
