<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQLi OOP - Pencarian</title>
  </head>
  <body>
    <h3>Pencarian Barang</h3>

    <?php
      // panggil koneksi database
      require "database.php";

      //insialisasi variabel
      $q = "";
      $optionWhere = "";
      $optionLike = "";

      // jika btnCari aktif
      if(isset($_GET['btnCari'])){
        $q = $koneksi->real_escape_string($_GET['q']);
        $tipeFilter = $_GET['tipeFilter'];
        if($tipeFilter=="Where"){
          $optionWhere = "selected";
        } else {
          $optionLike = "selected";
        }
      }
    ?>

    <!-- form pencarian -->
    <form action="" method="get">
      <input type="text"
             name="q"
             placeholder="Cari Kode Barang"
             value="<?php echo stripslashes($q); ?>"
             required>
      <select name="tipeFilter">
        <option value="Where" <?php echo $optionWhere; ?>>Where</option>
        <option value="Like" <?php echo $optionLike; ?>>Like</option>
      </select>
      <input type="submit" name="btnCari" value="Cari">
    </form>
    <br>

    <?php
      // jika tombol Cari aktif
      if(isset($_GET['btnCari'])){
        if(!ctype_alnum($q)){
          die("<p>Kode Barang hanya boleh huruf dan angka.</p>");
        }

        // variabel query pilih semua data barang
        $query = "SELECT * FROM barang ";

        // filter data barang berdasarkan kd_barang yang dicari sama (Where = ) atau mirip (Like %%)
        if($_GET['tipeFilter']=="Where"){
          $query .= "WHERE kd_barang = ?";
        } else {
          $q = "%".$q."%";
          $query .= "WHERE kd_barang LIKE ?";
        }

        // prepare statement
        $stmt = $koneksi->prepare($query);
        if($stmt===FALSE){
          die("PENCARIAN: Prepare statement gagal. ".$koneksi->error);
        }

        // bind param, tanda ? diisi dengan $q
        // s = string
        $stmt->bind_param('s', $q);

        // eksekusi prepare statement
        $stmt->execute();

        // simpan result set dari prepare statement
        $stmt->store_result();

        // binding kolom tabel ke variabel
        $stmt->bind_result($kode, $nama, $harga, $stok);

        // jumlah baris data yang dihasilkan dari query
        $jumlah = $stmt->num_rows();

        // jika ada data yang dicari
        if($jumlah>0){
          $no=1;
    ?>
          <p>Hasil pencarian: <strong><?php echo $jumlah; ?> barang</strong></p>
          <table border="1">
            <tr>
              <th>No.</th>
              <th>Kode</th>
              <th>Nama Barang</th>
              <th>Harga</th>
              <th>Stok</th>
            </tr>

            <?php
            // ambil hasil query dari variabel proses binding
              while($r=$stmt->fetch()){
            ?>

                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $kode; ?></td>
                  <td><?php echo $nama; ?></td>
                  <td><?php echo $harga; ?></td>
                  <td><?php echo $stok; ?></td>
                </tr>

            <?php
                $no++;
              } //endwhile
            ?>
          </table>

      <?php
          } // endif jumlah > 0

          // jika hasil pencarian 0
          else {
            echo "<font color=\"red\"><p>Kode Barang yang dicari tidak ditemukan</p></font>";
          }

          // kosongkan memori yang dipakai hasil query
          $stmt->free_result();

          // tutup prepare statement
          $stmt->close();

          // tutup koneksi database
          $koneksi->close();

        } // endif btnCari
      ?>

  </body>
</html>
