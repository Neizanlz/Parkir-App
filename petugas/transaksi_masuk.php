<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');

$area = mysqli_query($conn, "
    SELECT a.id_area, a.nama_area, a.kapasitas,
           (SELECT COUNT(*) FROM transaksi t WHERE t.id_area=a.id_area AND t.status='masuk') AS terisi
    FROM area_parkir a
");

$kendaraan = mysqli_query($conn, "
    SELECT id_kendaraan, plat_nomor, jenis_kendaraan FROM kendaraan
    WHERE id_kendaraan NOT IN (
        SELECT id_kendaraan FROM transaksi WHERE status='masuk'
    )
    ORDER BY plat_nomor ASC
");
$listKendaraan = [];
while ($k = mysqli_fetch_assoc($kendaraan)) {
    $listKendaraan[] = $k;
}

if (isset($_POST['simpan'])) {
    $id_kendaraan = (int)$_POST['id_kendaraan'];
    $id_area      = (int)$_POST['id_area'];
    $id_user      = $_SESSION['id_user'];

    $kend = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kendaraan WHERE id_kendaraan='$id_kendaraan'"));
    if (!$kend) die("Kendaraan tidak ditemukan!");

    $jenis = $kend['jenis_kendaraan'];

    $cekArea = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT kapasitas,
               (SELECT COUNT(*) FROM transaksi WHERE id_area='$id_area' AND status='masuk') AS terisi
        FROM area_parkir WHERE id_area='$id_area'
    "));

    if ($cekArea['terisi'] >= $cekArea['kapasitas']) {
        echo "<script>alert('Area Penuh!'); window.location='transaksi_masuk.php';</script>";
        exit;
    }

    $getTarif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tarif WHERE jenis_kendaraan='$jenis'"));
    if (!$getTarif) die("Tarif untuk jenis $jenis belum dibuat!");

    mysqli_query($conn, "
        INSERT INTO transaksi (id_kendaraan, waktu_masuk, id_tarif, status, id_user, id_area)
        VALUES ('$id_kendaraan', NOW(), '{$getTarif['id_tarif']}', 'masuk', '$id_user', '$id_area')
    ");

    header("Location: dashboard.php?sukses=1");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaksi Masuk</title>
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">
  <div class="page-header">
    <h4>Transaksi Masuk</h4>
    <p>Input kendaraan yang baru masuk area parkir</p>
  </div>

  <div class="card" style="max-width:600px">
    <div class="card-title"><i class="fa-solid fa-arrow-right-to-bracket"></i> Form Kendaraan Masuk</div>
    <form method="POST">
      <div class="row g-3">

        <div class="col-12 col-md-6">
          <label>Plat Nomor</label>
          <select name="id_kendaraan" id="plat_select" class="form-select" required onchange="isiJenis(this)">
            <option value="">-- Pilih Plat --</option>
            <?php foreach($listKendaraan as $k): ?>
            <option value="<?= $k['id_kendaraan'] ?>" data-jenis="<?= $k['jenis_kendaraan'] ?>">
              <?= $k['plat_nomor'] ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-12 col-md-6">
          <label>Jenis Kendaraan</label>
          <input type="text" id="jenis_display" class="form-control"
                 placeholder="Otomatis terisi" readonly
                 style="background:#f8fafc;color:#475569">
        </div>

        <div class="col-12">
          <label>Area Parkir</label>
          <select name="id_area" class="form-select" required>
            <option value="">-- Pilih Area --</option>
            <?php foreach($listKendaraan as $k): endforeach; // reset pointer ?>
            <?php
            // re-query area karena pointer sudah habis
            $area2 = mysqli_query($conn, "
                SELECT a.id_area, a.nama_area, a.kapasitas,
                       (SELECT COUNT(*) FROM transaksi t WHERE t.id_area=a.id_area AND t.status='masuk') AS terisi
                FROM area_parkir a
            ");
            while($a = mysqli_fetch_assoc($area2)):
              $sisa = $a['kapasitas'] - $a['terisi']; ?>
            <option value="<?= $a['id_area'] ?>" <?= $sisa <= 0 ? 'disabled' : '' ?>>
              <?= $a['nama_area'] ?> — Sisa <?= $sisa ?> slot<?= $sisa <= 0 ? ' (PENUH)' : '' ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-12 col-sm-6">
          <button name="simpan" class="btn-primary-custom w-100">Simpan</button>
        </div>
        <div class="col-12 col-sm-6">
          <a href="dashboard.php" class="btn-outline-custom w-100">Kembali</a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function isiJenis(select) {
    const opt   = select.options[select.selectedIndex];
    const jenis = opt.dataset.jenis || '';
    document.getElementById('jenis_display').value = jenis ? jenis.charAt(0).toUpperCase() + jenis.slice(1) : '';
}
</script>

</body>
</html>
