<?php
  // cek apakah kode ada isinya
  if(!isset($_GET['kode'])){
    $pesan = "UBAH BARANG: Kode Barang belum dipilih";
    header('location: index.php?pesan='.$pesan);
    exit();
  }

  // cek apakah kode barang yang dimasukkan hanya huruf dan angka
  if(!ctype_alnum($_GET['kode'])){
    die("UBAH BARANG: Kode Barang hanya boleh huruf dan angka");
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi OOP - Ubah Barang</title>
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

      // query dan prepare statement pemilihan data barang yang akan diubah
      $query = "SELECT * FROM barang WHERE kd_barang = ?";
      $stmt = $koneksi->prepare($query);
      if($stmt===FALSE){
        die("UBAH BARANG: Prepare statement gagal. ".$koneksi->error);
      }

      // bind param, tanda ? diisi dengan $kode
      // s = string
      $stmt->bind_param('s', $kode);

      // eksekusi prepapre statement
      $stmt->execute();

      // simpan result set dari prepare statement
      $stmt->store_result();

      // binding kolom tabel ke variabel
      $stmt->bind_result($kode, $nama, $harga, $stok);

      // jumlah baris data yang dihasilkan dari query
      $jumlah = $stmt->num_rows();

      // jika kode barang tidak ada di tabel
      if($jumlah===0){
        die("UBAH BARANG: Kode Barang tidak ditemukan.");
      }

      // ambil hasil query dari variabel proses binding
      $r=$stmt->fetch();
    ?>

    <!-- form masukan data -->
    <form action="aksi-ubah.php" method="post">
      <input type="hidden" name="kd_barang" value="<?php echo $kode; ?>">
      <table border="0">
        <tr>
          <td>Kode Barang</td>
          <td><strong><?php echo $kode; ?></strong></td>
        </tr>
        <tr>
          <td>Nama Barang</td>
          <td><input type="text" name="nama_barang" value="<?php echo $nama; ?>" placeholder="Nama Barang" required></td>
        </tr>
        <tr>
          <td>Harga Barang</td>
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
  // kosongkan memori yang dipakai hasil query
  $stmt->free_result();

  // tutup prepare statement
  $stmt->close();

  // tutup koneksi database
  $koneksi->close();
?>
