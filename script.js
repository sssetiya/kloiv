let cart = [];
let total = 0;

// TAMBAH KE KERANJANG
function addCart(nama, harga){
cart.push({
nama: nama,
harga: harga
});     

total += harga;

updateCart();

alert(nama + " berhasil ditambahkan ke keranjang!");
}

// UPDATE TAMPILAN KERANJANG
function updateCart(){
document.getElementById("count").innerText = cart.length;

let isi = "";

cart.forEach((item,index)=>{
isi += `
${index + 1}. ${item.nama} - Rp ${item.harga.toLocaleString("id-ID")}
<button onclick="hapusItem(${index})">❌</button><br><br>
`;
});

if(cart.length == 0){
isi = "Belum ada barang";
}

document.getElementById("cartItems").innerHTML = isi;
document.getElementById("total").innerText = total.toLocaleString("id-ID");
}

// LIHAT KERANJANG
function showCart(){
if(cart.length == 0){
alert("Keranjang kosong!");
return;
}

let pesan = "Isi Keranjang:\n\n";

cart.forEach((item,index)=>{
pesan += `${index+1}. ${item.nama} - Rp ${item.harga.toLocaleString("id-ID")}\n`;
});

pesan += "\nTotal: Rp " + total.toLocaleString("id-ID");

alert(pesan);
}

// HAPUS ITEM
function hapusItem(index){
total -= cart[index].harga;
cart.splice(index,1);
updateCart();
}

// CHECKOUT
function checkout(){

let nama = document.querySelector('input[placeholder="Nama Lengkap"]').value;
let alamat = document.querySelector('input[placeholder="Alamat Lengkap"]').value;
let hp = document.querySelector('input[placeholder="No HP"]').value;
let bayar = document.getElementById("payment").value;

if(cart.length == 0){
alert("Keranjang masih kosong!");
return;
}

if(nama == "" || alamat == "" || hp == ""){
alert("Lengkapi data checkout!");
return;
}

if(bayar == "Pilih Pembayaran"){
alert("Pilih metode pembayaran!");
return;
}

alert(
"Checkout Berhasil!\n\n" +
"Nama: " + nama +
"\nAlamat: " + alamat +
"\nNo HP: " + hp +
"\nPembayaran: " + bayar +
"\nTotal: Rp " + total.toLocaleString("id-ID")
);

// reset
cart = [];
total = 0;

document.querySelector('input[placeholder="Nama Lengkap"]').value = "";
document.querySelector('input[placeholder="Alamat Lengkap"]').value = "";
document.querySelector('input[placeholder="No HP"]').value = "";

updateCart();
}

// FILTER KATEGORI
function filterProduk(kategori){
let produk = document.querySelectorAll(".card");

produk.forEach(item => {

if(kategori == "all"){
item.style.display = "block";
}else{
if(item.classList.contains(kategori)){
item.style.display = "block";
}else{
item.style.display = "none";
}
}

});
}

// SEARCH PRODUK
function searchProduk(){

let input = document.getElementById("searchInput").value.toLowerCase();
let produk = document.querySelectorAll(".card");

produk.forEach(item => {

let nama = item.querySelector("h3").innerText.toLowerCase();

if(nama.includes(input)){
item.style.display = "block";
}else{
item.style.display = "none";
}

});

}

function openCart(){
document.getElementById("cartPopup").style.display="block";
updateCart();
}

function closeCart(){
document.getElementById("cartPopup").style.display="none";
}

// SCROLL KE PRODUK
function keProduk(){
document.querySelector(".products").scrollIntoView({
behavior:"smooth"
});
}


// ================= TAMBAHAN DETAIL PRODUK =================
function showDetail(nama, harga, gambar, desc) {
  document.getElementById("detailModal").style.display = "block";
  document.getElementById("detailNama").innerText = nama;
  document.getElementById("detailHarga").innerText = harga;
  document.getElementById("detailImg").src = gambar;
  document.getElementById("detailDesc").innerText = desc;
}

function closeModal() {
  document.getElementById('modal').style.display = 'none';
}
// --- 6. LOGIKA LOGIN ---
function openLogin() {
    document.getElementById("loginModal").style.display = "block";
}

function closeLogin() {
    document.getElementById("loginModal").style.display = "none";
}

function handleLogin() {
    let user = document.getElementById("userLogin").value;
    if(user !== "") {
        alert("Selamat datang, " + user + "!");
        closeLogin();
    } else {
        alert("Masukkan username!");
    }
}

// --- 7. LOGIKA DASHBOARD ADMIN (CRUD) ---
function toggleAdmin() {
    let adminSec = document.getElementById("adminSection");
    adminSec.style.display = adminSec.style.display === "none" ? "block" : "none";
    renderAdminProducts();
}

function tambahProduk() {
    // Ambil data dari form admin
    let nama = document.getElementById("newNama").value;
    let hargaInput = document.getElementById("newHarga").value;
    let kategori = document.getElementById("newKategori").value.toLowerCase();
    
    // Validasi input
    if (nama === "" || hargaInput === "" || kategori === "") {
        alert("Semua data produk harus diisi!");
        return;
    }

    let hargaNominal = parseInt(hargaInput);
    let hargaFormat = "Rp " + hargaNominal.toLocaleString("id-ID");

    // Targetkan kontainer produk
    let container = document.querySelector(".products");

    // Buat elemen card baru
    let newCard = document.createElement("div");
    // Tambahkan class 'card' dan kategori (misal: 'sneakers') agar sesuai CSS
    newCard.className = `card ${kategori}`; 
    
    // Set isi HTML card (sesuaikan dengan struktur index.html Anda)
    newCard.innerHTML = `
        <img src="placeholder.jpg"> <!-- Anda bisa ganti placeholder.jpg dengan link gambar asli -->
        <h3>${nama}</h3>
        <p class="price">${hargaFormat}</p>
        <button onclick="event.stopPropagation(); addCart('${nama}', ${hargaNominal})">+ Keranjang</button>
    `;

    // Pasangkan fungsi showDetail agar produk baru bisa diklik juga
    newCard.onclick = function() {
        showDetail(nama, hargaFormat, "placeholder.jpg", "Produk baru ditambahkan oleh Admin");
    };

    // Masukkan ke katalog
    container.appendChild(newCard);

    // Reset form admin
    document.getElementById("newNama").value = "";
    document.getElementById("newHarga").value = "";
    document.getElementById("newKategori").value = "";

    alert("Produk " + nama + " Berhasil Muncul di Katalog!");
}

// --- 8. SIMULASI PEMBAYARAN (Update Fungsi Checkout) ---
// Note: Ganti fungsi checkout lama dengan ini atau tambahkan logika ini di dalamnya
function simulasiPembayaran(metode, nominal) {
    console.log("Memulai simulasi " + metode);
    // Simulasi loading 2 detik seolah-olah sedang memproses ke API Bank/E-wallet
    setTimeout(() => {
        alert("Sistem " + metode + " berhasil memverifikasi pembayaran sebesar Rp " + nominal.toLocaleString("id-ID"));
    }, 2000);
}

// Tambahkan pemanggilan simulasiPembayaran() di dalam fungsi checkout() Anda yang sudah ada.
function showRegister() {
  document.getElementById('loginForm').style.display = 'none';
  document.getElementById('registerForm').style.display = 'block';
}
function showLogin() {
  document.getElementById('loginForm').style.display = 'block';
  document.getElementById('registerForm').style.display = 'none';
}