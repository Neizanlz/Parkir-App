<?php
include "../config/auth.php";
include "../config/koneksi.php";
include "../config/helper.php";
cekRole('admin');

$data = mysqli_query($conn, "
    SELECT l.*, u.nama_lengkap, u.role
    FROM log_aktivitas l
    JOIN user u ON l.id_user = u.id_user
    ORDER BY l.waktu_aktivitas DESC
");

$roleBadge = ['admin' => 'badge-red', 'petugas' => 'badge-blue', 'owner' => 'badge-green'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Log Aktivitas</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/style.css" rel="stylesheet">
</head>
<body>

<?php include "sidebar_admin.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Log Aktivitas</h4>
    <p>Rekam jejak aktivitas seluruh pengguna sistem</p>
  </div>

  <div class="card">
    <div class="card-title"><i class="fa-solid fa-list-check"></i> Riwayat Aktivitas</div>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr><th>User</th><th>Role</th><th>Aktivitas</th><th>Waktu</th></tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($data) == 0): ?>
          <tr>
            <td colspan="4" class="text-center" style="padding:32px;color:#94a3b8">
              <i class="fa-solid fa-list-check" style="font-size:24px;display:block;margin-bottom:8px"></i>
              Tidak ada log aktivitas
            </td>
          </tr>
          <?php else: while($d = mysqli_fetch_assoc($data)): $cls = $roleBadge[$d['role']] ?? 'badge-gray'; ?>
          <tr>
            <td><strong><?= $d['nama_lengkap'] ?></strong></td>
            <td><span class="badge <?= $cls ?>"><?= ucfirst($d['role']) ?></span></td>
            <td><?= $d['aktivitas'] ?></td>
            <td style="color:#64748b;font-size:12.5px"><?= date('d M Y, H:i', strtotime($d['waktu_aktivitas'])) ?></td>
          </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
