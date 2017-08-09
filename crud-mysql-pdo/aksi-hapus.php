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
  $kode = $_GET['kode'];

  // query hapus data
  $query = "DELETE FROM barang WHERE kd_barang = :kode";
  $stmt = $koneksi->prepare($query);
  $stmt->bindParam(":kode", $kode, PDO::PARAM_STR);

  // eksekusi prepare statement
  try {
    $stmt->execute();

    // jumlah baris data yang terkena query
    $jumlah = $stmt->rowCount();

    // tidak ada barang terhapus
    if($jumlah===0){
      $pesan = "HAPUS BARANG: Tidak ada barang yang dihapus.";
    }

    // ada barang yang terhapus
    else {
      $pesan = "HAPUS BARANG: Barang berhasil dihapus.";
    }
  }

  // esksekusi prepare statement gagal
  catch (PDOException $e){
    $pesan = "HAPUS BARANG: Gagal melakukan Query DELETE. ".$e->getMessage();
  }

  finally {
    // kosongkan prepare statement
    $stmt = null;

    // tutup koneksi database
    $koneksi = null;

    // redirect ke index.php disertai pesan
    header('location: index.php?pesan='.$pesan);
  }
?>
