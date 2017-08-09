<?php
// jika file diakses langsung
// redirect ke index.php
if (strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) !== FALSE){
  header('location: index.php');
  exit();
}

// akun login server dan nama database MySQL/MariaDB yang digunakan
$hostname = "localhost";
$username = "apps";
$password = "apps";
$database = "toko";

// login ke server
$koneksi=mysqli_connect($hostname, $username, $password, $database);

// tampilkan pesan jika gagal terhubung ke database
if(!$koneksi){
  die("Gagal terhubung ke database: ".mysqli_connect_errno()." - ".mysqli_connect_error());
}
?>
