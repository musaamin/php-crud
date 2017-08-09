<?php
  // cek apakah kode ada isinya
  if(!isset($_GET['kode'])){
    die("DETAIL BARANG: Kode Barang belum dipilih.");
  }

  // cek kode yang dimasukkan, hanya boleh huruf dan angka
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

      // prepare statement
      $query = "SELECT * FROM barang WHERE kd_barang = ?";
      $stmt = $koneksi->prepare($query);
      if($stmt===FALSE){
        die("DETAIL BARANG: Prepare statement gagal. ".$koneksi->error);
      }

      // bind param, tanda ? diisi dengan $kode
      // s = string
      mysqli_stmt_bind_param($stmt, 's', $kode);

      // eksekusi prepare statement
      mysqli_stmt_execute($stmt);

      // simpan result set dari prepare statement
      $stmt->store_result();

      // binding kolom tabel ke variabel
      $stmt->bind_result($kode, $nama, $harga, $stok);

      // tampil pesan jika tidak ada barang
      $jumlah = $stmt->num_rows;
      if($jumlah===0){
        echo "DETAIL BARANG: Kode Barang tidak ditemukan.";
      }

      else {

      // ambil hasil query dari variabel proses binding
      $r = $stmt->fetch();
    ?>

    <p>
      <a href="form-ubah.php?kode=<?php echo $kode; ?>">Ubah</a> |
      <a href="aksi-hapus.php?kode=<?php echo $kode; ?>" onclick="return confirm('Hapus data ini?');">Hapus</a>
    </p>

    <table border="1">
      <tr>
        <th>Kode Barang</th>
        <td><?php echo $kode; ?></td>
      </tr>
      <tr>
        <th>Nama Barang</th>
        <td><?php echo $nama; ?></td>
      </tr>
      <tr>
        <th>Harga Barang</th>
        <td><?php echo $harga; ?></td>
      </tr>
      <tr>
        <th>Stok</th>
        <td><?php echo $stok; ?></td>
      </tr>
    </table>

    <?php } //endelse ada data  ?>

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
