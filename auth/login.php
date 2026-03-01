<?php
session_start();
require_once "../config/database.php"; // pastikan di database.php juga variabelnya $koneksi



if(isset($_POST['login'])){

    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // =========================
    // LOGIN ADMIN HARD CODE
    // =========================
    if($email === "admin1@gmail.com" && $password === "8000"){
        $_SESSION['user_id']    = 0;
        $_SESSION['email']      = $email;
        $_SESSION['role']       = "admin";
        $_SESSION['nomor_tes']  = "PMB-ADMIN";
        header("Location: ../dashboard/data_mahasiswa.php");
        exit;
    }

    // =========================
    // LOGIN USER DARI DATABASE
    // =========================
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $data  = mysqli_fetch_assoc($query);

    if($data && password_verify($password, $data['password'])){

        // Generate nomor tes jika belum ada
        if(empty($data['nomor_tes'])){
            $nomor_tes = "PMB".date("Y").rand(1000,9999);
            mysqli_query($koneksi, "
                UPDATE users 
                SET nomor_tes='$nomor_tes' 
                WHERE id='".$data['id']."'
            ");
            $data['nomor_tes'] = $nomor_tes;
        }

        // Simpan session
        $_SESSION['user_id']    = $data['id'];
        $_SESSION['email']      = $data['email'];
        $_SESSION['role']       = $data['role'];
        $_SESSION['nomor_tes']  = $data['nomor_tes'];

        // Redirect sesuai role
        if($data['role'] === "admin"){
            header("Location: ../dashboard/admin.php");
        } else {
            header("Location: ../dashboard/user.php");
        }
        exit;

    } else {
        $error = "Email atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
html, body{ height:100%; }
body{ background: linear-gradient(120deg,#6a11cb,#8e7cff); }
.left-panel{ color: #fff; padding: 80px; }
.form-card{
    background: #fff;
    border-radius: 18px;
    padding: 40px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 25px 50px rgba(0,0,0,.25);
    transform: translateX(-40px);
}
.form-control{ border-radius: 12px; padding: 12px; }
.btn-primary{ background:#6a5af9; border:none; border-radius:12px; padding:12px; }
.btn-primary:hover{ background:#5a4de0; }
</style>
</head>

<body>
<div class="container-fluid h-100">
    <div class="row h-100">

        <!-- PANEL KIRI -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center left-panel">
            <h1 class="fw-bold">Welcome Back</h1>
            <p class="mt-3 fs-5">
                Login menggunakan email dan password yang telah dibuat
            </p>
        </div>

        <!-- PANEL KANAN -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="form-card">

                <h4 class="text-center fw-bold mb-2">Login PMB</h4>
                <p class="text-center text-muted mb-4">
                    Masukkan email dan password
                </p>

                <?php if(isset($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="Email" required>
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password"
                               class="form-control"
                               placeholder="Password" required>
                    </div>

                    <button type="submit" name="login"
                            class="btn btn-primary w-100">
                        Login
                    </button>
                </form>

                <p class="text-center mt-3">
                    Belum punya akun?
                    <a href="register.php" class="text-decoration-none">Daftar</a>
                </p>

            </div>
        </div>

    </div>
</div>
</body>
</html>
