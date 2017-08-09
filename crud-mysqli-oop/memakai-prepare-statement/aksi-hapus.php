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
  $kode = $koneksi->real_escape_string($_GET['kode']);

  // query dan prepare statement hapus data
  $query = "DELETE FROM barang WHERE kd_barang = ?";
  $stmt = $koneksi->prepare($query);
  if($stmt===FALSE){
    die ("HAPUS BARANG: Prepare statement gagal. ".$koneksi->error);
  }

  // bind param, tanda ? diisi dengan $kode
  // s = string
  $stmt->bind_param('s', $kode);

  // eksekusi prepare statement
  $stmt->execute();

  // jumlah baris data yang terkena query
  $jumlah = $stmt->affected_rows;

  // tidak ada barang yang terhapus
  if($jumlah===0){
    $pesan = "HAPUS BARANG: Tidak ada barang yang dihapus.";
  }

  // ada barang yang terhapus
  else {
    $pesan = "HAPUS BARANG: Barang berhasil dihapus.";
  }

  // tutup prepare statement
  $stmt->close();

  // tutup koneksi database
  $koneksi->close();

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
