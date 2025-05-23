<?php
session_start();
include '../config/connection.php';

$telepon = isset($_POST['telepon']) ? $_POST['telepon'] : '';
$keranjang = isset($_POST['keranjang']) ? $_POST['keranjang'] : array();

header('Content-Type: application/json');

if (!$telepon || empty($keranjang)) {
    echo json_encode(array('success' => false, 'message' => 'Data telepon atau keranjang kosong'));
    exit;
}


$conn->begin_transaction();

try {
    $stmtPelanggan = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE telepon = ?");
    if (!$stmtPelanggan) throw new Exception("Gagal menyiapkan statement pelanggan.");

    $stmtPelanggan->bind_param("s", $telepon);
    $stmtPelanggan->execute();
    $stmtPelanggan->bind_result($id_pelanggan);
    if (!$stmtPelanggan->fetch()) {
        throw new Exception("Pelanggan tidak ditemukan.");
    }
    $stmtPelanggan->close();


    $keranjang_tergabung = [];
    foreach ($keranjang as $item) {
        $id_obat = intval($item['id_obat']);
        $jumlah = intval($item['jumlah']);
        if (!isset($keranjang_tergabung[$id_obat])) {
            $keranjang_tergabung[$id_obat] = $jumlah;
        } else {
            $keranjang_tergabung[$id_obat] += $jumlah;
        }
    }
        file_put_contents('debug_keranjang.log', print_r($keranjang_tergabung, true));


    $total = 0;
    $detail = [];

    foreach ($keranjang_tergabung as $id_obat => $jumlah) {
        $stmtObat = $conn->prepare("SELECT harga_jual, stok FROM obat WHERE id_obat = ?");
        if (!$stmtObat) throw new Exception("Gagal prepare SELECT obat.");

        $stmtObat->bind_param("i", $id_obat);
        $stmtObat->execute();
        $stmtObat->bind_result($harga_jual, $stok);
        if (!$stmtObat->fetch()) {
            throw new Exception("Obat ID $id_obat tidak ditemukan.");
        }
        $stmtObat->close();

        if ($stok < $jumlah) {
            throw new Exception("Stok tidak cukup untuk obat ID $id_obat.");
        }

        $subtotal = $harga_jual * $jumlah;
        $total += $subtotal;

        $detail[] = [
            'id_obat' => $id_obat,
            'jumlah' => $jumlah,
            'harga' => $harga_jual,
            'subtotal' => $subtotal
        ];
    }

    $stmtTransaksi = $conn->prepare("INSERT INTO transaksi (id_pelanggan, total) VALUES (?, ?)");
    if (!$stmtTransaksi) throw new Exception("Gagal prepare INSERT transaksi.");

    $stmtTransaksi->bind_param("id", $id_pelanggan, $total);
    if (!$stmtTransaksi->execute()) throw new Exception("Gagal insert transaksi: " . $stmtTransaksi->error);
    $id_transaksi = $conn->insert_id;
    $stmtTransaksi->close();
$stmtDetail = $conn->prepare("INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah, harga, subtotal) VALUES (?, ?, ?, ?, ?)");

foreach ($detail as $d) {
    $stmtDetail->bind_param("iiidd", $id_transaksi, $d['id_obat'], $d['jumlah'], $d['harga'], $d['subtotal']);
    if (!$stmtDetail->execute()) throw new Exception("Gagal insert detail_transaksi.");

    $stmtStok = $conn->prepare("UPDATE obat SET stok = GREATEST(stok - ?, 0) WHERE id_obat = ?");
    if (!$stmtStok) throw new Exception("Gagal prepare update stok.");
}



$stmtDetail->close();



    $poin_didapat = floor($total / 5000) * 10;
    $stmtPoin = $conn->prepare("UPDATE pelanggan SET poin = poin + ? WHERE id_pelanggan = ?");
    $stmtPoin->bind_param("ii", $poin_didapat, $id_pelanggan);
    if (!$stmtPoin->execute()) throw new Exception("Gagal update poin.");
    $stmtPoin->close();

    $conn->query("INSERT INTO audit_log (tabel, id_record, aksi, keterangan) 
                VALUES ('pelanggan', $id_pelanggan, 'UPDATE', 'Poin pelanggan ditambah $poin_didapat')");

    $conn->commit();

    $nota = "Pesan: Pembelian berhasil\n";
    $nota .= "Nomor Transaksi: $id_transaksi\n";
    $nota .= "Total: Rp " . number_format($total, 0, ',', '.') . "\n";
    $nota .= "Poin Didapat: $poin_didapat\n";
    error_log("Updating stock for obat ID: " . $d['id_obat'] . ", reducing by: " . $d['jumlah']);

    echo json_encode(['success' => true, 'nota' => $nota]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
