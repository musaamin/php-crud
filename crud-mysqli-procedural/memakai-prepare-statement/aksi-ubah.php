<?php
  // cek apakah tombol Simpan aktif
  // sudah mengisi form
  if(!isset($_POST['btnSimpan'])){
    header('location: form-ubah.php');
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

  // simpan pesan error di variable session
  $_SESSION['pesan'] = array();

  if(!ctype_digit($harga)){
    $_SESSION['pesan'][] = "Harga harus angka.";
  }

  if(!ctype_digit($stok)){
    $_SESSION['pesan'][] = "Stok harus angka.";
  }

  // jika ada yang error redirect ke form-ubah
  if(!empty($_SESSION['pesan'])){
    header('location: form-ubah.php?kode='.$kd_barang);
    exit();
  }

  // query dan prepare statement ubah data
  $query = "UPDATE barang SET nama_barang = ?,
                              harga = ?,
                              stok = ?
            WHERE kd_barang = ?";
  $stmt = mysqli_prepare($koneksi, $query);
  if($stmt===FALSE){
    die ("UBAH BARANG: Prepare statement pengecekan Kode Barang gagal. ".mysqli_error($koneksi));
  }

  // bind param, tanda ? diisi dengan variabel lokal
  // s = string
  // i = integer
  mysqli_stmt_bind_param($stmt, 'siis', $nama_barang, $harga, $stok, $kd_barang);

  // eksekusi prepare statement
  mysqli_stmt_execute($stmt);

  // jumlah baris data yang terkena perintah query
  $jumlah = mysqli_stmt_affected_rows($stmt);

  // barang tidak diubah
  if($jumlah===0){
    $pesan = "UBAH BARANG: Barang tidak diubah.";
  }

  // barang diubah
  else {
    $pesan = "UBAH BARANG: Barang berhasil diubah.";
  }

  // tutup prepare statement
  mysqli_stmt_close($stmt);

  // tutup koneksi database
  mysqli_close($koneksi);

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
