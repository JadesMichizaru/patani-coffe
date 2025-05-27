<?php
error_reporting(0);
session_start();
include "configuration/config_etc.php";
include "configuration/config_include.php";
include "configuration/config_connect.php";
connect(); timing();

$username = $password = "";

$tabeldatabase = "user";
$forward = mysqli_real_escape_string($conn, $tabeldatabase);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['txtuser']);
    $password = mysqli_real_escape_string($conn, $_POST['txtpass']);
    $password = sha1(md5($password)); // Ubah sesuai metode yang dipakai saat menyimpan di database

    $sql = "SELECT * FROM $forward WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($conn, $sql);

    if (mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_assoc($hasil);
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['avatar'] = $data['avatar'];
        $_SESSION['nouser'] = $data['no'];
        $_SESSION['baseurl'] = $baseurl;
        login_validate();
        header("Location: index");
    } else {
        header("Location: loginagain");
    }
}
?>
