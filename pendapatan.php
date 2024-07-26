<?php
require 'cek-sesi.php';
require 'koneksi.php';

// Proses pemasukan jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sumber = $_POST['id_sumber'];
    $jumlah = $_POST['jumlah'];
    $tgl_pemasukan = $_POST['tgl_pemasukan'];

    if (!empty($id_sumber) && !empty($jumlah) && !empty($tgl_pemasukan)) {
        $sql = "INSERT INTO pemasukan (id_sumber, jumlah, tgl_pemasukan) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('iis', $id_sumber, $jumlah, $tgl_pemasukan);

        if ($stmt->execute()) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?status=sukses');
            exit;
        } else {
            $error = "Gagal menambah pemasukan: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $error = "Data tidak lengkap!";
    }
}

require ('sidebar.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Dashboard - Admin</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <!-- Main Content -->
  <div id="content">
    <?php require ('navbar.php'); ?>

    <!-- Begin Page Content -->
    <div class="container-fluid">
      <!-- Content Row -->
      <div class="row">
        <!-- Form Input Pemasukan -->
        <div class="col-lg-6 mb-4">
          <!-- Input Card Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Input Pemasukan</h6>
            </div>
            <div class="card-body">
              <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>
              <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                  <label for="id_sumber">Sumber Pemasukan</label>
                  <select class="form-control" id="id_sumber" name="id_sumber" required>
                    <?php
                    $sumber_query = mysqli_query($koneksi, "SELECT * FROM sumber");
                    while ($sumber = mysqli_fetch_assoc($sumber_query)) {
                      echo "<option value='{$sumber['id_sumber']}'>{$sumber['nama']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="jumlah">Jumlah Pemasukan</label>
                  <input type="number" class="form-control" id="jumlah" name="jumlah" required>
                </div>
                <div class="form-group">
                  <label for="tgl_pemasukan">Tanggal Pemasukan</label>
                  <input type="date" class="form-control" id="tgl_pemasukan" name="tgl_pemasukan" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah Pemasukan</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Tampilkan Pemasukan -->
        <div class="col-lg-6 mb-4">
          <!-- Tampilkan Card Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Daftar Pemasukan</h6>
            </div>
            <div class="card-body">
              <?php
              $pemasukan_query = "SELECT p.id_pemasukan, s.nama AS sumber, p.jumlah, p.tgl_pemasukan 
                                  FROM pemasukan p
                                  JOIN sumber s ON p.id_sumber = s.id_sumber";
              $result = mysqli_query($koneksi, $pemasukan_query);

              if (mysqli_num_rows($result) > 0) {
                echo "<table class='table table-bordered'>";
                echo "<thead><tr><th>ID</th><th>Sumber</th><th>Jumlah</th><th>Tanggal</th></tr></thead>";
                echo "<tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                  echo "<tr><td>{$row['id_pemasukan']}</td><td>{$row['sumber']}</td><td>Rp. ".number_format($row['jumlah'], 2, ',', '.')."</td><td>{$row['tgl_pemasukan']}</td></tr>";
                }
                echo "</tbody></table>";
              } else {
                echo "Tidak ada data pemasukan.";
              }

              mysqli_close($koneksi);
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
</body>
</html>
