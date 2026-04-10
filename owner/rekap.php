<?php
include "../config/auth.php";
include "../config/koneksi.php";
include "../config/helper.php";

if ($_SESSION['role'] != 'owner') { header("Location: ../auth/login.php"); exit; }

$tgl1 = $_GET['tgl1'] ?? '';
$tgl2 = $_GET['tgl2'] ?? '';

$query = "SELECT * FROM transaksi WHERE status='keluar'";
if (!empty($tgl1) && !empty($tgl2)) {
    $t1 = mysqli_real_escape_string($conn, $tgl1);
    $t2 = mysqli_real_escape_string($conn, $tgl2);
    $query .= " AND DATE(waktu_keluar) BETWEEN '$t1' AND '$t2'";
}
$query .= " ORDER BY waktu_keluar DESC";

$data  = mysqli_query($conn, $query);
$total = 0; $rows = [];
while ($d = mysqli_fetch_assoc($data)) { $rows[] = $d; $total += $d['biaya_total']; }

logAktivitas($conn, $_SESSION['id_user'], "Melihat rekap transaksi");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rekap Transaksi</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_owner.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Rekap Transaksi</h4>
    <p>Filter dan ekspor laporan pendapatan parkir</p>
  </div>

  <div class="card mb-4">
    <div class="card-title"><i class="fa-solid fa-filter"></i> Filter Periode</div>
    <form method="GET">
      <div class="row g-3">
        <div class="col-12 col-md-4">
          <label>Dari Tanggal</label>
          <input type="date" name="tgl1" value="<?= htmlspecialchars($tgl1) ?>" class="form-control">
        </div>
        <div class="col-12 col-md-4">
          <label>Sampai Tanggal</label>
          <input type="date" name="tgl2" value="<?= htmlspecialchars($tgl2) ?>" class="form-control">
        </div>
        <div class="col-12 col-md-4">
          <label>&nbsp;</label>
          <div class="d-flex gap-2 flex-wrap">
            <button type="submit" class="btn-primary-custom flex-grow-1">Filter</button>
            <a href="cetak_laporan.php?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>" class="btn-outline-custom">
              <i class="fa-solid fa-print"></i> Cetak
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-file-lines"></i> Data Transaksi</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Waktu Masuk</th><th>Waktu Keluar</th><th>Total Bayar</th></tr>
        </thead>
        <tbody>
          <?php if(count($rows) > 0): foreach($rows as $d): ?>
          <tr>
            <td><?= $d['id_parkir'] ?></td>
            <td style="font-size:12.5px;color:#64748b"><?= date('d M Y H:i', strtotime($d['waktu_masuk'])) ?></td>
            <td style="font-size:12.5px;color:#64748b"><?= date('d M Y H:i', strtotime($d['waktu_keluar'])) ?></td>
            <td><strong>Rp <?= number_format($d['biaya_total'],0,',','.') ?></strong></td>
          </tr>
          <?php endforeach; else: ?>
          <tr>
            <td colspan="4" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-file-circle-xmark" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data transaksi<?= ($tgl1 && $tgl2) ? ' pada periode ini' : '' ?>
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr style="background:#f8fafc">
            <td colspan="3" style="text-align:right;font-weight:600;padding:12px 14px">Total Pendapatan</td>
            <td style="font-weight:700;color:#16a34a;padding:12px 14px">Rp <?= number_format($total,0,',','.') ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const t1 = document.querySelector("input[name='tgl1']").value;
    const t2 = document.querySelector("input[name='tgl2']").value;
    if (t1 && t2 && t1 > t2) {
        alert("Tanggal awal tidak boleh lebih besar dari tanggal akhir!");
        e.preventDefault();
    }
});
</script>

</body>
</html>
