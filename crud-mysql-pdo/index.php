<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQL dengan PDO</title>
  </head>
  <body>
    <h1>CRUD PHP MySQL dengan PDO</h1>
    <h3>Toko Komputer LAKUMI</h3>

    <p>
      <button type="button" name="btnTambah" onclick="location.href='form-tambah.php'">Tambah</button>
      <button type="button" name="btnRefresh" onclick="location.href='index.php'">Refresh</button>
      <button type="button" name="btnCari" onclick="location.href='cari.php'">Pencarian</button>
    </p>

    <?php
      // pesan setelah tambah/ubah/hapus
      // atau ada error
      if(isset($_GET['pesan'])){
        echo "<p><font color=\"red\">".$_GET['pesan']."</font></p>";
      }

      // panggil koneksi database
      require "database.php";

      // prepared statement query
      $query = "SELECT * FROM barang";
      $stmt = $koneksi->prepare($query);

      // lakukan query
      try {
        $stmt->execute();
      }

      // pesan error jika gagal query
      catch (PDOException $e) {
        die ("TAMPIL BARANG: Gagal melakukan Query. ".$e->getMessage());
      }

      // jumlah baris data hasil query
      $jumlah = $stmt->rowCount();

      // tidak ada data
      if($jumlah===0){
        echo "<p>TAMPIL BARANG: Barang sedang kosong.</p>";
      }

      // ada data
      else {
        echo "<p>Jumlah : <strong>".$jumlah." barang</strong></p>";
    ?>

        <table border="1">
          <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
          </tr>

    <?php
        // variabel nomor urut
        $no=1;

        // simpan hasil query ke dalam array asosiatif
        while($r=$stmt->fetch(PDO::FETCH_ASSOC)){
          extract($r);
    ?>
          <tr>
            <td align="right"><?php echo $no; ?></td>
            <td align="center"><?php echo $kd_barang; ?></td>
            <td><?php echo $nama_barang; ?></td>
            <td align="right"><?php echo $harga; ?></td>
            <td align="right"><?php echo $stok; ?></td>
            <td><a href="detail.php?kode=<?php echo $kd_barang; ?>">Detail</a> |
                <a href="form-ubah.php?kode=<?php echo $kd_barang; ?>">Ubah</a> |
                <a href="aksi-hapus.php?kode=<?php echo $kd_barang; ?>"
                   onclick="return confirm('Hapus data ini?');">Hapus</a></td>
          </tr>

      <?php
          $no++;
        } // endwhile
      ?>
    </table>

    <?php  } // endelse ada barang ?>

  </body>
</html>

<?php
  // kosongkan prepare statement
  $stmt = null;

  // tutup koneksi database
  $koneksi = null;
?>
