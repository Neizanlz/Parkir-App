<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');

$id = $_GET['id'];

// Ambil data transaksi
$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT transaksi.*, tarif.tarif_per_jam
    FROM transaksi
    JOIN tarif ON transaksi.id_tarif=tarif.id_tarif
    WHERE id_parkir='$id'
"));

if (!$data) {
    die("Data tidak ditemukan!");
}

// Hitung durasi
$waktu_masuk = strtotime($data['waktu_masuk']);
$waktu_keluar = time();

$selisih_detik = $waktu_keluar - $waktu_masuk;
$durasi_jam = ceil($selisih_detik / 3600);
if ($durasi_jam < 1) {
    $durasi_jam = 1;
}

// Hitung total
$total = $durasi_jam * $data['tarif_per_jam'];

// Update transaksi
mysqli_query($conn,"
    UPDATE transaksi SET
    waktu_keluar = NOW(),
    durasi_jam = '$durasi_jam',
    biaya_total = '$total',
    status = 'keluar'
    WHERE id_parkir='$id'
");

echo "<script>
window.location='struk.php?id=$id';
</script>";
