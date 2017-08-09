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
  $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

  // query hapus data
  $query = "DELETE FROM barang WHERE kd_barang = '$kode'";
  $result = mysqli_query($koneksi, $query);

  // pesan jika query gagal
  if(!$result){
    die("HAPUS BARANG: Gagal melakukan Query DELETE. ".mysqli_error($koneksi));
  }

  // jumlah baris data yang terkena perintah query
  $jumlah = mysqli_affected_rows($koneksi);

  // tidak ada data yang dihapus
  if($jumlah===0){
    $pesan = "HAPUS BARANG: Tidak ada barang yang dihapus.";
  }

  // data dihapus
  else {
    $pesan = "HAPUS BARANG: Barang berhasil dihapus.";
  }

  // tutup koneksi database
  mysqli_close($koneksi);

  // redirect ke index.php disertai pesan
  header('location: index.php?pesan='.$pesan);
?>
