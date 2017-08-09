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

      // query barang
      $query = "SELECT * FROM barang";
      $result = mysqli_query($koneksi, $query);

      // pesan error jika gagal query
      if(!$result){
        die ("TAMPIL BARANG: Gagal melakukan Query SELECT. ".mysqli_error($koneksi));
      }

      // jumlah baris data yang dihasilkan dari query
      $jumlah = mysqli_num_rows($result);

      // tampil pesan jika tidak ada barang
      if($jumlah===0){
        echo "TAMPIL BARANG: Barang sedang kosong.";
      }

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

        // simpan hasil query ke dalam array asosiatif
        while($r=mysqli_fetch_assoc($result)){
      ?>

          <tr>
            <td align="right"><?php echo $no; ?></td>
            <td align="center"><?php echo $r['kd_barang']; ?></td>
            <td><?php echo $r['nama_barang']; ?></td>
            <td align="right"><?php echo $r['harga']; ?></td>
            <td align="right"><?php echo $r['stok']; ?></td>
            <td><a href="detail.php?kode=<?php echo $r['kd_barang']; ?>">Detail</a> |
                <a href="form-ubah.php?kode=<?php echo $r['kd_barang']; ?>">Ubah</a> |
                <a href="aksi-hapus.php?kode=<?php echo $r['kd_barang']; ?>"
                   onclick="return confirm('Hapus data ini?');">Hapus</a></td>
          </tr>

      <?php
          $no++;
        }
      ?>
    </table>

    <?php } //end else ada data ?>

  </body>
</html>

<?php
  // kosongkan memori yang dipakai hasil query
  mysqli_free_result($result);

  // tutup koneksi database
  mysqli_close($koneksi);
?>
