<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('admin');

function logAktivitas($conn, $id_user, $aktivitas) {
    mysqli_query($conn, "INSERT INTO log_aktivitas (id_user, aktivitas, waktu_aktivitas) VALUES ('$id_user', '$aktivitas', NOW())");
}

if (isset($_POST['simpan'])) {
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_kendaraan']);
    $tarif = (int)str_replace('.', '', $_POST['tarif_per_jam_raw'] ?? $_POST['tarif_per_jam']);
    mysqli_query($conn, "INSERT INTO tarif (jenis_kendaraan, tarif_per_jam) VALUES ('$jenis','$tarif')");
    logAktivitas($conn, $_SESSION['id_user'], "Menambah tarif $jenis sebesar $tarif");
    header("Location: tarif.php?sukses=tambah");
    exit;
}
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tarif WHERE id_tarif=$id");
    logAktivitas($conn, $_SESSION['id_user'], "Menghapus tarif ID $id");
}

$data = mysqli_query($conn, "SELECT * FROM tarif ORDER BY id_tarif DESC");
$badge = ['motor' => 'badge-green', 'mobil' => 'badge-blue', 'lainnya' => 'badge-violet'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tarif Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Tarif Parkir</h4>
    <p>Atur tarif per jam berdasarkan jenis kendaraan</p>
  </div>

  <div class="card mb-4">
    <div class="card-title"><i class="fa-solid fa-plus"></i> Tambah Tarif</div>
    <form method="POST">
      <?php if(isset($_GET['sukses'])): ?>
      <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Tarif berhasil ditambahkan.</div>
      <?php endif; ?>
      <div class="row g-3">
        <div class="col-12 col-md-5">
          <label>Jenis Kendaraan</label>
          <select name="jenis_kendaraan" class="form-select" required>
            <option value="motor">Motor</option>
            <option value="mobil">Mobil</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
        <div class="col-12 col-md-4">
          <label>Tarif per Jam (Rp)</label>
          <div style="position:relative">
            <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#64748b;font-size:13.5px;font-weight:500">Rp</span>
            <input type="text" name="tarif_per_jam" id="tarif_input" class="form-control" style="padding-left:36px" required>
            <input type="hidden" name="tarif_per_jam_raw" id="tarif_raw">
          </div>
        </div>
        <div class="col-12 col-md-3">
          <label>&nbsp;</label>
          <button name="simpan" class="btn-primary-custom w-100">Simpan</button>
        </div>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-tags"></i> Daftar Tarif</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Jenis</th><th>Tarif / Jam</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) == 0): ?>
          <tr>
            <td colspan="4" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-tags" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data tarif
            </td>
          </tr>
          <?php else: while($d = mysqli_fetch_assoc($data)): $cls = $badge[$d['jenis_kendaraan']] ?? 'badge-gray'; ?>
          <tr>
            <td><?= $d['id_tarif'] ?></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($d['jenis_kendaraan']) ?></span></td>
            <td><strong>Rp <?= number_format($d['tarif_per_jam'],0,',','.') ?></strong></td>
            <td>
              <a href="?hapus=<?= $d['id_tarif'] ?>" class="btn-danger-custom"
                 onclick="return confirm('Hapus tarif ini?')">
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
<script>
const tarifInput = document.getElementById('tarif_input');
const tarifRaw   = document.getElementById('tarif_raw');

tarifInput.addEventListener('input', function() {
    const raw = this.value.replace(/\./g, '').replace(/\D/g, '');
    tarifRaw.value = raw;
    this.value = raw ? parseInt(raw).toLocaleString('id-ID') : '';
});

tarifInput.closest('form').addEventListener('submit', function() {
    tarifInput.disabled = true;
});
</script>
</html>
