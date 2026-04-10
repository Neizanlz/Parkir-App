<?php

function logAktivitas($conn, $id_user, $aktivitas) {
    mysqli_query($conn, "INSERT INTO log_aktivitas 
        (id_user, aktivitas, waktu_aktivitas)
        VALUES ('$id_user', '$aktivitas', NOW())");
}
