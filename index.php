
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> kloiv step Store</title>
<link rel="stylesheet" href="style.css">
</head>
<body>


<header>
<div class="logo">👟kloiv step</div>

<div class="search-box">
<input type="text" id="searchInput" placeholder="Cari sepatu impian..." onkeyup="searchProduk()">
<button onclick="searchProduk()">Cari</button>
</div>

<div class="menu">
<span>🔔</span>
<span onclick="openCart()">🛒 <b id="count">0</b></span>
</div>
</header>


<section class="banner">
<h1>FLASH SALE HARI INI 🔥</h1>
<p>Banyak pilihan sepatu keren & murah</p>
<button>Belanja Sekarang</button>
</section>


<section class="kategori">
<div onclick="filterProduk('all')">🛍 Semua</div>
<div onclick="filterProduk('sneakers')">👟 Sneakers</div>
<div onclick="filterProduk('sport')">🏃 Sport</div>
<div onclick="filterProduk('wanita')">👠 Wanita</div>
<div onclick="filterProduk('boots')">🥾 Boots</div>
<div onclick="filterProduk('sandal')">🩴 Sandal</div>
</section>

<section class="container">
<h2>🔥 Produk Pilihan</h2>
<div class="products">
<?php
  include 'koneksi.php';
  // Ambil semua data produk dari database kloiv_store
  $query = mysqli_query($conn, "SELECT * FROM produk");
  
  // Jika database kosong, kasih pesan biar tidak bingung
  if(mysqli_num_rows($query) == 0) {
      echo "<p style='grid-column: 1 / -1; text-align: center;'>Belum ada produk di database.</p>";
  }

  while($row = mysqli_fetch_array($query)) {
?>
    <div class="card <?php echo $row['kategori']; ?>" 
         onclick="showDetail('<?php echo $row['nama_sepatu']; ?>', 'Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>', 'img/<?php echo $row['gambar']; ?>', '<?php echo $row['deskripsi']; ?>')">
        
        <img src="img/<?php echo $row['gambar']; ?>">
        <h3><?php echo $row['nama_sepatu']; ?></h3>
        <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
        
       <button onclick="event.stopPropagation(); addCart('<?php echo addslashes($row['nama_sepatu']); ?>', <?php echo $row['harga']; ?>)">
    + Keranjang
</button>
    </div>
<?php } ?>
</div>

<!-- ✅ TAMBAHAN MODAL (TIDAK MENGGANGGU YANG LAIN) -->
<div id="detailModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeDetail()">✖</span>
    <img id="detailImg">
    <h2 id="detailNama"></h2>
    <p id="detailHarga"></p>
    <p id="detailDesc"></p>
    <button onclick="addCartFromDetail()">+ Keranjang</button>
  </div>
</div>

<div class="cart-popup" id="cartPopup">

<div class="cart-box">
<span class="close" onclick="closeCart()">✖</span>

<h2>🛒 Keranjang Belanja</h2>

<p id="cartItems">Belum ada barang</p>

<h3>Total : Rp <span id="total">0</span></h3>

<input type="text" placeholder="Nama Lengkap">
<input type="text" placeholder="Alamat Lengkap">
<input type="text" placeholder="No HP">

<select id="payment">
<option>Pilih Pembayaran</option>
<option>COD</option>
<option>DANA</option>
<option>OVO</option>
<option>GoPay</option>
<option>QRIS</option>
</select>

<button onclick="checkout()">Checkout Sekarang</button>

</div>
</div>

</section>

<!-- 6. LOGIN & REGISTRASI USER (Modal Terpisah) -->
<div id="loginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeLogin()">✖</span>
    
    <!-- Bagian Login -->
    <div id="loginForm">
      <h2>Login</h2>
      <form action="login_proses.php" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Masuk</button>
      </form>
      <p>Belum punya akun? <a href="javascript:void(0)" onclick="showRegister()">Daftar di sini</a></p>
    </div>

    <!-- Bagian Daftar (Awalnya Sembunyi) -->
    <div id="registerForm" style="display:none;">
      <h2>Daftar Akun Baru</h2>
      <form action="daftar_proses.php" method="POST">
        <input type="text" name="username" placeholder="Username Baru" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="daftar">Daftar Sekarang</button>
      </form>
      <p>Sudah punya akun? <a href="javascript:void(0)" onclick="showLogin()">Login di sini</a></p>
    </div>
  </div>
</div>

<!-- DASHBOARD ADMIN (Terhubung ke Database) -->
<section id="adminSection" class="container" style="display:none; background:#fff; border-radius:15px; padding:20px;">
  <h2>🛠️ Dashboard Admin (CRUD Database)</h2>
  
  <!-- Form Tambah Produk dengan Upload File -->
  <form action="admin_proses.php" method="POST" enctype="multipart/form-data" style="margin-bottom:30px;">
    <input type="text" name="nama" placeholder="Nama Sepatu" required>
    <input type="number" name="harga" placeholder="Harga" required>
    <select name="kategori">
      <option value="sneakers">Sneakers</option>
      <option value="sport">Sport</option>
      <option value="wanita">Wanita</option>
    </select>
    <input type="file" name="foto" accept="image/*" required> <!-- Input Foto Asli -->
    <textarea name="deskripsi" placeholder="Deskripsi Produk"></textarea>
    <button type="submit" name="tambah">Simpan ke Database</button>
  </form>

  <h3>Daftar & Hapus Produk</h3>
  <table border="1" width="100%" style="border-collapse:collapse;">
    <tr style="background:#ff5722; color:white;">
      <th>Nama</th>
      <th>Harga</th>
      <th>Aksi</th>
    </tr>
    <?php
      include 'koneksi.php';
      $data = mysqli_query($conn, "SELECT * FROM produk");
      while($d = mysqli_fetch_array($data)){
        echo "<tr>
                <td>{$d['nama_sepatu']}</td>
                <td>Rp ".number_format($d['harga'])."</td>
                <td><a href='admin_proses.php?hapus={$d['id']}' style='color:red;'>Hapus</a></td>
              </tr>";
      }
    ?>
  </table>
</section>
<!-- Tombol Akses Cepat di Header (Tambahkan di dalam div class="menu") -->
<script>
  // Script kecil untuk menyisipkan tombol admin & login ke header secara dinamis
  document.querySelector('.menu').innerHTML += `
  `;
</script>

<footer class="main-footer">
    <div class="footer-container">
        <!-- Kolom 1: Tentang -->
        <div class="footer-box">
            <h3>kloiv step Shoes</h3>
            <p class="tagline">Trend & Stylish Every Day</p>
            <p class="desc">Kloiv Step adalah toko sepatu terpercaya yang menyediakan berbagai model sepatu keren, berkualitas, dan nyaman dipakai untuk setiap aktivitas Anda.</p>
        </div>

        <!-- Kolom 2: Kontak -->
        <div class="footer-box">
            <h3>Kontak Kami</h3>
            <ul>
                <li>📞 080901795980</li>
                <li>💬 WhatsApp</li>
                <li>✉️ kloivstep@gmail.com</li>
                <li>📍 Jl. Tol Ciawi No.1, Ciawi, Bogor</li>
            </ul>
        </div>

        <!-- Kolom 3: Sosial Media -->
        <div class="footer-box">
            <h3>Ikuti Kami</h3>
            <div class="social-icons">
                <a href="#">📘</a>
                <a href="#">📷</a>
                <a href="#">💃</a>
                <a href="#">🎵</a>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2026 Kloiv Step. Semua hak dilindungi.</p>
    </div>
</footer>

<script src="script.js"></script>

<script>
let currentProduk = {};

function showDetail(nama, harga, gambar, desc) {
  document.getElementById("detailModal").style.display = "block";
  document.getElementById("detailNama").innerText = nama;
  document.getElementById("detailHarga").innerText = harga;
  document.getElementById("detailImg").src = gambar;
  document.getElementById("detailDesc").innerText = desc;
  currentProduk = { nama, harga };
}

function closeDetail() {
  document.getElementById("detailModal").style.display = "none";
}

function addCartFromDetail() {
  let harga = parseInt(currentProduk.harga.replace(/[^0-9]/g, ""));
  addCart(currentProduk.nama, harga);
}
</script>

</body>
<script>
  let userIcon = `<span onclick="openLogin()">👤</span>`;
  
  // Jika sudah login dan role-nya adalah admin
  <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
    userIcon += `<span onclick="toggleAdmin()" title="Mode Admin">⚙️</span>`;
  <?php endif; ?>

  // Jika sudah login, bisa tambahkan tombol Logout
  <?php if(isset($_SESSION['username'])): ?>
    userIcon += `<a href="logout.php" style="text-decoration:none; font-size:12px;">🚪 Logout</a>`;
  <?php endif; ?>

  document.querySelector('.menu').innerHTML += userIcon;
</script>

</html>