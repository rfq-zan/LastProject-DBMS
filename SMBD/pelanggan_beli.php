<?php
session_start();
include 'config/connection.php';


if (!isset($_SESSION['telepon'])) {
    header("Location: login.html");
    exit;
}

$telepon = $_SESSION['telepon'];
$nama = $_SESSION['nama'];

$stmt = $conn->prepare("SELECT id_pelanggan FROM pelanggan WHERE telepon = ?");
$stmt->bind_param("s", $telepon);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$id_pelanggan = isset($data['id_pelanggan']) ? $data['id_pelanggan'] : null;


if (!$id_pelanggan) {
    echo "Gagal mengambil ID pelanggan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Medicine Sale</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert"></script>
    <!-- Bootstrap Bundle JS (includes Popper) for modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
body {
    background: linear-gradient(135deg, #e6f2ff, #cce6ff);
    color: #203a43;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    min-height: 100vh;
}

.container, .card, .table-responsive {
    background-color: #f0f8ff; 
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 115, 204, 0.15);
}

.navbar-dark.bg-primary {
    background-color: #203a43 !important; 
}

.navbar-brand {
    color: #cbdcff !important; 
    font-weight: 700;
    font-size: 1.25rem;
}

#keranjangButton {
    background-color: transparent;
    color: #00DFD8;
    border: 2px solid #00DFD8;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

#keranjangButton:hover {
    background: linear-gradient(to right, #007CF0, #00DFD8);
    color: white;
}

#keranjangCount {
    background-color: #00DFD8 !important;
    color: #203a43;
}

.card-header.bg-primary {
    background: linear-gradient(90deg, #203a43, #2c5364) !important;
    color: #cbdcff !important;
    font-weight: 600;
}

#medicineTable {
    background-color: white;
    color: #203a43;
    border: 1px solid #cbdcff;
    border-radius: 8px;
}

#medicineTable thead {
    background: linear-gradient(90deg, #203a43, #2c5364);
    color: #cbdcff;
}

#medicineTable thead th {
    font-weight: 600;
    border: none;
}

#medicineTable tbody tr:hover {
    background-color: #d4e9ff;
    color: #203a43;
}

#medicineTable tbody td {
    border-top: 1px solid #cbdcff;
}
</style>
<body>  
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand">WalMart Pharmacy</span>
        <button class="btn btn-light position-relative" id="keranjangButton">
            🛒 Keranjang 
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="keranjangCount">0</span>
        </button>
        <button class="btn btn-outline-light ms-2" onclick="window.location.href='login.php'">
            <i class="fas fa-sign-out-alt me-1"></i> Logout
        </button>
    </div>
</nav>
            <div id="originalTableContainer" class="">
                <div class="card mt-1">
                    <h3 class="card-header bg-primary text-white"></h3>
                    <div class="card-body">
                        <div class="table-responsive">
                <table id="medicineTable" class="table table-striped  table-bordered table-hover  ">
                    <thead>
                        <tr>
                                        <th>Nama Obat</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Tanggal Kadaluarsa</th>
                                        <th>Kategori</th>
                                        <th>Gambar</th>
                                        <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            </div>
            </div>
            </div>
        </div>
</div>
    </form>
  </div>
</div>


<script>
$(document).ready(function() {
    let table = $('#medicineTable').DataTable({
        destroy: true,
        ajax: {
            url: 'api/get_obat.php',
            data: ''
        },
        columns: [
            { data: 'nama' },
            { data: 'harga_jual' },
            { data: 'stok' },
            { data: 'tanggal_kadaluarsa' },
            {data: 'nama_kategori'},
            {
                data: 'gambar',
                render: function(data, type, row) {
                    if(data){
                        return `<img src="images/${data}" alt="${row.nama}" style="max-height:50px; max-width:50px;" />`;
                    } else {
                        return '-';
                    }
                },
                orderable: false,
                searchable: false
            },
            {
                data: null,
                render: function (data, type, row) {
                    if (row.stok <= 0) {
                        return `<button class="btn btn-sm btn-secondary" disabled>Stok Habis</button>`;
                    }
                    return `<button class="btn btn-sm btn-success btn-tambah" data-id="${row.id_obat}" data-nama="${row.nama}" data-stok="${row.stok}">Tambah</button>`;
                },
                orderable: false,
            }

        ],
        order: [[0, 'asc']]
    });
    let keranjang = [];

function updateKeranjangCount() {
    $('#keranjangCount').text(keranjang.length);
}

$(document).on('click', '.btn-tambah', function () {
    let id = $(this).data('id');
    let nama = $(this).data('nama');
    let stok = $(this).data('stok');

    let jumlah = prompt(`Masukkan jumlah untuk "${nama}" (Stok tersedia: ${stok})`);
    jumlah = parseInt(jumlah);

    if (isNaN(jumlah) || jumlah <= 0) {
        alert("Jumlah tidak valid");
        return;
    }

    if (jumlah > stok) {
        alert("Stok tidak cukup!");
        return;
    }
    

    keranjang.push({ id_obat: id, nama, jumlah });
    updateKeranjangCount();
});

$('#keranjangButton').click(function () {
    if (keranjang.length === 0) {
        alert("Keranjang kosong");
        return;
    }

    let isi = keranjang.map(item => `${item.nama} x ${item.jumlah}`).join('\n');
    if (confirm(`Konfirmasi pembelian:\n\n${isi}\n\nLanjutkan?`)) {
        let telepon = prompt("Masukkan nomor telepon pelanggan:");

        if (!telepon) {
            alert("Telepon diperlukan");
            return;
        }

        $.ajax({
            url: 'api/proses_pembelian.php',
            method: 'POST',
            data: {
                telepon: telepon,
                keranjang: keranjang
            },
            success: function (res) {
                alert("Pembelian berhasil!\n\nNota:\n" + res.nota);
                keranjang = [];
                updateKeranjangCount();
                table.ajax.reload();
            },
            error: function () {
                alert("Terjadi kesalahan saat pembelian.");
            }
        });
    }
});

});
</script>
</body>
</html>