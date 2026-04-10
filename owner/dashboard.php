<?php
include "../config/auth.php";
include "../config/koneksi.php";
include "../config/helper.php";

if ($_SESSION['role'] != 'owner') { header("Location: ../auth/login.php"); exit; }

$total = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(biaya_total) as total FROM transaksi WHERE status='keluar'"));

$hari_ini = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(biaya_total) as total FROM transaksi WHERE status='keluar' AND DATE(waktu_keluar)=CURDATE()"));

$jumlah = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total FROM transaksi WHERE status='keluar'"));

$persen = ($total['total'] ?? 0) > 0
    ? round(($hari_ini['total'] / $total['total']) * 100, 1)
    : 0;

// 7 hari terakhir
$labels = []; $chartData = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $row  = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT SUM(biaya_total) as total FROM transaksi WHERE status='keluar' AND DATE(waktu_keluar)='$date'"));
    $labels[]    = date('d/m', strtotime("-$i days"));
    $chartData[] = (int)($row['total'] ?? 0);
}

logAktivitas($conn, $_SESSION['id_user'], "Membuka dashboard owner");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Owner</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include "sidebar_owner.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Halo, <?= $_SESSION['nama'] ?> </h4>
    <p>Ringkasan performa bisnis parkir</p>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-wallet"></i></div>
        <div>
          <div class="stat-label">Total Pendapatan</div>
          <div class="stat-value" style="font-size:20px">Rp <?= number_format($total['total'] ?? 0,0,',','.') ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-calendar-day"></i></div>
        <div>
          <div class="stat-label">Pendapatan Hari Ini</div>
          <div class="stat-value" style="font-size:20px">Rp <?= number_format($hari_ini['total'] ?? 0,0,',','.') ?></div>
          <div style="font-size:11.5px;color:#94a3b8;margin-top:2px"><?= $persen ?>% dari total</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon violet"><i class="fa-solid fa-receipt"></i></div>
        <div>
          <div class="stat-label">Transaksi Selesai</div>
          <div class="stat-value"><?= $jumlah['total'] ?? 0 ?></div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-chart-line"></i> Pendapatan 7 Hari Terakhir</div>
    <canvas id="lineChart" height="80"></canvas>
  </div>

  <div class="mt-3">
    <a href="rekap.php" class="btn-primary-custom" style="text-decoration:none">
      <i class="fa-solid fa-file-lines"></i> Lihat Rekap Detail
    </a>
  </div>
</div>

<script>
new Chart(document.getElementById('lineChart'), {
  type: 'line',
  data: {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      data: <?= json_encode($chartData) ?>,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.07)',
      borderWidth: 2.5,
      pointBackgroundColor: '#3b82f6',
      pointRadius: 4,
      tension: 0.4,
      fill: true
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: {
      x: { grid: { display: false }, ticks: { font: { size: 12 } } },
      y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 12 } } }
    }
  }
});
</script>

</body>
</html>
