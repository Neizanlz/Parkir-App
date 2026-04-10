<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');

$whereFilter = "";
if (!empty($_GET['filter'])) {
    $filter = mysqli_real_escape_string($conn, $_GET['filter']);
    $whereFilter = " AND kendaraan.jenis_kendaraan='$filter'";
}

$aktif    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE status='masuk'"));
$hari_ini = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM transaksi WHERE DATE(waktu_masuk)=CURDATE()"));
$pendapatan = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(biaya_total) as total FROM transaksi WHERE status='keluar' AND DATE(waktu_keluar)=CURDATE()"));

$area = mysqli_query($conn, "
    SELECT a.id_area, a.nama_area, a.kapasitas,
           (SELECT COUNT(*) FROM transaksi t WHERE t.id_area=a.id_area AND t.status='masuk') AS terisi
    FROM area_parkir a
");

$kendaraan = mysqli_query($conn, "
    SELECT transaksi.*, kendaraan.plat_nomor, kendaraan.jenis_kendaraan, area_parkir.nama_area
    FROM transaksi
    JOIN kendaraan ON transaksi.id_kendaraan=kendaraan.id_kendaraan
    JOIN area_parkir ON transaksi.id_area=area_parkir.id_area
    WHERE status='masuk' $whereFilter
    ORDER BY waktu_masuk DESC
");

$badgeJenis = ['motor' => 'badge-blue', 'mobil' => 'badge-green'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="10">
<title>Dashboard Petugas</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">
  <?php if(isset($_GET['sukses'])): ?>
  <div class="alert-success"><i class="fa-solid fa-circle-check"></i> Kendaraan berhasil masuk parkir.</div>
  <?php endif; ?>

    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
      <div class="page-header" style="margin-bottom:0">
        <h4>Dashboard Petugas</h4>
        <p>Monitor kendaraan parkir secara real-time</p>
      </div>
      <div style="text-align:right">
        <div id="hari"    style="font-size:12px;color:#94a3b8"></div>
        <div id="tanggal" style="font-size:12.5px;color:#64748b"></div>
        <div id="jam"     style="font-size:22px;font-weight:700;color:#3b82f6"></div>
      </div>
    </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-car"></i></div>
        <div>
          <div class="stat-label">Kendaraan Aktif</div>
          <div class="stat-value"><?= $aktif ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon amber"><i class="fa-solid fa-arrow-right-to-bracket"></i></div>
        <div>
          <div class="stat-label">Masuk Hari Ini</div>
          <div class="stat-value"><?= $hari_ini ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-coins"></i></div>
        <div>
          <div class="stat-label">Pendapatan Hari Ini</div>
          <div class="stat-value" style="font-size:18px">Rp <?= number_format($pendapatan['total'] ?? 0,0,',','.') ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Area Parkir -->
  <div class="card mb-4">
    <div class="card-title"><i class="fa-solid fa-map-location-dot"></i> Status Area Parkir</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>Nama Area</th><th>Kapasitas</th><th>Terisi</th><th>Sisa</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php while($a = mysqli_fetch_assoc($area)):
            $sisa = $a['kapasitas'] - $a['terisi']; ?>
          <tr>
            <td><?= $a['nama_area'] ?></td>
            <td><?= $a['kapasitas'] ?></td>
            <td><?= $a['terisi'] ?></td>
            <td><?= $sisa ?></td>
            <td>
              <?php if($sisa <= 0): ?>
                <span class="badge badge-red">Penuh</span>
              <?php else: ?>
                <span class="badge badge-green">Tersedia</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Kendaraan Parkir -->
  <div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <div class="card-title" style="margin-bottom:0"><i class="fa-solid fa-car"></i> Kendaraan Sedang Parkir</div>
      <form method="GET" class="d-flex gap-2">
        <select name="filter" class="form-select" style="width:140px;font-size:13px">
          <option value="">Semua Jenis</option>
          <option value="motor" <?= (@$_GET['filter']=='motor'?'selected':'') ?>>Motor</option>
          <option value="mobil" <?= (@$_GET['filter']=='mobil'?'selected':'') ?>>Mobil</option>
          <option value="lainnya" <?= (@$_GET['filter']=='lainnya'?'selected':'') ?>>Lainnya</option>
        </select>
        <button class="btn-primary-custom">Filter</button>
      </form>
    </div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>Plat</th><th>Jenis</th><th>Area</th><th>Masuk</th><th>Durasi</th><th>Aksi</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($kendaraan) == 0):
            $filterLabel = !empty($_GET['filter']) ? ucfirst($_GET['filter']) : '';
            $iconMap = ['motor' => 'fa-motorcycle', 'mobil' => 'fa-car', '' => 'fa-car'];
            $icon = $iconMap[$_GET['filter'] ?? ''] ?? 'fa-car';
          ?>
          <tr>
            <td colspan="6" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid <?= $icon ?>" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada data kendaraan<?= $filterLabel ? ' ('.$filterLabel.')' : '' ?>
            </td>
          </tr>
          <?php else: while($k = mysqli_fetch_assoc($kendaraan)):
            $selisih = time() - strtotime($k['waktu_masuk']);
            $jam   = floor($selisih / 3600);
            $menit = floor(($selisih % 3600) / 60);
            $cls   = $badgeJenis[$k['jenis_kendaraan']] ?? 'badge-gray';
          ?>
          <tr>
            <td><strong><?= $k['plat_nomor'] ?></strong></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($k['jenis_kendaraan']) ?></span></td>
            <td><?= $k['nama_area'] ?></td>
            <td style="font-size:12.5px;color:#64748b"><?= $k['waktu_masuk'] ?></td>
            <td style="font-size:12.5px"><?= $jam ?>j <?= $menit ?>m</td>
            <td>
              <a href="transaksi_keluar.php?id=<?= $k['id_parkir'] ?>" class="btn-warning-custom">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
              </a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function updateClock() {
  const now = new Date();
  const hari   = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
  const bulan  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
  document.getElementById("hari").textContent    = hari[now.getDay()];
  document.getElementById("tanggal").textContent = now.getDate()+" "+bulan[now.getMonth()]+" "+now.getFullYear();
  document.getElementById("jam").textContent     =
    now.getHours().toString().padStart(2,'0')+":"+
    now.getMinutes().toString().padStart(2,'0')+":"+
    now.getSeconds().toString().padStart(2,'0');
}
setInterval(updateClock, 1000);
updateClock();
</script>

</body>
</html>
