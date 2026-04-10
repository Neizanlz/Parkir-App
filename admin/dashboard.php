<?php
include "../config/auth.php";
include "../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$total_user       = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user"));
$total_kendaraan  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE status='masuk'"));
$total_transaksi  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi"));
$total_area       = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM area_parkir"));
$transaksi_hari_ini = mysqli_num_rows(mysqli_query($conn,
    "SELECT * FROM transaksi WHERE DATE(waktu_masuk)=CURDATE()"
));

// Transaksi 7 hari terakhir untuk line chart
$chart_labels = [];
$chart_data   = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $label = date('d/m', strtotime("-$i days"));
    $count = mysqli_num_rows(mysqli_query($conn,
        "SELECT * FROM transaksi WHERE DATE(waktu_masuk)='$date'"
    ));
    $chart_labels[] = $label;
    $chart_data[]   = $count;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
  .section-card { background:#fff; border-radius:16px; padding:28px; box-shadow:0 2px 12px rgba(0,0,0,0.06); height:100%; }
  .section-card .card-heading { font-size:14px; font-weight:600; color:#0f172a; margin-bottom:20px; display:flex; align-items:center; gap:8px; }
  .section-card .card-heading i { color:#3b82f6; }
  .today-badge { display:inline-flex; align-items:center; gap:8px; background:#eff6ff; color:#3b82f6; font-size:13px; font-weight:600; padding:6px 14px; border-radius:999px; margin-bottom:16px; }
  .info-row { display:flex; justify-content:space-between; align-items:center; padding:13px 0; border-bottom:1px solid #f1f5f9; font-size:14px; }
  .info-row:last-child { border-bottom:none; }
  .info-row .label { color:#64748b; display:flex; align-items:center; gap:8px; }
  .info-row .val { font-weight:600; color:#0f172a; }
  .donut-wrap { position:relative; max-width:200px; margin:0 auto; }
  .donut-center { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); text-align:center; pointer-events:none; }
  .donut-center .num { font-size:26px; font-weight:700; color:#0f172a; }
  .donut-center .sub { font-size:11px; color:#94a3b8; }
  @media(max-width:767px){ .section-card{ padding:16px; } }
</style>
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">

  <!-- Header -->
  <div class="page-header">
    <h4>Dashboard Admin</h4>
    <p>Selamat datang kembali — pantau sistem parkir secara real-time</p>
  </div>

  <!-- Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
        <div>
          <div class="stat-label">Total User</div>
          <div class="stat-value"><?= $total_user ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-car"></i></div>
        <div>
          <div class="stat-label">Sedang Parkir</div>
          <div class="stat-value"><?= $total_kendaraan ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="stat-icon amber"><i class="fa-solid fa-receipt"></i></div>
        <div>
          <div class="stat-label">Total Transaksi</div>
          <div class="stat-value"><?= $total_transaksi ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="stat-card">
        <div class="stat-icon violet"><i class="fa-solid fa-square-parking"></i></div>
        <div>
          <div class="stat-label">Area Parkir</div>
          <div class="stat-value"><?= $total_area ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Row -->
  <div class="row g-3">

    <!-- Line Chart -->
    <div class="col-lg-7">
      <div class="section-card">
        <div class="card-heading">
          <i class="fa-solid fa-chart-line"></i> Transaksi 7 Hari Terakhir
        </div>
        <canvas id="lineChart" height="110"></canvas>
      </div>
    </div>

    <!-- Donut + Quick Info -->
    <div class="col-lg-5">
      <div class="section-card">
        <div class="card-heading">
          <i class="fa-solid fa-circle-info"></i> Ringkasan
        </div>

        <div class="today-badge">
          <i class="fa-solid fa-bolt"></i>
          <?= $transaksi_hari_ini ?> transaksi hari ini
        </div>

        <div class="donut-wrap mb-4">
          <canvas id="donutChart"></canvas>
          <div class="donut-center">
            <div class="num"><?= $transaksi_hari_ini ?></div>
            <div class="sub">Hari Ini</div>
          </div>
        </div>

        <div class="info-row">
          <span class="label"><i class="fa-solid fa-users"></i> Total User</span>
          <span class="val"><?= $total_user ?></span>
        </div>
        <div class="info-row">
          <span class="label"><i class="fa-solid fa-car"></i> Sedang Parkir</span>
          <span class="val"><?= $total_kendaraan ?></span>
        </div>
        <div class="info-row">
          <span class="label"><i class="fa-solid fa-square-parking"></i> Area Parkir</span>
          <span class="val"><?= $total_area ?></span>
        </div>
        <div class="info-row">
          <span class="label"><i class="fa-solid fa-receipt"></i> Total Transaksi</span>
          <span class="val"><?= $total_transaksi ?></span>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
const lineCtx = document.getElementById('lineChart');
new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: <?= json_encode($chart_labels) ?>,
    datasets: [{
      label: 'Transaksi',
      data: <?= json_encode($chart_data) ?>,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.08)',
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
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1, font: { size: 12 } },
        grid: { color: '#f1f5f9' }
      }
    }
  }
});

new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: ['Hari Ini', 'Lainnya'],
    datasets: [{
      data: [<?= $transaksi_hari_ini ?>, <?= max(0, $total_transaksi - $transaksi_hari_ini) ?>],
      backgroundColor: ['#3b82f6', '#e2e8f0'],
      borderWidth: 0,
      hoverOffset: 4
    }]
  },
  options: {
    cutout: '72%',
    plugins: { legend: { display: false } }
  }
});
</script>

</body>
</html>
