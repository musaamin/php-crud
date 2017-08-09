<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi Procedural - Tambah Barang</title>
  </head>
  <body>
    <h1>Tambah Barang</h1>

    <?php
      // mulai session
      session_start();

      // inisialisasi variabel
      $kd_barang = "";
      $nama_barang = "";
      $harga = "";
      $stok = "";

      // jika ada pesan error tampilkan
      if(!empty($_SESSION['pesan'])){
        $kd_barang = stripslashes($_SESSION['kd_barang']);
        $nama_barang = stripslashes($_SESSION['nama_barang']);
        $harga = $_SESSION['harga'];
        $stok = $_SESSION['stok'];

        echo "<ul>";
        foreach ($_SESSION['pesan'] as $key => $value) {
           echo "<li>".$value."</li>";
        }
        echo "</ul>";
      }
    ?>

    <!-- form masukan data -->
    <form action="aksi-tambah.php" method="post">
      <table border="0">
        <tr>
          <td>Kode Barang</td>
          <td><input type="text" name="kd_barang" value="<?php echo $kd_barang; ?>" placeholder="Kode Barang" maxlength="4" required></td>
        </tr>
        <tr>
          <td>Nama Barang</td>
          <td><input type="text" name="nama_barang" value="<?php echo $nama_barang; ?>" placeholder="Nama Barang" required></td>
        </tr>
        <tr>
          <td>Harga</td>
          <td><input type="number" name="harga" value="<?php echo $harga; ?>" placeholder="Harga" required></td>
        </tr>
        <tr>
          <td>Stok</td>
          <td><input type="number" name="stok" value="<?php echo $stok; ?>" placeholder="Stok" required></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="btnSimpan" value="Simpan"></td>
        </tr>
      </table>
    </form>

  </body>
</html>

<?php
  // hapus session
  session_unset();
  session_destroy();
?>
