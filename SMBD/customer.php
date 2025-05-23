<?php
include 'config/connection.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM pelanggan WHERE id_pelanggan = $id");
    echo "<script>alert('Data berhasil dihapus'); window.location='customer.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM pelanggan WHERE telepon = ? AND id_pelanggan != ?");
    $stmt->bind_param("si", $telepon, $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Nomor telepon sudah digunakan pelanggan lain!');</script>";
    } else {
        $stmt = $conn->prepare("UPDATE pelanggan SET nama=?, telepon=? WHERE id_pelanggan=?");
        $stmt->bind_param("ssi", $nama, $telepon, $id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Data berhasil diperbarui'); window.location='customer.php';</script>";
        exit;
    }
}

$pelanggan = $conn->query("SELECT * FROM pelanggan");

$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $result = $conn->query("SELECT * FROM pelanggan WHERE id_pelanggan = $idEdit");
    if ($result->num_rows > 0) {
        $editData = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    body {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        color: #cbdcff;
    }
    .container {
        margin-top: 40px;
    }
    h2, h4 {
        color: #007CF0;
    }
    h2 {
    color: #007CF0 !important;
}
    table#tabelPelanggan {
        background-color: #203a43;
        color: #cbdcff;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #00DFD8;
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
    }
    table#tabelPelanggan thead tr {
        background-color: #2c5364;
        color: #00DFD8;
    }
    table#tabelPelanggan th,
    table#tabelPelanggan td {
        padding: 12px 15px;
        border: 1px solid #00DFD8;
        vertical-align: middle;
        text-align: center;
    }
    table#tabelPelanggan tbody tr:nth-child(odd) {
        background-color: #203a43;
    }
    table#tabelPelanggan tbody tr:nth-child(even) {
        background-color: #2c5364;
    }
    table#tabelPelanggan tbody tr:hover {
        background-color: #007CF0;
        color: #cbdcff;
    }
    .btn-warning {
        background-color: #00DFD8;
        border-color: #00DFD8;
        color: #203a43;
    }
    .btn-warning:hover {
        background-color: #007CF0;
        border-color: #007CF0;
        color: #cbdcff;
    }
    .btn-danger {
        background-color: #c43a3a;
        border-color: #c43a3a;
        color: #cbdcff;
    }
    .btn-danger:hover {
        background-color: #a72b2b;
        border-color: #a72b2b;
        color: #fff;
    }
    .btn-success {
        background-color: #00DFD8;
        border-color: #00DFD8;
        color: #203a43;
    }
    .btn-success:hover {
        background-color: #007CF0;
        border-color: #007CF0;
        color: #cbdcff;
    }
    .btn-secondary {
        background-color: #203a43;
        border-color: #203a43;
        color: #cbdcff;
    }
    .btn-secondary:hover {
        background-color: #2c5364;
        border-color: #2c5364;
        color: #00DFD8;
    }
    .form-control {
        border-radius: 8px;
        background-color: #203a43;
        border: 1px solid #00DFD8;
        color: #cbdcff;
    }
    .form-control:focus {
        background-color: #2c5364;
        border-color: #007CF0;
        color: #cbdcff;
        box-shadow: none;
    }
    label {
        color: #00DFD8;
    }
</style>
</head>
<body>
    <?php include("nav.php"); ?> 

    <div class="container">
        <h2 class="mb-4">Data Pelanggan</h2>

        <?php if ($editData): ?>
            <h4>Edit Pelanggan</h4>
            <form method="post" class="mb-4">
                <input type="hidden" name="id" value="<?= $editData['id_pelanggan'] ?>">
                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($editData['nama']) ?>" required>
                </div>
                <div class="mb-3">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($editData['telepon']) ?>" required>
                </div>
                <button type="submit" name="update" class="btn btn-success">Simpan</button>
                <a href="customer.php" class="btn btn-secondary">Batal</a>
            </form>
        <?php endif; ?>

        <table id="tabelPelanggan" class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Poin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pelanggan->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['telepon']) ?></td>
                        <td><?= $row['poin'] ?></td>
                        <td>
                            <a href="customer.php?edit=<?= $row['id_pelanggan'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="customer.php?delete=<?= $row['id_pelanggan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- DataTables scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tabelPelanggan').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
                }
            });
        });
    </script>
</body>
</html>
