<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once("config/connection.php");  

header('Content-Type: application/json');

$nama             = isset($_POST['nama']) ? $_POST['nama'] : '';
$id_kategori      = isset($_POST['id_kategori']) ? intval($_POST['id_kategori']) : 0;
$id_pemasok       = isset($_POST['id_pemasok']) ? intval($_POST['id_pemasok']) : 0;
$stok             = isset($_POST['stok']) ? intval($_POST['stok']) : 0;
$tanggal_kadaluarsa = isset($_POST['tanggal_kadaluarsa']) ? $_POST['tanggal_kadaluarsa'] : '';
$harga_beli       = isset($_POST['harga_beli']) ? floatval($_POST['harga_beli']) : 0;
$harga_jual       = isset($_POST['harga_jual']) ? floatval($_POST['harga_jual']) : 0;
$gambarName       = isset($_FILES['image']['name']) ? $_FILES['image']['name'] : '';

if (!$nama || !$id_kategori || !$id_pemasok || $stok < 1 || !$tanggal_kadaluarsa || $harga_jual < $harga_beli) {
    echo json_encode(['error' => 'Data tidak valid atau harga jual lebih rendah dari harga beli']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'Gagal mengupload gambar']);
    exit;
}

$allowed = ['image/jpeg', 'image/png', 'image/jpg'];
if (!in_array($_FILES['image']['type'], $allowed)) {
    echo json_encode(['error' => 'Gambar harus format jpg/jpeg/png']);
    exit;
}

if (!is_dir('images')) {
    mkdir('images', 0755, true);
}

$gambarName = time() . '_' . basename($_FILES['image']['name']);
$targetPath = 'images/' . $gambarName;

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$namaFileGambar = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($nama)) . '_' . time() . '.' . $ext;
$targetPath = 'images/' . $namaFileGambar;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
    echo json_encode(['error' => 'Gagal menyimpan file gambar']);
    exit;
}

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("INSERT INTO obat (nama, id_kategori, harga_beli, harga_jual, stok, tanggal_kadaluarsa, id_pemasok, gambar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        throw new Exception("Failed to prepare statement: " . $conn->error);
    }

    $stmt->bind_param("sddiisis", $nama, $id_kategori, $harga_beli, $harga_jual, $stok, $tanggal_kadaluarsa, $id_pemasok, $namaFileGambar);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $conn->commit();

    echo json_encode(['message' => 'Data obat berhasil ditambahkan']);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage(),
        'code' => $e->getCode()
    ]);
    exit;
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
