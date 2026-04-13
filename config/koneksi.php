<?php
// Tambahkan "3308" di bagian akhir parameter koneksi
$conn = mysqli_connect("localhost", "root", "", "parkir_app", 3308);

if(!$conn){
    die("Koneksi gagal : " . mysqli_connect_error());
}
?>