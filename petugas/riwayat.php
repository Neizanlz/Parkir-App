<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');

$data = mysqli_query($conn, "
    SELECT t.id_parkir, k.plat_nomor, k.jenis_kendaraan, a.nama_area,
           t.waktu_masuk, t.waktu_keluar, t.durasi_jam, t.biaya_total, t.status
    FROM transaksi t
    JOIN kendaraan k ON t.id_kendaraan = k.id_kendaraan
    JOIN area_parkir a ON t.id_area = a.id_area
    ORDER BY t.id_parkir DESC
");

$badgeJenis = ['motor' => 'badge-blue', 'mobil' => 'badge-green'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Riwayat Transaksi</h4>
    <p>Seluruh riwayat transaksi parkir</p>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Semua Transaksi</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Plat</th><th>Jenis</th><th>Area</th><th>Masuk</th><th>Keluar</th><th>Durasi</th><th>Total</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($data)):
            $cls = $badgeJenis[$row['jenis_kendaraan']] ?? 'badge-gray'; ?>
          <tr>
            <td><?= $row['id_parkir'] ?></td>
            <td><strong><?= $row['plat_nomor'] ?></strong></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($row['jenis_kendaraan']) ?></span></td>
            <td><?= $row['nama_area'] ?></td>
            <td style="font-size:12px;color:#64748b"><?= $row['waktu_masuk'] ?></td>
            <td style="font-size:12px;color:#64748b"><?= $row['waktu_keluar'] ?: '-' ?></td>
            <td><?= $row['durasi_jam'] ? $row['durasi_jam'].'j' : '-' ?></td>
            <td><?= $row['biaya_total'] ? 'Rp '.number_format($row['biaya_total'],0,',','.') : '-' ?></td>
            <td>
              <?php if($row['status'] == 'masuk'): ?>
                <span class="badge badge-amber">Masuk</span>
              <?php else: ?>
                <span class="badge badge-green">Keluar</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
