<?php
require_once "../config/database.php";




if(isset($_POST['register'])){

    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password_input = $_POST['password'];

    // Cek email sudah ada atau belum
    $check = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {

        // Hash password
        $password_hash = password_hash($password_input, PASSWORD_DEFAULT);

        // Simpan juga password asli ke password_plain
        $query = mysqli_query($koneksi, "
            INSERT INTO users 
            (email, password, password_plain, role) 
            VALUES 
            ('$email', '$password_hash', '$password_input', 'user')
        ");

        if($query){
            echo "<script>
                    window.location='../auth/login.php';
                  </script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register PMB</title>

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
            <h1 class="fw-bold">Hey, Hello!</h1>
            <p class="mt-3 fs-5">
                Daftar sekarang untuk membuat akun PMB.
                Nomor tes akan diberikan setelah login pertama.
            </p>
        </div>

        <!-- PANEL KANAN -->
        <div class="col-md-6 d-flex align-items-center justify-content-center">
            <div class="form-card">

                <h4 class="text-center fw-bold mb-2">Daftar Akun PMB</h4>
                <p class="text-center text-muted mb-4">
                    Silakan registrasi untuk membuat akun
                </p>

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

                    <button type="submit" name="register"
                            class="btn btn-primary w-100">
                        Register
                    </button>
                </form>

                <p class="text-center mt-3">
                    Sudah punya akun?
                    <a href="../auth/login.php" class="text-decoration-none">Login</a>
                </p>

            </div>
        </div>

    </div>
</div>
</body>
</html>
