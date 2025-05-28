<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "configuration/config_etc.php";
include "configuration/config_include.php";
include 'configuration/config_connect.php';

// Pastikan koneksi berhasil
if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
}

// Bagian ini sepertinya untuk mengambil nama footer.
$queryback = "SELECT * FROM data";
$resultback = mysqli_query($conn, $queryback);
if ($resultback) { // Tambahkan pengecekan hasil query
  $rowback = mysqli_fetch_assoc($resultback);
  $footer = isset($rowback['nama']) ? $rowback['nama'] : 'Default Footer Name'; // Tambahkan fallback
} else {
  $footer = 'Default Footer Name'; // Fallback jika query gagal
}

// Pastikan fungsi connect() dan timing() terdefinisi dan dipanggil dengan benar
// Fungsi ini dipanggil dari config_include.php atau config_etc.php.
// Pastikan tidak ada duplikasi atau masalah di dalamnya.
connect();
timing();
?>

<!DOCTYPE html>
<html>

<head>
  <br>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="\dist\css\style.css">
</head>

<body style="background: #325d75">
  <?php head(); ?>

  <body class="hold-transition login-page">
    <?php
    $username_input = ""; // Ganti nama variabel agar lebih jelas
    $password_input_raw = ""; // Ganti nama variabel agar lebih jelas
    $tabeldatabase = "user"; // tabel database

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $username_input = mysqli_real_escape_string($conn, $_POST['txtuser']);
      $password_input_raw = mysqli_real_escape_string($conn, $_POST['txtpass']);
      // Debug HASHING
      // echo sha1(md5($password_input_raw));
      // exit();
      // Hashing password sesuai dengan yang ada di DB Anda (SHA1(MD5(pass)))
      $hashed_password_for_query = sha1(md5($password_input_raw));

      // --- AWAL DEBUGGING: INFORMASI INPUT & HASH ---
      echo "<pre style='background-color: #e0f2f7; color: #0366d6; border: 1px solid #c8e6f1; padding: 10px; margin-top: 20px; text-align: left;'>";
      echo "<strong>DEBUGGING INFO (Input Form):</strong>\n";
      echo "Username dari Form (mentah): '" . htmlspecialchars($username_input) . "'\n";
      echo "Password dari Form (mentah): '" . sha1(md5($password_input_raw)) . "'\n";
      echo "Password di-hash untuk Query (sha1(md5(pass))): '" . sha1(md5($hashed_password_for_query)) . "'\n";
      echo "</pre>";
      // --- AKHIR DEBUGGING ---

      // Cek ke tabel user
      // Menggunakan fungsi LOWER() di SQL untuk perbandingan username yang tidak peka huruf besar/kecil
      $sql = "SELECT * FROM $tabeldatabase WHERE username = '$username_input' AND password='$hashed_password_for_query'";
      $hasil = mysqli_query($conn, $sql);

      if (!$hasil) {
        die("Query error tabel user: " . mysqli_error($conn));
      }

      // --- AWAL DEBUGGING: HASIL QUERY USER ---
      echo "DEcode : " . htmlspecialchars_decode($hashed_password_for_query);
      echo "<pre style='background-color: #fce8e8; color: #dc3545; border: 1px solid #fccfd1; padding: 10px; margin-top: 10px; text-align: left;'>";
      echo "<strong>DEBUGGING INFO (Hasil Query User):</strong>\n";
      echo "Query SQL User: " . htmlspecialchars($sql) . "\n";
      echo "Jumlah baris ditemukan di tabel user: " . mysqli_num_rows($hasil) . "\n";
      echo "</pre>";
      // --- AKHIR DEBUGGING ---

      if (mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_assoc($hasil);

        // --- AWAL DEBUGGING: DATA USER DITEMUKAN ---
        echo "<pre style='background-color: #e6ffed; color: #28a745; border: 1px solid #d4edda; padding: 10px; margin-top: 10px; text-align: left;'>";
        echo "<strong>DEBUGGING INFO (Data User Ditemukan):</strong>\n";
        echo "Data dari DB (User):\n";
        print_r($data);
        echo "Jabatan mentah dari DB: '" . htmlspecialchars($data['jabatan']) . "'\n";
        $processedJabatan = strtolower(trim($data['jabatan']));
        echo "Jabatan setelah trim dan strtolower: '" . htmlspecialchars($processedJabatan) . "'\n";
        $allowed_roles = ['admin', 'kasir'];
        echo "Jabatan yang diizinkan: " . htmlspecialchars(implode(', ', $allowed_roles)) . "\n";
        $isJabatanValid = in_array($processedJabatan, $allowed_roles);
        // echo "Apakah jabatan valid ('admin' atau 'kasir')? " . ($isJabatanValid ? 'Ya' : 'Tidak') . "\n";
        echo "</pre>";
        // --- AKHIR DEBUGGING ---

        if ($isJabatanValid) {
          $_SESSION['username'] = $data['username'];
          $_SESSION['nama'] = $data['nama'];
          $_SESSION['jabatan'] = $data['jabatan'];
          $_SESSION['avatar'] = $data['avatar'];
          $_SESSION['nouser'] = $data['no'];
          $_SESSION['baseurl'] = $baseurl;
          login_validate();
          header("Location: index");
          exit();
        } else {
          session_destroy();
          header("Location: loginagain.php");
          exit();
        }
      } else {
        // Jika user tidak ditemukan di tabel 'user' sama sekali
        session_destroy();
        header("Location: loginagain.php");
        exit();
      }
    }
    ?>

    <div class="container">
      <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="login-box">
          <div class="login-logo">
            <a href=""><img src="img/1000076586-removebg-preview.png" width="170" alt="" srcset=""></a>
          </div>
          <div class="login-box-body">
            <form action="login.php" method="post">
              <div class="form-group has-feedback">
                <input type="text" class="form-control" name="txtuser" placeholder="Username" maxlength="255" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" class="form-control" name="txtpass" placeholder="Password" maxlength="255" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                <div class="col-xs-12" align="right">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Masuk</button>
                </div>
              </div>
            </form>
            <br>
            <p class="login-box-msg">Copyright © <span id="year"></span> MC.Barkel<br /> Coffe Patani</p>
          </div>
        </div>
      </div>
    </div>

    <script src="dist/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
      $.widget.bridge('uibutton', $.ui.button);
      const yearElement = document.getElementById("year");
      const currentYear = new Date().getFullYear();
      if (yearElement) {
        yearElement.textContent = currentYear;
      }
    </script>
    <script src="dist/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="dist/plugins/morris/morris.min.js"></script>
    <script src="dist/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script src="dist/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="dist/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="dist/plugins/knob/jquery.knob.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="dist/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="dist/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="dist/plugins/fastclick/fastclick.js"></script>
    <script src="dist/js/app.min.js"></script>
    <script src="dist/js/pages/dashboard.js"></script>
    <script src="dist/js/demo.js"></script>
    <script src="dist/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="dist/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="dist/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="dist/plugins/fastclick/fastclick.js"></script>
  </body>

</html>