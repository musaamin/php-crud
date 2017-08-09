<?php
// jika file diakses langsung
// redirect ke index.php
if (strpos($_SERVER['REQUEST_URI'], basename(__FILE__)) == true){
  header('location: index.php');
  exit();
}

// akun login server dan nama database yang digunakan
$hostname = "localhost";
$username = "apps";
$password = "apps";
$database = "toko";

// login ke server
try {
    $koneksi = new PDO("mysql:host={$hostname};dbname={$database}", $username, $password);
    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

// tampilkan pesan jika gagal terhubung ke database
catch(PDOException $e){
    die("Gagal terhubung ke database: " . $e->getMessage());
}
?>
