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
  $kd_barang = $koneksi->real_escape_string($_POST['kd_barang']);
  $nama_barang = $koneksi->real_escape_string($_POST['nama_barang']);
  $harga = $koneksi->real_escape_string($_POST['harga']);
  $stok = $koneksi->real_escape_string($_POST['stok']);

  // cek apakah kd_barang sudah ada sebelumnya
  $query = "SELECT kd_barang FROM barang WHERE kd_barang = '$kd_barang'";
  $result = $koneksi->query($query);

  // pesan jika query berhasil atau gagal
  if(!$result){
    die ("TAMBAH BARANG: Query pengecekan Kode Barang gagal. ".$koneksi->error);
  }

  // jumlah baris data yang dihasilkan dari query
  $jumlah = $result->num_rows;

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

  // query tambah data
  $query = "INSERT INTO barang (kd_barang, nama_barang, harga, stok)
            VALUES ('$kd_barang', '$nama_barang', '$harga', '$stok')";
  $result = $koneksi->query($query);

  // pesan jika query gagal
  if(!$result){
    die("TAMBAH BARANG: Gagal melakukan Query INSERT. ".$koneksi->error);
  }

  // tutup koneksi database
  $koneksi->close();

  // redirect ke index.php disertai pesan
  $pesan = "TAMBAH BARANG: Barang berhasil ditambah";
  header('location: index.php?pesan='.$pesan);
?>
