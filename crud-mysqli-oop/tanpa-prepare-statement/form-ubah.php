<?php
  // cek apakah ada kode barang yang dipilih
  if(!isset($_GET['kode'])){
    $pesan = "UBAH BARANG: Kode Barang belum dipilih";
    header('location: index.php?pesan='.$pesan);
    exit();
  }

  if(!ctype_alnum($_GET['kode'])){
    die("UBAH BARANG: Kode Barang hanya boleh huruf dan angka");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi Procedural - Ubah Barang</title>
  </head>
  <body>
    <h3>Ubah Barang</h3>

    <?php
      // mulai session
      session_start();

      // jika ada pesan error tampilkan
      if(!empty($_SESSION['pesan'])){
        echo "<ul>";
        foreach ($_SESSION['pesan'] as $key => $value) {
           echo "<li>".$value."</li>";
        }
        echo "</ul>";

        session_unset();
        session_destroy();
      }

      // panggil koneksi database
      require "database.php";

      // variabel superglobal kode diubah menjadi variabel lokal
      $kode = $koneksi->real_escape_string($_GET['kode']);

      // query pemilihan data barang yang akan diubah
      $query = "SELECT * FROM barang WHERE kd_barang = '$kode'";
      $result = $koneksi->query($query);

      // pesan error jika gagal query
      if(!$result){
        echo "UBAH BARANG: Gagal melakukan Query ".$koneksi->error;
      }

      // jumlah baris data yang dihasilkan dari query
      $jumlah = $result->num_rows;

      // jika kode barang tidak ada di tabel
      if($jumlah===0){
        die("UBAH BARANG: Kode Barang tidak ditemukan.");
      }

      // simpan hasil query ke dalam array asosiatif
      $r=$result->fetch_assoc();
    ?>

    <!-- form masukan data -->
    <form action="aksi-ubah.php" method="post">
      <input type="hidden" name="kd_barang" value="<?php echo $r['kd_barang']; ?>">
      <table border="0">
        <tr>
          <td>Kode Barang</td>
          <td><strong><?php echo $r['kd_barang']; ?></strong></td>
        </tr>
        <tr>
          <td>Nama Barang</td>
          <td><input type="text" name="nama_barang" value="<?php echo $r['nama_barang']; ?>" placeholder="Nama Barang" required></td>
        </tr>
        <tr>
          <td>Harga Barang</td>
          <td><input type="number" name="harga" value="<?php echo $r['harga']; ?>" placeholder="Harga" required></td>
        </tr>
        <tr>
          <td>Stok</td>
          <td><input type="number" name="stok" value="<?php echo $r['stok']; ?>" placeholder="Stok" required></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="btnSimpan" value="Simpan"></td>
        </tr>
      </table>
    </form>
  </body>
</html>

<?php
  // kosongkan memori yang dipakai hasil query
  $result->close();

  // tutup koneksi database
  $koneksi->close();
?>
