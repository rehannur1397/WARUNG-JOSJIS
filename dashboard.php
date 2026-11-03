<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin.php");
    exit;
}

include "koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM pesanan ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Warung Josjis</title>
</head>
<body>

<h1>Dashboard Admin Warung Josjis</h1>
<p>
    <a href="produk.php">+ Tambah Produk</a>
</p>
<h2>Daftar Pesanan Pelanggan</h2>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID Pesanan</th>
        <th>Nama Pembeli</th>
        <th>Alamat</th>
        <th>Total</th>
        <th>Tanggal</th>
        <th>Status</th>
    </tr>

    <?php while ($pesanan = mysqli_fetch_assoc($query)) { ?>
    <tr>
        <td><?php echo $pesanan['id_pesanan']; ?></td>
        <td><?php echo htmlspecialchars($pesanan['nama_pembeli']); ?></td>
        <td><?php echo htmlspecialchars($pesanan['alamat']); ?></td>
        <td>Rp<?php echo number_format($pesanan['total'], 0, ',', '.'); ?></td>
        <td><?php echo $pesanan['tanggal']; ?></td>
        <td><?php echo htmlspecialchars($pesanan['status']); ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>