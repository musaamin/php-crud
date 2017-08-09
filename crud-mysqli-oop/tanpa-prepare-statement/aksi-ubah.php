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

  // query ubah data
  $query = "UPDATE barang SET nama_barang = '$nama_barang',
                              harga = '$harga',
                              stok = '$stok'
            WHERE kd_barang = '$kd_barang'";
  $result = $koneksi->query($query);

  // pesan jika query sukses atau gagal
  if(!$result){
    die("UBAH BARANG: Gagal melakukan Query UPDATE. ".$koneksi->error);
  }

  // jumlah baris data yang terkena perintah query
  $jumlah = $koneksi->affected_rows;

  // data tidak diubah
  if($jumlah===0){
    $pesan = "UBAH BARANG: Barang tidak diubah.";
  }

  // data diubah
  else {
    $pesan = "UBAH BARANG: Barang berhasil diubah.";
  }

  // tutup koneksi database
  $koneksi->close();

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
