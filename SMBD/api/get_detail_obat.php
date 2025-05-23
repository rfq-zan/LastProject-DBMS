<?php
require_once("../config/connection.php");
header('Content-Type: application/json');

$id = $_GET['id_obat'];

if (!$id) {
    echo json_encode(["error" => "ID Obat tidak diberikan"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM view_stok_obat_kategori WHERE id_obat = ?");
    $stmt->execute([$id]);
    $obat = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$obat) {
        echo json_encode(["error" => "Data tidak ditemukan"]);
        exit;
    }

    $stmt2 = $pdo->prepare("SELECT tanggal_kadaluarsa, hari_menuju_kadaluarsa FROM view_obat_kadaluarsa WHERE id_obat = ?");
    $stmt2->execute([$id]);
    $kadaluarsa = $stmt2->fetch(PDO::FETCH_ASSOC);

    $result = $obat;
    if ($kadaluarsa) {
        $result = array_merge($result, $kadaluarsa);
    } else {
        $result['tanggal_kadaluarsa'] = null;
        $result['hari_menuju_kadaluarsa'] = null;
    }

    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
