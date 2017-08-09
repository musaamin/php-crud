<?php
  // cek apakah kode ada isinya
  if(!isset($_GET['kode'])){
    die("HAPUS BARANG: Kode Barang belum dipilih");
  }

  // cek kode yang dimasukkan, hanya boleh huruf dan angka
  if(!ctype_alnum($_GET['kode'])){
    die("HAPUS BARANG: Kode Barang hanya boleh huruf dan angka");
  }

  // panggil koneksi database
  require "database.php";

  // variabel superglobal kode diubah menjadi variabel lokal
  $kode = $koneksi->real_escape_string($_GET['kode']);

  // query hapus data
  $query = "DELETE FROM barang WHERE kd_barang = '$kode'";
  $result = $koneksi->query($query);

  // pesan jika query sukses atau gagal
  if(!$result){
    die("HAPUS BARANG: Gagal melakukan Query DELETE. ".$koneksi->error);
  }

  // jumlah baris data yang terkena perintah query
  $jumlah = $koneksi->affected_rows;

  // tidak ada data yang dihapus
  if($jumlah===0){
    $pesan = "HAPUS BARANG: Tidak ada barang yang dihapus.";
  }

  // data dihapus
  else {
    $pesan = "HAPUS BARANG: Barang berhasil dihapus.";
  }

  // tutup koneksi database
  $koneksi->close();

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
