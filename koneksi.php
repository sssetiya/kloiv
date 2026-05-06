<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kloiv_store"; // Pastikan nama database ini sama dengan yang Anda buat di phpMyAdmin

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>