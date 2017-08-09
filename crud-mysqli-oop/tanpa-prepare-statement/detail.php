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
    <title>CRUD PHP MySQLi OOP - Detail</title>
  </head>
  <body>
    <h3>Detail Barang</h3>

    <?php
      // panggil koneksi database
      require "database.php";

      // variabel kode superglobal diubah menjadi lokal
      $kode = $koneksi->real_escape_string($_GET['kode']);

      // query memilih data
      $query = "SELECT * FROM barang WHERE kd_barang = '$kode'";
      $result = $koneksi->query($query);

      // pesan error jika gagal query
      if(!$result){
        die ("DETAIL BARANG: Gagal melakukan Query ".$koneksi->error);
      }

      // jumlah baris data yang dihasilkan dari query
      $jumlah = $result->num_rows;

      // jika kode barang tidak ada di tabel
      if($jumlah===0){
        echo "DETAIL BARANG: Kode Barang tidak ditemukan.";
      }

      else {

      // simpan hasil query ke dalam array asosiatif
      $r = $result->fetch_assoc();
    ?>

    <p>
      <a href="form-ubah.php?kode=<?php echo $kode; ?>">Ubah</a> |
      <a href="aksi-hapus.php?kode=<?php echo $kode; ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
    </p>

    <table border="1">
      <tr>
        <th>Kode Barang</th>
        <td><?php echo $r['kd_barang']; ?></td>
      </tr>
      <tr>
        <th>Nama Barang</th>
        <td><?php echo $r['nama_barang']; ?></td>
      </tr>
      <tr>
        <th>Harga Barang</th>
        <td><?php echo $r['harga']; ?></td>
      </tr>
      <tr>
        <th>Stok</th>
        <td><?php echo $r['stok']; ?></td>
      </tr>
    </table>

    <?php } //end else  ?>

  </body>
</html>

<?php
  // kosongkan memori yang dipakai hasil query
  $result->close();

  // tutup koneksi database
  $koneksi->close();
?>
