<?php
session_start();
include "configuration/config_etc.php";
include "configuration/config_include.php";
include 'configuration/config_connect.php';
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login Gagal</title>
  <link rel="stylesheet" type="text/css" href="\dist\css\style.css">
</head>
<body style="background: #325d75">
  <?php head(); ?>

  <div class="container" style="margin-top:100px; max-width:400px;">
    <div class="alert alert-danger text-center" role="alert" style="padding:30px; font-size:18px;">
      <strong>❌ Login Gagal!</strong><br>
      Username atau password salah, atau Anda bukan owner/kasir.<br><br>
      <a href="login.php" class="btn btn-primary">Kembali ke Halaman Login</a>
    </div>
  </div>

  <script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="dist/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
