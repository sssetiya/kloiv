<?php
session_start();
include 'koneksi.php';

// 1. PROTEKSI: Cek apakah yang akses benar-benar Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses Ditolak!'); window.location='index.php';</script>";
    exit();
}

// 2. PROSES HAPUS PRODUK
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Ambil nama gambar dulu supaya bisa dihapus dari folder img (biar tidak penuh)
    $data = mysqli_query($conn, "SELECT gambar FROM produk WHERE id='$id'");
    $pecah = mysqli_fetch_array($data);
    $gambar = $pecah['gambar'];
    
    if (file_exists("img/$gambar")) {
        unlink("img/$gambar"); // Hapus file asli di folder img
    }

    // Hapus data dari database
    $query = mysqli_query($conn, "DELETE FROM produk WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Produk Berhasil Dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal Menghapus!'); window.location='index.php';</script>";
    }
}

// 3. PROSES TAMBAH PRODUK (Jika ada)
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    
    $nama_file = $_FILES['foto']['name'];
    $source = $_FILES['foto']['tmp_name'];
    $folder = './img/';

    move_uploaded_file($source, $folder.$nama_file);
    
    $insert = mysqli_query($conn, "INSERT INTO produk (nama_sepatu, harga, kategori, gambar, deskripsi) 
              VALUES ('$nama', '$harga', '$kategori', '$nama_file', '$deskripsi')");

    if ($insert) {
        echo "<script>alert('Produk Berhasil Ditambah!'); window.location='index.php';</script>";
    }
}
?>