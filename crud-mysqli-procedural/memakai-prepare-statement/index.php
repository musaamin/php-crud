<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi Procedural</title>
  </head>
  <body>
    <h1>CRUD PHP MySQLi Procedural</h1>
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

      // query dan prepare statement
      $query = "SELECT * FROM barang";
      $stmt = mysqli_prepare($koneksi, $query);
      if($stmt===FALSE){
        die("TAMPIL BARANG: Prepare statement gagal. ".mysqli_error($koneksi));
      }

      // eksekusi prepare statement
      mysqli_stmt_execute($stmt);

      // simpan result set dari prepare statement
      mysqli_stmt_store_result($stmt);

      // binding kolom tabel ke variabel
      mysqli_stmt_bind_result($stmt, $kode, $nama, $harga, $stok);

      // tampil pesan jika tidak ada barang
      $jumlah = mysqli_stmt_num_rows($stmt);
      if($jumlah===0){
        echo "TAMPIL BARANG: Barang sedang kosong.";
      }

      // jika ada barang
      else {
        echo "<p>Jumlah : <strong>".$jumlah." barang</strong></p>";
    ?>

    <table border="1">
      <tr>
        <th>No.</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>

      <?php
        // variabel nomor urut
        $no=1;

        // ambil hasil query dari variabel proses binding
        while($r=mysqli_stmt_fetch($stmt)){
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
        } //endwhile
      ?>
    </table>

      <?php } //end else ada data ?>

  </body>
</html>

<?php
  // kosongkan memori yang dipakai hasil query
  mysqli_stmt_free_result($stmt);

  // tutup prepare statement
  mysqli_stmt_close($stmt);

  // tutup koneksi database
  mysqli_close($koneksi);
?>
