
<?php
include 'koneksi.php';

$query = mysqli_query($conn, "SELECT * FROM produk");
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Josjis</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f8f5;
            color: #333;
        }

        header {
            background: #16834a;
            color: white;
            padding: 20px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .hero {
            background: #e0f2e7;
            padding: 55px 8%;
            text-align: center;
        }

        .hero h1 {
            color: #16834a;
            margin-bottom: 15px;
        }

        .hero p {
            margin-bottom: 20px;
        }

        .produk, .keranjang {
            padding: 40px 8%;
        }

        .produk h2, .keranjang h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .daftar-produk {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(170px, 1fr));
            gap: 20px;
        }

        .kartu {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px #00000010;
        }

        .gambar {
            font-size: 45px;
            margin-bottom: 12px;
        }

        .kartu h3 {
            margin-bottom: 10px;
        }

        .harga {
            color: #16834a;
            font-weight: bold;
            margin-bottom: 15px;
        }

        button {
            background: #16834a;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #106638;
        }

        .kotak-keranjang {
            background: white;
            padding: 22px;
            border-radius: 10px;
            max-width: 650px;
            margin: auto;
            box-shadow: 0 2px 8px #00000010;
        }

        .item-keranjang {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #ddd;
        }

        .jumlah button {
            padding: 5px 10px;
            margin: 2px;
        }

        .total {
            margin-top: 20px;
            font-size: 19px;
            font-weight: bold;
            color: #16834a;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-family: Arial, sans-serif;
        }

        .checkout {
            width: 100%;
            margin-top: 18px;
            padding: 13px;
        }

        footer {
            background: #16834a;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
        .gambar {
    width: 100%;
    height: 150px;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.gambar img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
    </style>
</head>

<body>

<header>
    <h2>Warung Josjis</h2>
    <nav>
        <a href="#">Beranda</a>
        <a href="#produk">Produk</a>
        <a href="#keranjang">Keranjang</a>
    </nav>
</header>

<section class="hero">
    <h1>Selamat Datang di Warung Josjis</h1>
    <p>Belanja kebutuhan sehari-hari lebih mudah dan praktis.</p>
</section>

<section class="produk" id="produk">
    <h2>Daftar Produk Kami</h2>
    <input
    type="text"
    id="cariProduk"
    placeholder="Cari barang di Warung Josjis..."
    onkeyup="cariBarang()"
    >

    <select id="filterKategori" onchange="cariBarang()">
    <option value="semua">Semua Kategori</option>
    <option value="sembako">Sembako</option>
    <option value="makanan">Makanan & Minuman</option>
    <option value="rumah">Kebutuhan Rumah Tangga</option>
</select>
<div class="daftar-produk">

        <?php
while ($produk = mysqli_fetch_assoc($query)) :
    $nama = $produk['nama_produk'];

    $gambar = [
    'Beras' => 'beras.jpeg',
    'Minyak Goreng' => 'minyak.jpeg',
    'Mi Instan' => 'mie.jpg',
    'Tepung Terigu' => 'tepung.jpeg',
    'Gula' => 'gula.jpg',
    'Telur Ayam' => 'telur.jpg',
    'Garam' => 'garam.jpg',
    'Kopi Sachet' => 'kopi.jpg',
    'Air Mineral' => 'air.jpg',
    'Susu Kental Manis' => 'susu.jpg',
    'Sabun Mandi' => 'sabun.jpg',
    'Deterjen' => 'deterjen.jpg',
    'Kecap Manis' => 'kecap.jpg'
];

    $fileGambar = $gambar[$nama] ?? '';
?>

<div class="kartu">
    <div class="gambar">
        <img src="gambar/<?= $fileGambar ?>"
             alt="<?= htmlspecialchars($nama) ?>">
    </div>

    <h3><?= htmlspecialchars($nama) ?></h3>

    <p class="harga">
        Rp<?= number_format($produk['harga'], 0, ',', '.') ?>
    </p>

    <button onclick="tambahKeranjang(
        '<?= htmlspecialchars($nama, ENT_QUOTES) ?>',
        <?= (int)$produk['harga'] ?>
    )">
        Pesan
    </button>
</div>

<?php endwhile; ?>
    </div>
</section>

<section class="keranjang" id="keranjang">
    <h2>Keranjang Belanja</h2>

    <div class="kotak-keranjang">

        <div id="daftar-keranjang">
            Keranjang masih kosong.
        </div>

        <div class="total" id="total">
            Total: Rp0
        </div>

        <h3 style="margin-top:25px;">Data Pemesan</h3>

        <input type="text" id="nama"
            placeholder="Masukkan nama pembeli">

        <textarea id="alamat"
            placeholder="Masukkan alamat atau keterangan pengambilan"
            rows="3"></textarea>

        <button class="checkout" onclick="kirimPesanan()">
            Pesan melalui WhatsApp
        </button>

    </div>
</section>

<footer>
    <p>&copy; 2026 Warung Josjis</p>
    <p>Pemesanan Barang Toko Kelontong Berbasis Web</p>
</footer>

<script>

    let keranjang = [];

    function tambahKeranjang(nama, harga) {

        let barang = keranjang.find(item => item.nama === nama);

        if (barang) {
            barang.jumlah++;
        } else {
            keranjang.push({
                nama: nama,
                harga: harga,
                jumlah: 1
            });
        }

        tampilkanKeranjang();
    }

    function ubahJumlah(index, perubahan) {

        keranjang[index].jumlah += perubahan;

        if (keranjang[index].jumlah <= 0) {
            keranjang.splice(index, 1);
        }

        tampilkanKeranjang();
    }

    function tampilkanKeranjang() {

        let daftar = document.getElementById("daftar-keranjang");
        let total = 0;

        if (keranjang.length === 0) {
            daftar.innerHTML = "Keranjang masih kosong.";
        } else {

            daftar.innerHTML = "";

            keranjang.forEach((item, index) => {

                total += item.harga * item.jumlah;

                daftar.innerHTML += `
                    <div class="item-keranjang">
                        <div>
                            <b>${item.nama}</b><br>
                            Rp${item.harga.toLocaleString("id-ID")}
                        </div>

                        <div class="jumlah">
                            <button onclick="ubahJumlah(${index}, -1)">-</button>
                            ${item.jumlah}
                            <button onclick="ubahJumlah(${index}, 1)">+</button>
                        </div>
                    </div>
                `;
            });
        }

        document.getElementById("total").innerText =
            "Total: Rp" + total.toLocaleString("id-ID");
    }

    function kirimPesanan() {

        let nama = document.getElementById("nama").value.trim();
        let alamat = document.getElementById("alamat").value.trim();

        if (keranjang.length === 0) {
            alert("Silakan pilih barang terlebih dahulu.");
            return;
        }

        if (nama === "" || alamat === "") {
            alert("Silakan isi nama dan alamat terlebih dahulu.");
            return;
        }

        let total = 0;
        let pesan = "Halo Warung Josjis, saya ingin memesan:\n\n";

        keranjang.forEach(item => {

            let subtotal = item.harga * item.jumlah;
            total += subtotal;

            pesan += item.nama + " x " + item.jumlah +
                " = Rp" + subtotal.toLocaleString("id-ID") + "\n";
        });

        pesan += "\nTotal: Rp" + total.toLocaleString("id-ID");
        pesan += "\nNama: " + nama;
        pesan += "\nAlamat/Keterangan: " + alamat;

        // GANTI NOMOR INI DENGAN NOMOR WHATSAPP PEMILIK TOKO
        let nomorWhatsApp = "6285733058262";

        let url = "https://wa.me/" + nomorWhatsApp +
            "?text=" + encodeURIComponent(pesan);

        window.open(url, "_blank");
    }
        function cariBarang() {
    let input = document.getElementById("cariProduk").value.toLowerCase();
    let filter = document.getElementById("filterKategori").value;

    let produk = document.querySelectorAll(".kartu");

    let kategoriProduk = {
        "beras": "sembako",
        "minyak goreng": "sembako",
        "tepung terigu": "sembako",
        "gula": "sembako",
        "telur ayam": "sembako",
        "garam": "sembako",
        "kecap manis": "sembako",
        "mi instan": "makanan",
        "kopi sachet": "makanan",
        "air mineral": "makanan",
        "susu kental manis": "makanan",
        "sabun mandi": "rumah",
        "deterjen": "rumah"
    };

    produk.forEach(function(item) {
        let nama = item.textContent.toLowerCase();

        let kategori = Object.keys(kategoriProduk).find(function(namaProduk) {
            return nama.includes(namaProduk);
        });

        let cocokNama = nama.includes(input);
        let cocokKategori = filter === "semua" || kategoriProduk[kategori] === filter;

        if (cocokNama && cocokKategori) {
            item.style.display = "";
        } else {
            item.style.display = "none";
        }
    });
}
   

</script>

</body>
</html>