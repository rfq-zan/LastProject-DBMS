<?php
require_once("../config/connection.php");

$sql = "SELECT id_kategori, nama_kategori FROM kategori_obat ORDER BY nama_kategori ASC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
