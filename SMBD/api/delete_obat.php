<?php
include("../config/connection.php");

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_obat'])) {
    $id_obat = $_POST['id_obat'];

    $sql = "DELETE FROM obat WHERE id_obat = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_obat);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan atau tidak memenuhi syarat hapus']);
        }
    } else {
        echo json_encode(value: ['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}
?>
