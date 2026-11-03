<?php
header("Content-Type: application/json");
include "koneksi.php";

$data = json_decode(file_get_contents("php://input"), true);

$nama = trim($data["nama"] ?? "");
$alamat = trim($data["alamat"] ?? "");
$keranjang = $data["keranjang"] ?? [];

if ($nama == "" || $alamat == "" || empty($keranjang)) {
    echo json_encode([
        "status" => "error",
        "pesan" => "Data pesanan belum lengkap."
    ]);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $total = 0;
    $detail = [];

    foreach ($keranjang as $item) {
        $namaProduk = $item["nama"];
        $jumlah = (int) $item["jumlah"];

        if ($jumlah < 1) {
            throw new Exception("Jumlah produk tidak valid.");
        }

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id_produk, harga, stok
             FROM produk
             WHERE nama_produk = ?
             FOR UPDATE"
        );

        mysqli_stmt_bind_param($stmt, "s", $namaProduk);
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        $produk = mysqli_fetch_assoc($hasil);

        if (!$produk) {
            throw new Exception("Produk tidak ditemukan.");
        }

        if ($produk["stok"] < $jumlah) {
            throw new Exception("Stok produk tidak mencukupi.");
        }

        $subtotal = $produk["harga"] * $jumlah;
        $total += $subtotal;

        $detail[] = [
            "id_produk" => $produk["id_produk"],
            "jumlah" => $jumlah,
            "harga" => $produk["harga"]
        ];
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO pesanan (nama_pembeli, alamat, total)
         VALUES (?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "ssi", $nama, $alamat, $total);
    mysqli_stmt_execute($stmt);

    $idPesanan = mysqli_insert_id($conn);

    foreach ($detail as $item) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO detail_pesanan
             (id_pesanan, id_produk, jumlah, harga)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iiii",
            $idPesanan,
            $item["id_produk"],
            $item["jumlah"],
            $item["harga"]
        );

        mysqli_stmt_execute($stmt);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE produk SET stok = stok - ?
             WHERE id_produk = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $item["jumlah"],
            $item["id_produk"]
        );

        mysqli_stmt_execute($stmt);
    }

    mysqli_commit($conn);

    echo json_encode([
        "status" => "success",
        "pesan" => "Pesanan berhasil disimpan.",
        "id_pesanan" => $idPesanan,
        "total" => $total
    ]);

} catch (Exception $e) {
    mysqli_rollback($conn);

    echo json_encode([
        "status" => "error",
        "pesan" => $e->getMessage()
    ]);
}
?>