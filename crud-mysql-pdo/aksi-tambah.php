<?php
  // cek apakah tombol Simpan aktif
  // sudah mengisi form
  if(!isset($_POST['btnSimpan'])){
    header('location: form-tambah.php');
    exit();
  }

  // mulai session
  session_start();

  // panggil koneksi database
  require "database.php";

  // variabel superglobal diubah menjadi variabel lokal
  $kd_barang = $_POST['kd_barang'];
  $nama_barang = $_POST['nama_barang'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  // cek apakah kd_barang sudah ada sebelumnya
  $query = "SELECT kd_barang FROM barang WHERE kd_barang = :kode";
  $stmt = $koneksi->prepare($query);
  $stmt->bindParam(":kode", $kd_barang, PDO::PARAM_STR);

  // lakukan query
  try {
    $stmt->execute();
  }

  // pesan error jika gagal query
  catch (PDOException $e) {
    die ("TAMBAH BARANG: Query pengecekan Kode Barang gagal. ".$e->getMessage());
  }

  // jumlah baris data yang dihasilkan dari query
  $jumlah = $stmt->rowCount();

  // simpan pesan error di variable session
  $_SESSION['pesan'] = array();

  if($jumlah>0){
    $_SESSION['pesan'][] = "Kode Barang sudah ada.";
  }

  if(!ctype_alnum($kd_barang)){
    $_SESSION['pesan'][] = "Kode Barang hanya boleh huruf dan angka.";
  }

  if(!ctype_digit($harga)){
    $_SESSION['pesan'][] = "Harga harus angka.";
  }

  if(!ctype_digit($stok)){
    $_SESSION['pesan'][] = "Stok harus angka.";
  }

  // jika ada yang error redirect ke form-tambah
  if(!empty($_SESSION['pesan'])){
    $_SESSION['kd_barang'] = $kd_barang;
    $_SESSION['nama_barang'] = $nama_barang;
    $_SESSION['harga'] = $harga;
    $_SESSION['stok'] = $stok;

    header('location: form-tambah.php');
    exit();
  }

  // prepared statement query
  $query = "INSERT INTO barang (kd_barang, nama_barang, harga, stok)
                             VALUES (:kd_barang, :nama_barang, :harga, :stok)";
  $stmt = $koneksi->prepare($query);

  $stmt->bindParam(":kd_barang", $kd_barang, PDO::PARAM_STR);
  $stmt->bindParam(":nama_barang", $nama_barang, PDO::PARAM_STR);
  $stmt->bindParam(":harga", $harga, PDO::PARAM_INT);
  $stmt->bindParam(":stok", $stok, PDO::PARAM_INT);

  // jalankan query
  try {
    $stmt->execute();
    $pesan = "TAMBAH BARANG: Barang berhasil ditambah.";
  }

  // pesan jika query gagal
  catch (PDOException $e){
    $pesan = "TAMBAH BARANG: Gagal melakukan Query INSERT. ".$e->getMessage();
  }

  finally {
    // kosongkan prepare statement
    $stmt = null;

    // tutup koneksi database
    $koneksi = null;

    // redirect ke index.php disertai pesan
    header('location: index.php?pesan='.$pesan);
  }
?>
