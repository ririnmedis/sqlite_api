<?php
$koneksi = mysqli_connect('localhost', 'root', '', 'jurnal');
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    // echo "Koneksi berhasil";
}

?>