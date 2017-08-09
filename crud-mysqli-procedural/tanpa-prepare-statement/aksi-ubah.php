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

  // query ubah data
  $query = "UPDATE barang SET nama_barang = '$nama_barang',
                              harga = '$harga',
                              stok = '$stok'
            WHERE kd_barang = '$kd_barang'";
  $result = mysqli_query($koneksi, $query);

  // pesan jika query gagal
  if(!$result){
    die("UBAH BARANG: Gagal melakukan Query UPDATE. ".mysqli_error($koneksi));
  }

  // jumlah baris data yang terkena perintah query
  $jumlah = mysqli_affected_rows($koneksi);

  // data tidak diubah
  if($jumlah===0){
    $pesan = "UBAH BARANG: Barang tidak diubah.";
  }

  // data diubah
  else {
    $pesan = "UBAH BARANG: Barang berhasil diubah.";
  }

  // tutup koneksi database
  mysqli_close($koneksi);

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
