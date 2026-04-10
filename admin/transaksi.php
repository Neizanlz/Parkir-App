<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('admin');

$data = mysqli_query($conn, "
    SELECT t.id_parkir, k.plat_nomor, u.nama_lengkap,
           t.waktu_masuk, t.waktu_keluar, t.biaya_total, t.status
    FROM transaksi t
    JOIN kendaraan k ON t.id_kendaraan = k.id_kendaraan
    JOIN user u ON t.id_user = u.id_user
    ORDER BY t.id_parkir DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitoring Transaksi</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Monitoring Transaksi</h4>
    <p>Pantau seluruh aktivitas transaksi parkir</p>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-chart-bar"></i> Semua Transaksi</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>ID</th><th>Plat</th><th>Petugas</th><th>Masuk</th><th>Keluar</th><th>Total</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php while($d = mysqli_fetch_assoc($data)): ?>
          <tr>
            <td><?= $d['id_parkir'] ?></td>
            <td><strong><?= $d['plat_nomor'] ?></strong></td>
            <td><?= $d['nama_lengkap'] ?></td>
            <td style="font-size:12.5px;color:#64748b"><?= $d['waktu_masuk'] ?></td>
            <td style="font-size:12.5px;color:#64748b"><?= $d['waktu_keluar'] ?: '-' ?></td>
            <td><?= $d['biaya_total'] ? 'Rp '.number_format($d['biaya_total'],0,',','.') : '-' ?></td>
            <td>
              <?php if($d['status'] == 'masuk'): ?>
                <span class="badge badge-blue">Masuk</span>
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
