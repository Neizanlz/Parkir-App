<?php
session_start();
include "../config/koneksi.php";
include "../config/helper.php";

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$data = mysqli_query($conn, "
    SELECT * FROM user 
    WHERE username='$username' 
    AND password='$password' 
    AND status_aktif=1
");

if(mysqli_num_rows($data) > 0){

    $user = mysqli_fetch_assoc($data);

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['nama']    = $user['nama_lengkap'];

    // 🔥 Log aktivitas login
    logAktivitas($conn, $user['id_user'], "Login ke sistem sebagai ".$user['role']);

    // 🔥 Redirect berdasarkan role
    if ($user['role'] == 'admin') {

        header("Location: ../admin/dashboard.php");

    } elseif ($user['role'] == 'petugas') {

        header("Location: ../petugas/dashboard.php");

    } elseif ($user['role'] == 'owner') {

        header("Location: ../owner/dashboard.php");

    }

    exit;

} else {

    echo "<script>
            alert('Login gagal! Username atau password salah.');
            window.location='../auth/login.php';
          </script>";
}
