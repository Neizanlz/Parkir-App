<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('admin');

function logAktivitas($conn, $id_user, $aktivitas) {
    mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aktivitas, waktu_aktivitas) VALUES ('$id_user', '$aktivitas', NOW())");
}

if (isset($_POST['simpan'])) {
    $plat    = mysqli_real_escape_string($conn, $_POST['plat_nomor']);
    $jenis   = mysqli_real_escape_string($conn, $_POST['jenis_kendaraan']);
    $warna   = mysqli_real_escape_string($conn, $_POST['warna']);
    $pemilik = mysqli_real_escape_string($conn, $_POST['pemilik']);
    $id_user = (int)$_SESSION['id_user'];
    mysqli_query($conn, "INSERT INTO kendaraan (plat_nomor, jenis_kendaraan, warna, pemilik, id_user) VALUES ('$plat','$jenis','$warna','$pemilik','$id_user')");
    logAktivitas($conn, $_SESSION['id_user'], "Menambah kendaraan $plat");
    header("Location: kendaraan.php?sukses=tambah");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kendaraan WHERE id_kendaraan=$id");
    logAktivitas($conn, $_SESSION['id_user'], "Menghapus kendaraan ID $id");
}

$data = mysqli_query($conn, "
    SELECT * FROM kendaraan
    WHERE id_kendaraan NOT IN (
        SELECT id_kendaraan FROM transaksi
    )
    ORDER BY id_kendaraan DESC
");

$badge = ['motor' => 'badge-green', 'mobil' => 'badge-blue', 'lainnya' => 'badge-violet'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kendaraan</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Kendaraan</h4>
    <p>Kelola data kendaraan terdaftar</p>
  </div>

  <div class="card mb-4">
    <div class="card-title"><i class="fa-solid fa-plus"></i> Tambah Kendaraan</div>
    <form method="POST">
      <?php if(isset($_GET['sukses'])): ?>
      <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Kendaraan berhasil ditambahkan.</div>
      <?php endif; ?>
      <div class="row g-3">
        <div class="col-12 col-md-3">
          <label>Plat Nomor</label>
          <input type="text" name="plat_nomor" class="form-control" required>
        </div>
        <div class="col-12 col-md-3">
          <label>Jenis Kendaraan</label>
          <select name="jenis_kendaraan" class="form-select" required>
            <option value="motor">Motor</option>
            <option value="mobil">Mobil</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
        <div class="col-12 col-md-3">
          <label>Warna</label>
          <input type="text" name="warna" class="form-control">
        </div>
        <div class="col-12 col-md-3">
          <label>Pemilik</label>
          <input type="text" name="pemilik" class="form-control">
        </div>
        <div class="col-12">
          <button name="simpan" class="btn-primary-custom">Simpan</button>
        </div>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-car"></i> Daftar Kendaraan</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Plat</th><th>Jenis</th><th>Warna</th><th>Pemilik</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) == 0): ?>
          <tr>
            <td colspan="6" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-car" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data kendaraan
            </td>
          </tr>
          <?php else: while($d = mysqli_fetch_assoc($data)): $cls = $badge[$d['jenis_kendaraan']] ?? 'badge-gray'; ?>
          <tr>
            <td><?= $d['id_kendaraan'] ?></td>
            <td><strong><?= $d['plat_nomor'] ?></strong></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($d['jenis_kendaraan']) ?></span></td>
            <td><?= $d['warna'] ?></td>
            <td><?= $d['pemilik'] ?></td>
            <td>
              <a href="?hapus=<?= $d['id_kendaraan'] ?>" class="btn-danger-custom"
                 onclick="return confirm('Hapus kendaraan ini?')">
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
