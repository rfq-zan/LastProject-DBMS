<?php
require_once("../config/connection.php");

$sql = "SELECT id_pemasok, nama_pemasok FROM pemasok ORDER BY nama_pemasok ASC";
$result = $conn->query($sql);

$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
