<?php
include "../config/auth.php";
include "../config/koneksi.php";

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Laporan</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Inter', sans-serif;
    background: #f1f5f9;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 40px 16px;
  }

  /* ── ACTIONS BAR ── */
  .actions-bar {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
    width: 100%;
    max-width: 420px;
    flex-wrap: wrap;
  }
  .btn {
    flex: 1;
    padding: 10px 14px;
    border: none;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    cursor: pointer;
    transition: opacity .15s, transform .1s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
  }
  .btn:hover { opacity: .88; transform: translateY(-1px); }
  .btn-dark  { background: #0f172a; color: #fff; }
  .btn-blue  { background: #3b82f6; color: #fff; }
  .btn-gray  { background: #e2e8f0; color: #475569; }
  .btn-back  { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }

  /* ── RECEIPT ── */
  .receipt {
    background: #fff;
    width: 100%;
    max-width: 420px;
    border-radius: 20px;
    box-shadow: 0 4px 32px rgba(0,0,0,.08);
    overflow: hidden;
  }

  .receipt-header {
    background: linear-gradient(135deg, #1d4ed8, #3b82f6);
    color: #fff;
    text-align: center;
    padding: 28px 24px;
  }
  .receipt-header .icon {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin: 0 auto 12px;
  }
  .receipt-header h3 { font-size: 17px; font-weight: 700; }
  .receipt-header p  { font-size: 12px; opacity: .75; margin-top: 3px; }

  .receipt-body { padding: 24px; }

  .period-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #eff6ff;
    color: #3b82f6;
    font-size: 12.5px;
    font-weight: 600;
    padding: 7px 14px;
    border-radius: 999px;
    margin-bottom: 20px;
  }

  .divider { border: none; border-top: 1px dashed #e2e8f0; margin: 16px 0; }

  /* Table inside receipt */
  .receipt-table { width: 100%; border-collapse: collapse; }
  .receipt-table thead th {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 0 0 10px;
    border-bottom: 1px solid #f1f5f9;
  }
  .receipt-table thead th:last-child { text-align: right; }
  .receipt-table tbody td {
    padding: 10px 0;
    font-size: 13px;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
  }
  .receipt-table tbody tr:last-child td { border-bottom: none; }
  .receipt-table tbody td:last-child { text-align: right; font-weight: 600; color: #0f172a; }
  .receipt-table .id-cell   { font-size: 11.5px; color: #94a3b8; }
  .receipt-table .date-cell { font-size: 12px; color: #64748b; margin-top: 2px; }

  .total-box {
    background: #f8fafc;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
  }
  .total-box .lbl { font-size: 13px; font-weight: 600; color: #475569; }
  .total-box .val { font-size: 18px; font-weight: 700; color: #16a34a; }

  .empty-state {
    text-align: center;
    padding: 32px 0;
    color: #94a3b8;
    font-size: 13px;
  }
  .empty-state i { font-size: 28px; display: block; margin-bottom: 8px; }

  .thanks {
    text-align: center;
    font-size: 12.5px;
    color: #94a3b8;
    margin-top: 18px;
    padding-top: 16px;
    border-top: 1px dashed #e2e8f0;
  }

  /* ── PRINT ── */
  @media print {
    body { background: #fff; padding: 0; justify-content: flex-start; }
    .actions-bar { display: none; }
    .receipt { box-shadow: none; border-radius: 0; width: 100%; }
    .receipt-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  }
</style>
</head>
<body>

<!-- Action Buttons -->
<div class="actions-bar">
  <button class="btn btn-dark"  onclick="printAndBack()"><i class="fa-solid fa-print"></i> Print</button>
  <button class="btn btn-blue"  onclick="downloadPDF()"><i class="fa-solid fa-file-pdf"></i> PDF</button>
  <button class="btn btn-gray"  onclick="downloadExcel()"><i class="fa-solid fa-file-excel"></i> Excel</button>
  <a href="rekap.php?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>" class="btn btn-back">
    <i class="fa-solid fa-arrow-left"></i> Kembali
  </a>
</div>

<!-- Receipt -->
<div class="receipt" id="areaCetak">

  <div class="receipt-header">
    <div class="icon"><i class="fa-solid fa-square-parking"></i></div>
    <h3>PARKIR SYSTEM</h3>
    <p>Laporan Transaksi</p>
  </div>

  <div class="receipt-body">

    <?php if($tgl1 && $tgl2): ?>
    <div class="period-badge">
      <i class="fa-solid fa-calendar-range"></i>
      <?= date('d M Y', strtotime($tgl1)) ?> — <?= date('d M Y', strtotime($tgl2)) ?>
    </div>
    <?php endif; ?>

    <?php if(count($rows) > 0): ?>
    <table class="receipt-table">
      <thead>
        <tr>
          <th>Transaksi</th>
          <th style="text-align:right">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $d): ?>
        <tr>
          <td>
            <div class="id-cell">#<?= $d['id_parkir'] ?></div>
            <div class="date-cell"><?= date('d/m/Y H:i', strtotime($d['waktu_keluar'])) ?></div>
          </td>
          <td>Rp <?= number_format($d['biaya_total'],0,',','.') ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="total-box">
      <span class="lbl">Total Pendapatan</span>
      <span class="val">Rp <?= number_format($total,0,',','.') ?></span>
    </div>

    <?php else: ?>
    <div class="empty-state">
      <i class="fa-solid fa-file-circle-xmark"></i>
      Tidak ada data transaksi
    </div>
    <?php endif; ?>

    <div class="thanks">Terima kasih </div>

  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
const backUrl = 'rekap.php?tgl1=<?= $tgl1 ?>&tgl2=<?= $tgl2 ?>';

function printAndBack() {
    window.print();
    setTimeout(() => { window.location = backUrl; }, 500);
}
function downloadExcel() {
    const rows = <?= json_encode(array_map(fn($d) => [
        'ID Parkir'     => $d['id_parkir'],
        'Waktu Keluar'  => $d['waktu_keluar'],
        'Biaya Total'   => (int)$d['biaya_total'],
    ], $rows)) ?>;
    rows.push({});
    rows.push({ 'ID Parkir': 'TOTAL PENDAPATAN', 'Waktu Keluar': '', 'Biaya Total': <?= (int)$total ?> });
    const ws = XLSX.utils.json_to_sheet(rows);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'Laporan');
    XLSX.writeFile(wb, 'laporan.xlsx');
    setTimeout(() => { window.location = backUrl; }, 1000);
}
function downloadPDF() {
    html2canvas(document.getElementById("areaCetak")).then(canvas => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();
        pdf.addImage(canvas.toDataURL("image/png"), 'PNG', 10, 10, 180, 0);
        pdf.save("laporan.pdf");
        setTimeout(() => { window.location = backUrl; }, 1000);
    });
}
</script>

</body>
</html>
