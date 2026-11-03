<?php
include "koneksi.php";

$query = mysqli_query($conn, "SELECT * FROM produk");

while ($data = mysqli_fetch_assoc($query)) {
    echo $data['nama_produk'] . " - Rp" . $data['harga'] . "<br>";
}
?>