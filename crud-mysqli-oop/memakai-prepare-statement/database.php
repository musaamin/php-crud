<?php
// jika file diakses langsung
// redirect ke index.php
if (strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) == true){
  header('location: index.php');
  exit();
}

// akun login server dan nama database
$hostname = "localhost";
$username = "apps";
$password = "apps";
$database = "toko";

// login ke server
$koneksi=new mysqli($hostname, $username, $password, $database);

// tampilkan pesan jika gagal terhubung ke database
if(!$koneksi){
  die("Gagal terhubung ke database: ".$koneksi->connect_errno." - ".$koneksi->connect_error);
}
?>
