<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>CRUD PHP MySQL dengan PDO - Pencarian</title>
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
        $q = $_GET['q'];
        $tipeFilter = $_GET['tipeFilter'];
        if($tipeFilter=="Where"){
          $optionWhere = "selected";
          $optionLike = "";
        } else {
          $optionWhere = "";
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
      if(isset($_GET['btnCari'])){
        if(!ctype_alnum($q)){
          die("<p>Kode Barang hanya boleh huruf dan angka.</p>");
        }

        // variabel query pilih semua data barang
        $query = "SELECT * FROM barang ";

        // filter data barang berdasarkan kd_barang yang dicari sama (Where = ) atau mirip (Like %%)
        if($_GET['tipeFilter']=="Where"){
          $query .= "WHERE kd_barang = :q";
          $stmt = $koneksi->prepare($query);
          $stmt->bindParam(":q", $q, PDO::PARAM_STR);
        } else {
          $query .= "WHERE kd_barang LIKE :like";
          $like = "%".$q."%";
          $stmt = $koneksi->prepare($query);
          $stmt->bindParam(":like", $like, PDO::PARAM_STR);
        }

        // lakukan query
        try {
          $stmt->execute();
        }

        // pesan error jika gagal query
        catch (PDOException $e) {
          die ("PENCARIAN: Gagal melakukan Query. ".$e->getMessage());
        }

        // jumlah baris data yang dihasilkan dari query
        $jumlah = $stmt->rowCount();

        // jika ditemukan hasil pencarian
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

            <?php
              // simpan hasil query ke dalam array asosiatif
              while($r=$stmt->fetch(PDO::FETCH_ASSOC)){
                extract($r);
            ?>

                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $kd_barang; ?></td>
                  <td><?php echo $nama_barang; ?></td>
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
            echo "<font color=\"red\"><p>Kode barang yang dicari tidak ditemukan</p></font>";
          }

          // kosongkan $stmt
          $stmt = null;

          // tutup koneksi database
          $koneksi = null;

        } // endif btnCari
      ?>

  </body>
</html>
