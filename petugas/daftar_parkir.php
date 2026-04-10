<?php
include "../config/auth.php";
include "../config/koneksi.php";
cekRole('petugas');


$data = mysqli_query($conn, "
    SELECT transaksi.*, kendaraan.plat_nomor, area_parkir.nama_area
    FROM transaksi
    JOIN kendaraan ON transaksi.id_kendaraan=kendaraan.id_kendaraan
    JOIN area_parkir ON transaksi.id_area=area_parkir.id_area
    WHERE status='masuk'
");
?>

<h2>Kendaraan Sedang Parkir</h2>

<table border="1">
<tr>
    <th>Plat</th>
    <th>Area</th>
    <th>Waktu Masuk</th>
    <th>Aksi</th>
</tr>

<?php while($d=mysqli_fetch_assoc($data)){ ?>
<tr>
    <td><?= $d['plat_nomor'] ?></td>
    <td><?= $d['nama_area'] ?></td>
    <td><?= $d['waktu_masuk'] ?></td>
    <td>
        <a href="transaksi_keluar.php?id=<?= $d['id_parkir'] ?>">
            Proses Keluar
        </a>
    </td>
</tr>
<?php } ?>
</table>
