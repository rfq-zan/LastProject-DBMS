<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("../config/connection.php");

$id_obat = $_POST['id_obat'];
$nama = $_POST['nama'];
$id_kategori = $_POST['kategori_id'];
$harga_beli = $_POST['harga_beli']; 
$harga_jual = $_POST['harga_jual'];
$stok = $_POST['stok'];
$tanggal_kadaluarsa = $_POST['tanggal_kadaluarsa'];
$id_pemasok = $_POST['pemasok_id'];
$gambarBaru = null;

$queryOld = $conn->prepare("SELECT gambar FROM obat WHERE id_obat = ?");
$queryOld->bind_param("i", $id_obat);
$queryOld->execute();
$queryOld->bind_result($gambarLama);
$queryOld->fetch();
$queryOld->close();

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    $gambarBaru = uniqid("obat_") . '.' . $ext;
    move_uploaded_file($_FILES['gambar']['tmp_name'], "../images/" . $gambarBaru);
} else {
    $gambarBaru = $gambarLama; 
}

$sql = "UPDATE obat SET 
        nama = ?, 
        id_kategori = ?,
        harga_beli = ?, 
        harga_jual = ?, 
        stok = ?, 
        tanggal_kadaluarsa = ?, 
        id_pemasok = ?, 
        gambar = ?
        WHERE id_obat = ?";

if ($stmt = $conn->prepare($sql)) {
$stmt->bind_param("siisssisi", $nama, $id_kategori, $harga_beli, $harga_jual, $stok, $tanggal_kadaluarsa, $id_pemasok, $gambarBaru, $id_obat);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Obat berhasil diperbarui"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Obat gagal diperbarui"]);
        exit;
    }
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyiapkan query"]);
}

$conn->close();
?>
