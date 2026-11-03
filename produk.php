<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit;
}

include "koneksi.php";

$pesan = "";

if (isset($_POST["tambah"])) {
    $nama = trim($_POST["nama_produk"]);
    $harga = (int) $_POST["harga"];
    $stok = (int) $_POST["stok"];

    if ($nama !== "" && $harga > 0 && $stok >= 0) {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO produk (nama_produk, harga, stok)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "sii", $nama, $harga, $stok);

        if (mysqli_stmt_execute($stmt)) {
            $pesan = "Produk berhasil ditambahkan!";
        } else {
            $pesan = "Gagal menambahkan produk.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $pesan = "Periksa kembali nama, harga, dan stok.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - Warung Josjis</title>
</head>
<body>

<h1>Tambah Produk Warung Josjis</h1>

<p><?php echo htmlspecialchars($pesan); ?></p>

<form method="POST">

    <label>Nama Produk:</label><br>
    <input type="text" name="nama_produk" required>

    <br><br>

    <label>Harga Produk:</label><br>
    <input type="number" name="harga" min="1" required>

    <br><br>

    <label>Jumlah Stok:</label><br>
    <input type="number" name="stok" min="0" required>

    <br><br>

    <button type="submit" name="tambah">
        Tambahkan Produk
    </button>

</form>

<br>

<a href="dashboard.php">Kembali ke Dashboard</a>

</body>
</html>