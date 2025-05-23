<?php
var_dump($_POST);
require_once '../config/connection.php';

$type = isset($_POST['filterType']) ? $_POST['filterType'] : '';

if ($type === 'stok_rendah' && isset($_POST['maxStok'])) {
    $batas = intval($_POST['maxStok']);
    $stmt = $conn->prepare("CALL tampil_obat_stok_rendah(?)");
    $stmt->bind_param("i", $batas);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='table table-bordered'>
    <tr>
    <th>ID Obat</th>
    <th>Nama Obat</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nama = $row['nama'];
        echo "<tr>
        <td>{$id}</td>
        <td>{$nama}</td>
        </tr>";
    }
    echo "</table>";
    $stmt->close();

} elseif ($type === 'kategori' && isset($_POST['kategori'])) {
    $kategori = intval($_POST['kategori']);
    $stmt = $conn->prepare("CALL tampil_obat_by_kategori(?)");
    $stmt->bind_param("i", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='table table-bordered'>
    <tr>
    <th>ID Obat</th>
    <th>Nama Obat</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nama = $row['nama'];
        echo "<tr>
        <td>{$id}</td>
        <td>{$nama}</td>
        </tr>";
    }
    echo "</table>";
    $stmt->close();

} elseif ($type === 'kadaluarsa' && isset($_POST['days'])) {
    $days = intval($_POST['days']);
    $stmt = $conn->prepare("CALL tampil_obat_mau_kadaluarsa(?)");
    $stmt->bind_param("i", $days);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<table class='table table-bordered'>
    <tr>
    <th>ID Obat</th>
    <th>Nama Obat</th>
    <th>Kadaluarsa</th>
    </tr>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nama = $row['nama'];
        $tgl = $row['tanggal_kadaluarsa'];
        echo "<tr>
        <td>{$id}</td>
        <td>{$nama}</td>
        <td>{$tgl}</td>
        </tr>";
    }
    echo "</table>";
    $stmt->close();

} else {
    echo "<div class='alert alert-info'>Silakan pilih filter yang valid dan pastikan parameter yang dibutuhkan dikirim.</div>";
}




$conn->close();
?>
