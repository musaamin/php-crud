<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi OOP</title>
  </head>
  <body>
    <h1>CRUD PHP MySQLi OOP</h1>
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

      // prepare statement
      $query = "SELECT * FROM barang";
      $stmt = $koneksi->prepare($query);
      if($stmt===FALSE){
        die("TAMPIL BARANG: Prepare statement gagal. ".$koneksi->error);
      }

      // eksekusi prepare statement
      $stmt->execute();

      // simpan result set dari prepare statement
      $stmt->store_result();

      // binding kolom tabel ke variabel
      $stmt->bind_result($kode, $nama, $harga, $stok);

      // tampil pesan jika tidak ada barang
      $jumlah = $stmt->num_rows;
      if($jumlah===0){
        echo "TAMPIL BARANG: Barang sedang kosong.";
      }

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
        //variabel nomor urut
        $no=1;

        // ambil hasil query dari variabel proses binding
        while($r=$stmt->fetch()){
      ?>

          <tr>
            <td align="right"><?php echo $no; ?></td>
            <td align="center"><?php echo $kode; ?></td>
            <td><?php echo $nama; ?></td>
            <td align="right"><?php echo $harga; ?></td>
            <td align="right"><?php echo $stok; ?></td>
            <td><a href="detail.php?kode=<?php echo $kode; ?>">Detail</a> |
                <a href="form-ubah.php?kode=<?php echo $kode; ?>">Ubah</a> |
                <a href="aksi-hapus.php?kode=<?php echo $kode; ?>"
                   onclick="return confirm('Hapus data ini?');">Hapus</a></td>
          </tr>

      <?php
          $no++;
        }
      ?>

    </table>
      <?php } // endelse ada data ?>
  </body>
</html>

<?php
  // kosongkan memori yang dipakai hasil query
  $stmt->free_result();

  // tutup prepare statement
  $stmt->close();

  // tutup koneksi database
  $koneksi->close();
?>
