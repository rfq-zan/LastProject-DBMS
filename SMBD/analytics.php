<?php include("nav.php"); ?>
<?php include("config/connection.php"); ?>

<?php
function fetchPenjualanPerObat($conn) {
    $sql = "SELECT * FROM view_penjualan_per_obat";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query error (Penjualan Per Obat): " . $conn->error);
    }
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function fetchPenjualanBulanan($conn) {
    $sql = "SELECT * FROM view_penjualan_bulanan";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function fetchAudit($conn) {
$sql = "SELECT * FROM audit_log ORDER BY id ASC";
$result = $conn->query($sql);
return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function fetchObatTerlaris($conn) {
    $sql = "SELECT * FROM view_obat_terlaris ORDER BY total_terjual DESC";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function fetchTransaksiDetail($conn) {
    $sql = "SELECT * FROM view_transaksi_detail ORDER BY tanggal DESC";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}

function fetchPelangganPoinDanTransaksi($conn) {
    $sql = "SELECT * FROM view_pelanggan_poin_dan_transaksi ORDER BY total_transaksi DESC";
    return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penjualan Obat</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
    body {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        color: #cbdcff;
    }
    h4 {
        color: #00DFD8;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    table {
        background-color: #203a43;
        color: #cbdcff;
    }
    thead.thead-dark {
        background-color: #2c5364;
        color: #00DFD8;
    }
    table.table-bordered th,
    table.table-bordered td {
        border: 1px solid #00DFD8;
    }
    table.table-striped tbody tr:nth-of-type(odd) {
        background-color: #203a43;
    }
    table.table-striped tbody tr:nth-of-type(even) {
        background-color: #2c5364;
    }
    table.table-striped tbody tr:hover {
        background-color: #007CF0;
        color: #cbdcff;
    }
    .table-responsive {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 15px #007CF0;
        margin-bottom: 3rem;
    }
</style>

<body>
    <div class="container mt-2">
        <div class="row">
    <div class="container mt-4">
        <h4>Penjualan Per Obat</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Obat</th>
                        <th>Nama Obat</th>
                        <th>Total Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchPenjualanPerObat($conn) as $row): ?>
                        <tr>
                            <td><?= $row['id_obat'] ?></td>
                            <td><?= $row['nama_obat'] ?></td>
                            <td><?= $row['total_terjual'] ?></td>
                            <td>Rp<?= number_format($row['total_pendapatan'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-5">Penjualan Bulanan</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Bulan</th>
                        <th>Jumlah Transaksi</th>
                        <th>Total Penjualan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchPenjualanBulanan($conn) as $row): ?>
                        <tr>
                            <td><?= $row['bulan'] ?></td>
                            <td><?= $row['jumlah_transaksi'] ?></td>
                            <td>Rp<?= number_format($row['total_penjualan'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <h4 class="mt-5">Obat Terlaris</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Obat</th>
                        <th>Nama</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchObatTerlaris($conn) as $row): ?>
                        <tr>
                            <td><?= $row['id_obat'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['total_terjual'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-5">Pelanggan dan Poin</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Pelanggan</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Poin</th>
                        <th>Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchPelangganPoinDanTransaksi($conn) as $row): ?>
                        <tr>
                            <td><?= $row['id_pelanggan'] ?></td>
                            <td><?= $row['nama_pelanggan'] ?></td>
                            <td><?= $row['telepon'] ?></td>
                            <td><?= $row['poin'] ?></td>
                            <td><?= number_format($row['total_transaksi'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h4 class="mt-5">Detail Transaksi</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Obat</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchTransaksiDetail($conn) as $row): ?>
                        <tr>
                            <td><?= $row['id_transaksi'] ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['pelanggan'] ?></td>
                            <td><?= $row['nama_obat'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <h4 class="mt-5">Audit</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID Audit</th>
                        <th>Tabel</th>
                        <th>ID Record Audit</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (fetchAudit($conn) as $row): ?>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['tabel']) ?></td>
                        <td><?= htmlspecialchars($row['id_record']) ?></td>
                        <td><?= htmlspecialchars($row['aksi']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td><?= htmlspecialchars($row['waktu']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
