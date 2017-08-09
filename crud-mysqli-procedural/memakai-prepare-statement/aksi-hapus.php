<?php
  // cek apakah ada kode barang yang dipilih
  if(!isset($_GET['kode'])){
    die("HAPUS BARANG: Kode Barang belum dipilih");
  }

  // cek apakah kode barang yang dimasukkan hanya huruf dan angka
  if(!ctype_alnum($_GET['kode'])){
    die("HAPUS BARANG: Kode Barang hanya boleh huruf dan angka");
  }

  // panggil koneksi database
  require "database.php";

  // variabel superglobal kode diubah menjadi variabel lokal
  $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

  // query dan prepare statement
  $query = "DELETE FROM barang WHERE kd_barang = ?";
  $stmt = mysqli_prepare($koneksi, $query);
  if($stmt===FALSE){
    die ("HAPUS BARANG: Prepare statement gagal. ".mysqli_error($koneksi));
  }

  // bind param, tanda ? diisi dengan $kode
  // s = string
  mysqli_stmt_bind_param($stmt, 's', $kode);

  // eksekusi prepare statement
  mysqli_stmt_execute($stmt);

  // jumlah baris data yang terkena perintah query
  $jumlah = mysqli_stmt_affected_rows($stmt);

  // tidak ada data yang dihapus
  if($jumlah===0){
    $pesan = "HAPUS BARANG: Tidak ada barang yang dihapus.";
  }

  // data dihapus
  else {
    $pesan = "HAPUS BARANG: Barang berhasil dihapus.";
  }

  // tutup prepare statement
  mysqli_stmt_close($stmt);

  // tutup koneksi database
  mysqli_close($koneksi);

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
