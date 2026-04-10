<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');

$id   = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT transaksi.*, kendaraan.plat_nomor, kendaraan.jenis_kendaraan, area_parkir.nama_area
    FROM transaksi
    JOIN kendaraan ON transaksi.id_kendaraan=kendaraan.id_kendaraan
    JOIN area_parkir ON transaksi.id_area=area_parkir.id_area
    WHERE transaksi.id_parkir='$id'
"));

if (!$data) die("Data tidak ditemukan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Struk Parkir</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Inter', sans-serif; background: #f1f5f9; display: flex; flex-direction: column; align-items: center; padding: 32px 16px; }

  .receipt {
    background: #fff;
    width: 100%;
    max-width: 340px;
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 4px 24px rgba(0,0,0,.08);
  }
  .receipt-header { text-align: center; margin-bottom: 18px; }
  .receipt-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
  .receipt-header p  { font-size: 11.5px; color: #94a3b8; margin-top: 2px; }

  .divider { border: none; border-top: 1px dashed #e2e8f0; margin: 14px 0; }

  .row-item { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
  .row-item .lbl { color: #64748b; }
  .row-item .val { font-weight: 500; color: #0f172a; }

  .total-box {
    background: #f8fafc;
    border-radius: 10px;
    padding: 14px;
    text-align: center;
    margin-top: 14px;
  }
  .total-box .lbl { font-size: 12px; color: #94a3b8; }
  .total-box .val { font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 4px; }

  .thanks { text-align: center; font-size: 12.5px; color: #94a3b8; margin-top: 16px; }

  .actions { display: flex; gap: 10px; margin-top: 20px; }
  .btn {
    flex: 1; padding: 10px; border: none; border-radius: 10px;
    font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity .15s;
  }
  .btn:hover { opacity: .85; }
  .btn-print { background: #0f172a; color: #fff; }
  .btn-back  { background: #f1f5f9; color: #475569; text-decoration: none; display: flex; align-items: center; justify-content: center; }

  @media print {
    body { background: #fff; padding: 0; }
    .receipt { box-shadow: none; border-radius: 0; }
    .actions { display: none; }
  }
</style>
</head>
<body>

<div class="receipt" id="struk">
  <div class="receipt-header">
    <h3>PARKIR</h3>
    <p>Struk Pembayaran Parkir</p>
  </div>

  <hr class="divider">

  <div class="row-item"><span class="lbl">Plat Nomor</span><span class="val"><?= $data['plat_nomor'] ?></span></div>
  <div class="row-item"><span class="lbl">Jenis</span><span class="val"><?= ucfirst($data['jenis_kendaraan']) ?></span></div>
  <div class="row-item"><span class="lbl">Area</span><span class="val"><?= $data['nama_area'] ?></span></div>

  <hr class="divider">

  <div class="row-item"><span class="lbl">Waktu Masuk</span><span class="val"><?= date('d/m/Y H:i', strtotime($data['waktu_masuk'])) ?></span></div>
  <div class="row-item"><span class="lbl">Waktu Keluar</span><span class="val"><?= date('d/m/Y H:i', strtotime($data['waktu_keluar'])) ?></span></div>
  <div class="row-item"><span class="lbl">Durasi</span><span class="val"><?= $data['durasi_jam'] ?> Jam</span></div>

  <div class="total-box">
    <div class="lbl">Total Bayar</div>
    <div class="val">Rp <?= number_format($data['biaya_total'],0,',','.') ?></div>
  </div>

  <div class="thanks">Terima Kasih </div>
</div>

<div class="actions">
  <button class="btn btn-print" onclick="window.print()">Print</button>
  <a href="dashboard.php" class="btn btn-back">Kembali</a>
</div>

</body>
</html>
