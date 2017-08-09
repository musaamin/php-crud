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
  $kd_barang = mysqli_real_escape_string($koneksi, $_POST['kd_barang']);
  $nama_barang = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
  $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
  $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);

  // query dan prepare statement pengecekan kd_barang apakah sudah ada sebelumnya
  $query = "SELECT kd_barang FROM barang WHERE kd_barang = ?";
  $stmt = mysqli_prepare($koneksi, $query);
  if($stmt===FALSE){
    die ("TAMBAH BARANG: Prepare statement pengecekan Kode Barang gagal. ".mysqli_error($koneksi));
  }

  // bind param, tanda ? diisi dengan $kd_barang
  // s = string
  mysqli_stmt_bind_param($stmt, 's', $kd_barang);

  // eksekusi prepare statement
  mysqli_stmt_execute($stmt);

  // simpan result set dari prepare statement
  mysqli_stmt_store_result($stmt);

  // jumlah baris data yang dihasilkan dari query
  $jumlah = mysqli_stmt_num_rows($stmt);

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

  // query dan prepare statement tambah data
  $query = "INSERT INTO barang (kd_barang, nama_barang, harga, stok)
            VALUES (?, ?, ?, ?)";
  $stmt = mysqli_prepare($koneksi, $query);

  if($stmt===FALSE){
    die("TAMBAH BARANG: Prepare statement tambah data gagal. ".mysqli_error($koneksi));
  }

  // bind param, tanda ? diisi dengan variabel lokal
  // s = string
  // i = integer
  mysqli_stmt_bind_param($stmt, 'ssii', $kd_barang, $nama_barang, $harga, $stok);

  // eksekusi prepare statement
  mysqli_stmt_execute($stmt);

  // tutup prepare statement
  mysqli_stmt_close($stmt);

  // tutup koneksi database
  mysqli_close($koneksi);

  // redirect ke index.php disertai pesan
  $pesan = "TAMBAH BARANG: Barang berhasil ditambah.";
  header('location: index.php?pesan='.$pesan);
?>
