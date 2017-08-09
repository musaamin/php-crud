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
  $kd_barang = $_POST['kd_barang'];
  $nama_barang = $_POST['nama_barang'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  // simpan pesan error di variable session
  $_SESSION['pesan'] = array();

  if(!ctype_digit($harga)){
    $_SESSION['pesan'][] = "Harga harus angka.";
  }

  if(!ctype_digit($stok)){
    $_SESSION['pesan'][] = "Stok harus angka.";
  }

  // jika ada yang error redirect ke form-tambah
  if(!empty($_SESSION['pesan'])){
    header('location: form-ubah.php?kode='.$kd_barang);
    exit();
  }

  // prepared statement query
  $query = "UPDATE barang SET nama_barang = :nama_barang,
                              harga = :harga,
                              stok = :stok
            WHERE kd_barang = :kd_barang";
  $stmt = $koneksi->prepare($query);

  $stmt->bindParam(":kd_barang", $kd_barang, PDO::PARAM_STR);
  $stmt->bindParam(":nama_barang", $nama_barang, PDO::PARAM_STR);
  $stmt->bindParam(":harga", $harga, PDO::PARAM_INT);
  $stmt->bindParam(":stok", $stok, PDO::PARAM_INT);

  // jalankan query
  try {
    $stmt->execute();

    // jumlah baris data yang terkena query
    $jumlah = $stmt->rowCount();

    // barang tidak diubah
    if($jumlah===0){
      $pesan = "UBAH BARANG: Barang tidak diubah.";
    }

    // barang diubah
    else {
      $pesan = "UBAH BARANG: Barang berhasil diubah.";
    }
  }

  // pesan jika query gagal
  catch (PDOException $e){
    die ("UBAH BARANG: Gagal melakukan Query UPDATE. ".$e->getMessage());
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
