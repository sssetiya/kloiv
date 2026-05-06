<?php
session_start(); // Memulai session agar bisa ingat siapa yang login
include 'koneksi.php'; // Menghubungkan ke database kloiv_store

if (isset($_POST['login'])) {
    // Mengambil data dari form modal di index.php
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Mencari user di tabel users
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if (mysqli_num_rows($query) > 0) {
        // Jika data ketemu, simpan identitas ke Session
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role']; // Ini yang menentukan dia bisa liat ikon gerigi atau tidak

        if ($data['role'] == 'admin') {
            echo "<script>alert('Halo Admin, selamat bekerja!'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Berhasil Login!'); window.location='index.php';</script>";
        }
    } else {
        // Jika username atau password salah
        echo "<script>alert('Username atau Password salah!'); window.location='index.php';</script>";
    }
} else {
    // Jika mencoba akses file ini tanpa lewat form login
    header("Location: index.php");
}
// Di dalam login_proses.php setelah cek username & password benar
$_SESSION['username'] = $data['username'];
$_SESSION['role'] = $data['role']; // Ini yang dibaca oleh script di baris 272
?>