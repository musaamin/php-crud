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
  $kd_barang = $koneksi->real_escape_string($_POST['kd_barang']);
  $nama_barang = $koneksi->real_escape_string($_POST['nama_barang']);
  $harga = $koneksi->real_escape_string($_POST['harga']);
  $stok = $koneksi->real_escape_string($_POST['stok']);

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
  $stmt = $koneksi->prepare($query);
  if($stmt===FALSE){
    die ("UBAH BARANG: Prepare pengecekan Kode Barang gagal. ".$koneksi->error);
  }

  // bind param, tanda ? diisi dengan variabel lokal
  // s = string
  // i = integer
  $stmt->bind_param('siis', $nama_barang, $harga, $stok, $kd_barang);

  // eksekusi prepare statement
  $stmt->execute();

  // jumlah baris data yang terkena perintah query
  $jumlah = $stmt->affected_rows;

  // barang tidak diubah
  if($jumlah===0){
    $pesan = "UBAH BARANG: Barang tidak diubah.";
  }

  // barang diubah
  else {
    $pesan = "UBAH BARANG: Barang berhasil diubah.";
  }

  // tutup prepare statement
  $stmt->close();

  // tutup koneksi database
  $koneksi->close();

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
