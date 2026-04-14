<?php
// Tambahkan "3308" di bagian akhir parameter koneksi
$conn = mysqli_connect("localhost", "root", "", "app_parkir", 3308);

if(!$conn){
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>