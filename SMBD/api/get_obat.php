<?php 
require_once("../config/connection.php"); 

try {
    $result = $conn->query("SELECT o.id_obat, o.nama, o.harga_beli, o.harga_jual, o.stok, o.gambar, o.tanggal_kadaluarsa, k.nama_kategori, p.nama_pemasok FROM obat o 
    LEFT JOIN kategori_obat k ON o.id_kategori = k.id_kategori LEFT JOIN pemasok p ON o.id_pemasok = p.id_pemasok ORDER BY o.id_obat DESC");
    


$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
echo json_encode(["data" => $data]);
?>
