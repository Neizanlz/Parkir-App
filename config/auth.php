<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

function cekRole($role) {
    if ($_SESSION['role'] != $role) {
        header("Location: ../auth/login.php");
        exit;
    }
}
