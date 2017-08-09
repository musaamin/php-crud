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
             value="<?php echo stripslashes($q); ?>">
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
          $query .= "WHERE kd_barang = '$q'";
        } else {
          $query .= "WHERE kd_barang LIKE '%$q%'";
        }

        // jalankan query
        $result = $koneksi->query($query);

        // pesan gagal melakukan query
        if(!$result){
          die ("PENCARIAN: Gagal melakukan Query SELECT. ".$koneksi->error);
        }

        // jumlah baris data yang dihasilkan dari query
        $jumlah = $result->num_rows;

        // jika ada data yang dicari
        if($jumlah>0){
          $no=1;
    ?>

          <p>Hasil pencarian : <strong><?php echo $jumlah; ?> barang</strong></p>
          <table border="1">
            <tr>
              <th>No.</th>
              <th>Kode</th>
              <th>Nama Barang</th>
              <th>Harga</th>
              <th>Stok</th>
            </tr>

            <?php while($r=mysqli_fetch_assoc($result)){ ?>

            <tr>
              <td><?php echo $no; ?></td>
              <td><?php echo $r['kd_barang']; ?></td>
              <td><?php echo $r['nama_barang']; ?></td>
              <td><?php echo $r['harga']; ?></td>
              <td><?php echo $r['stok']; ?></td>
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
            echo "<font color=\"red\"><p>Kode barang yang dicari tidak ditemukan</p></font>";
          }

          // kosongkan memori yang dipakai hasil query
          $result->close();

          // tutup koneksi database
          $koneksi->close();

        } // endif btnCari
      ?>

  </body>
</html>
