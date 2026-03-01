<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PMB Online - Universitas Nusantara</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root {
    --primary: #0E2148;
    --secondary: #483AA0;
    --accent: #7965C1;
    --gold: #E3D095;
    --light-bg: #f8f9fc;
}

/* NAVBAR */
.navbar {
    background: var(--primary) !important;
}

/* HERO */
.hero {
    min-height: 100vh;
    background: 
     linear-gradient(to left, rgba(14, 33, 72, 0.3) 45%, rgba(14,33,72,0.2)),
        url('../assets/img/mahasiswa2.jpg') ;
    background-size: cover;
    display: flex;
    align-items: center;
    color: white;
    padding-left: 80px;
}

.hero-content {
    max-width: 600px;
    margin-top: 80px;
}

.hero-content h1 {
    font-size: 40px;
    font-weight: 700;
}

.hero-content p {
    font-size: 18px;
    margin-top: 15px;
}

.hero .btn-warning {
    background: var(--gold);
    border: none;
    color: var(--primary);
    font-weight: 600;
    padding: 12px 35px;
    border-radius: 50px;
    transition: 0.3s;
}

.hero .btn-warning:hover {
    background: white;
    transform: scale(1.05);
}

/* SECTION TITLE */
.section-title {
    font-weight: 700;
    font-size: 32px;
    color: var(--primary);
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: "";
    width: 60px;
    height: 4px;
    background: var(--accent);
    display: block;
    margin: 10px auto 0;
    border-radius: 5px;
}

/* ABOUT */
.about-section {
    background: white;
    padding-top: 100px;  
    padding-bottom: 90px; 
}

.about-section .section-title {
    margin-top: 50px;   
    margin-bottom: 30px; 
}

.about-section p {
    margin-top: 20px; 
    font-size: 18px;
    line-height: 1.8;
}

/* PROGRAM STUDI */
.program-section {
    background: var(--light-bg);
}

/* CARD / ICON BOX */
.icon-box {
    padding: 40px 30px;
    border-radius: 15px;
    background: white;
    transition: 0.3s;
    box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    height: 100%;
}

.icon-box h5,
.icon-box h6 {
    font-weight: 600;
    color: var(--primary);
}

.icon-box:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(72,58,160,0.2);
}

/* ALUR */
.alur-section {
    background: #f4f6ff;
}

/* CTA */
.cta-section {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

.cta-section .btn-light {
    background: white;
    color: var(--primary);
    border-radius: 50px;
    padding: 12px 35px;
    font-weight: 600;
    transition: 0.3s;
}

.cta-section .btn-light:hover {
    background: var(--gold);
    transform: scale(1.05);
}

/* FOOTER */
.footer {
    background: var(--primary);
    color: white;
    padding: 20px 0;
    font-size: 14px;
}

/* Smooth scroll */
html {
    scroll-behavior: smooth;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
  <div class="container">
      <a class="navbar-brand fw-bold" href="#hero">PMB Online</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                  <a class="nav-link" href="#tentang">About</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link" href="#alur">Alur Pendaftaran</a>
              </li>
          </ul>
      </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero" id="hero">
    <div class="hero-content">
        <h1>Penerimaan Mahasiswa Baru 2026</h1>
        <p>Bergabunglah bersama Universitas Nusantara dan raih masa depan gemilang.</p>
        <a href="../auth/register.php" class="btn btn-warning btn-lg mt-3">
            Daftar Sekarang
        </a>
    </div>
</section>

<!-- TENTANG -->
<section class="about-section py-5" id="tentang">
<div class="container text-center">
    <h2 class="section-title mb-4">Tentang Kami</h2>
    <p class="text-muted col-md-8 mx-auto">
        Universitas Nusantara adalah institusi pendidikan tinggi yang berkomitmen
        mencetak lulusan unggul, profesional, dan berintegritas.
        Dengan fasilitas modern dan tenaga pengajar berpengalaman,
        kami siap mendukung kesuksesan akademik Anda.
    </p>
</div>
</section>

<!-- PROGRAM STUDI -->
<section class="program-section py-5">
<div class="container text-center">
    <h2 class="section-title mb-5">Program Studi</h2>

    <div class="row text-center">

        <div class="col-md-4 mb-4">
            <div class="icon-box">
                <h5>Teknik Informatika</h5>
                <p class="text-muted">Fokus pada pengembangan software dan AI.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="icon-box">
                <h5>Manajemen</h5>
                <p class="text-muted">Mencetak pemimpin bisnis profesional.</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="icon-box">
                <h5>Akuntansi</h5>
                <p class="text-muted">Spesialisasi keuangan & audit modern.</p>
            </div>
        </div>

    </div>
</div>
</section>

<!-- ALUR PENDAFTARAN -->
<section class="alur-section py-5" id="alur">
  <div class="container text-center">
    <h2 class="section-title mb-5">Alur Pendaftaran</h2>

    <div class="row g-4 justify-content-center">

      <div class="col-md-3">
        <div class="icon-box">
          <h6>1. Registrasi</h6>
          <p class="text-muted">Buat akun dan isi formulir online.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="icon-box">
          <h6>2. Tes Online</h6>
          <p class="text-muted">Ikuti tes seleksi secara daring.</p>
        </div>
      </div>

      <div class="col-md-3">
        <div class="icon-box">
          <h6>3. Pengumuman</h6>
          <p class="text-muted">Cek hasil kelulusan & daftar ulang.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section text-center py-5">
<div class="container">
    <h3>Siap Menjadi Bagian dari Kami?</h3>
    <a href="register.php" class="btn btn-light btn-lg mt-3">
        Daftar Sekarang
    </a>
</div>
</section>

<!-- FOOTER -->
<footer class="footer text-center">
<div class="container">
    <p class="mb-0">
        © 2026 Universitas Nusantara | PMB Online
    </p>
</div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
