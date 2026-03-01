<?php
session_start();

// Hapus semua data session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman index.php
header("Location: ../index.php");
exit();
?>
