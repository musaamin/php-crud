<?php
  // cek apakah ada kode barang yang dipilih
  if(!isset($_GET['kode'])){
    die("DETAIL BARANG: Kode Barang belum dipilih.");
  }

  // cek apakah kode barang yang dimasukkan hanya huruf dan angka
  if(!ctype_alnum($_GET['kode'])){
    die("DETAIL BARANG: Kode Barang hanya boleh huruf dan angka.");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQL dengan PDO - Detail</title>
  </head>
  <body>
    <h3>Detail Barang</h3>

    <?php
      // panggil koneksi database
      require "database.php";

      // variabel kode superglobal diubah menjadi lokal
      $kode = $_GET['kode'];

      // prepared statement query
      $query = "SELECT * FROM barang WHERE kd_barang = :kode";
      $stmt = $koneksi->prepare($query);
      $stmt->bindParam(":kode", $kode, PDO::PARAM_STR);

      // lakukan query
      try {
        $stmt->execute();
      }

      // pesan error jika gagal query
      catch (PDOException $e) {
        die ("DETAIL BARANG: Gagal melakukan Query SELECT. ".$e->getMessage());
      }

      // cek apakah barang yang mau ditampilkan tersedia
      $jumlah = $stmt->rowCount();
      if($jumlah===0){
        echo "DETAIL BARANG: Kode Barang tidak ditemukan.";
      }

      else {
        // simpan hasil query ke dalam array asosiatif
        $r=$stmt->fetch(PDO::FETCH_ASSOC);
        extract($r);
    ?>

    <p>
      <a href="form-ubah.php?kode=<?php echo $kode; ?>">Ubah</a> |
      <a href="aksi-hapus.php?kode=<?php echo $kode; ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
    </p>

    <table border="1">
      <tr>
        <th>Kode Barang</th>
        <td><?php echo $kd_barang; ?></td>
      </tr>
      <tr>
        <th>Nama Barang</th>
        <td><?php echo $nama_barang; ?></td>
      </tr>
      <tr>
        <th>Harga Barang</th>
        <td><?php echo $harga; ?></td>
      </tr>
      <tr>
        <th>Stok</th>
        <td><?php echo $r['stok']; ?></td>
      </tr>
    </table>

    <?php
      } // end else

    // kosongkan prepare statement
    $stmt = null;

    // tutup koneksi database
    $koneksi = null;
    ?>

  </body>
</html>
