<?php
include "../config/auth.php";
include "../config/koneksi.php";

if ($_SESSION['role'] != 'admin') { header("Location: ../auth/login.php"); exit; }

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_area']);
    $kap  = (int)$_POST['kapasitas'];
    mysqli_query($conn, "INSERT INTO area_parkir (nama_area, kapasitas, terisi) VALUES ('$nama','$kap',0)");
    header("Location: area.php?sukses=tambah");
    exit;
}
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM area_parkir WHERE id_area=".(int)$_GET['hapus']);
}

$data = mysqli_query($conn, "SELECT * FROM area_parkir ORDER BY id_area DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Area Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Area Parkir</h4>
    <p>Kelola area dan kapasitas parkir</p>
  </div>

  <div class="card mb-4">
    <div class="card-title"><i class="fa-solid fa-plus"></i> Tambah Area</div>
    <form method="POST">
      <?php if(isset($_GET['sukses'])): ?>
      <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Area parkir berhasil ditambahkan.</div>
      <?php endif; ?>
      <div class="row g-3">
        <div class="col-12 col-md-5">
          <label>Nama Area</label>
          <input type="text" name="nama_area" class="form-control" required>
        </div>
        <div class="col-12 col-md-4">
          <label>Kapasitas</label>
          <input type="number" name="kapasitas" class="form-control" required>
        </div>
        <div class="col-12 col-md-3">
          <label>&nbsp;</label>
          <button name="simpan" class="btn-primary-custom w-100">Simpan</button>
        </div>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-map-location-dot"></i> Daftar Area</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Nama Area</th><th>Kapasitas</th><th>Terisi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) == 0): ?>
          <tr>
            <td colspan="5" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-map-location-dot" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data area parkir
            </td>
          </tr>
          <?php else: while($d = mysqli_fetch_assoc($data)): ?>
          <tr>
            <td><?= $d['id_area'] ?></td>
            <td><?= $d['nama_area'] ?></td>
            <td><?= $d['kapasitas'] ?></td>
            <td><?= $d['terisi'] ?></td>
            <td>
              <a href="?hapus=<?= $d['id_area'] ?>" class="btn-danger-custom"
                 onclick="return confirm('Hapus area ini?')">
                <i class="fa-solid fa-trash-can"></i> Hapus
              </a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
