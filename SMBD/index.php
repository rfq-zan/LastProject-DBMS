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
    background: #0f2027;  
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color: #e0e6f1;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.card {
    background: rgba(15, 32, 39, 0.85);
    border-radius: 15px;
    border: 1px solid #3a8ddb;
    box-shadow: 0 8px 20px rgba(58, 141, 219, 0.25);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(58, 141, 219, 0.45);
}

.card-header {
    background: linear-gradient(90deg, #007CF0, #00DFD8);
    font-weight: 700;
    font-size: 1.4rem;
    letter-spacing: 0.05em;
    border-bottom: none;
    border-radius: 15px 15px 0 0;
    color: #fff;
    text-shadow: 0 0 8px #00dff0;
}

.form-label, label {
    color: #a9c9ff;
    font-weight: 600;
    font-size: 0.95rem;
}

.form-control, .form-select {
    background: #122c54;
    border: 1.5px solid #3a8ddb;
    color: #cbdcff;
    border-radius: 10px;
    transition: border-color 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #00dfff;
    box-shadow: 0 0 10px #00dfff;
    background: #1a3a72;
    color: #e0e6f1;
}

.btn-primary {
    background: linear-gradient(90deg, #007CF0, #00DFD8);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    letter-spacing: 0.05em;
    box-shadow: 0 6px 12px rgba(0, 223, 216, 0.6);
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

.btn-primary:hover, .btn-primary:focus {
    background: linear-gradient(90deg, #00DFD8, #007CF0);
    box-shadow: 0 10px 25px rgba(0, 223, 216, 0.9);
}

.btn-secondary {
    background: #375a7f;
    border-radius: 12px;
    font-weight: 600;
    color: #cbdcff;
    border: none;
    box-shadow: 0 3px 10px rgba(55, 90, 127, 0.5);
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #2a4567;
}

.table {
    background: #122c54;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 140, 255, 0.4);
}

.table thead tr {
    background: linear-gradient(90deg, #007CF0, #00DFD8);
    color: #fff;
    font-weight: 700;
    letter-spacing: 0.05em;
}

.table tbody tr {
    color: #cbdcff;
    transition: background-color 0.25s ease;
}

.table tbody tr:hover {
    background: rgba(0, 223, 216, 0.15);
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
    border: none;
    padding: 15px 12px;
}

#filterForm {
    background: rgba(15, 32, 39, 0.9);
    padding: 15px 20px;
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 140, 255, 0.3);
}

#filterResult {
    background: rgba(15, 32, 39, 0.9);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 6px 20px rgba(0, 140, 255, 0.4);
}

.modal-content {
    background: #122c54;
    border-radius: 15px;
    border: 1px solid #3a8ddb;
    color: #cbdcff;
    box-shadow: 0 8px 30px rgba(0, 223, 216, 0.5);
}

.modal-header {
    background: linear-gradient(90deg, #007CF0, #00DFD8);
    border-bottom: none;
    border-radius: 15px 15px 0 0;
    color: white;
    font-weight: 700;
    letter-spacing: 0.05em;
    box-shadow: 0 4px 10px rgba(0, 223, 216, 0.6);
}

.modal-footer {
    background: #122c54;
    border-top: none;
    border-radius: 0 0 15px 15px;
}

.btn-close {
    filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(93deg) brightness(103%) contrast(103%);
    opacity: 0.75;
}

.btn-close:hover {
    opacity: 1;
}

#currentImage img {
    max-width: 120px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 223, 216, 0.7);
}

.table-responsive {
    max-height: 480px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #00dfff #122c54;
}

.table-responsive::-webkit-scrollbar {
    width: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #122c54;
    border-radius: 15px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background-color: #00dfff;
    border-radius: 15px;
    border: 2px solid #122c54;
}

</style>
<body>
    <?php include("nav.php"); ?> 
    <div class="column">

    <div class="col-md-5  ">
  <div class="card mt-1">
    <h3 class="card-header bg-primary text-white border-bottom border-light">Filter Obat</h3>
    <div class="card-body">
      <form id="filterForm">
        <div class="form-group">
          <label for="filterSelect">Pilih Filter</label>
          <select id="filterSelect" name="filterType" class="form-control">
            <option value="">-- Pilih Filter --</option>
            <option value="stok_rendah">Lihat Stok Rendah</option>
            <option value="kategori">Lihat Berdasarkan Kategori</option>
            <option value="kadaluarsa">Obat Mau Kadaluarsa</option>
          </select>
        </div>

        <div id="filterFields" class="form-group mt-2"></div>

        <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">Tampilkan</button>
      </form>
    </div>
  </div>
</div>

<div id="filterResult" class="d-none"></div>

            <div id="originalTableContainer" class="">
                <div class="card mt-1">
                    <h3 class="card-header bg-primary text-white"></h3>
                    <div class="card-body">
                        <div class="table-responsive">
                <table id="medicineTable" class="table table-striped  table-bordered table-hover  ">
                    <thead>
                        <tr>
                                        <th>ID Obat</th>
                                        <th>Nama Obat</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                        <th>Tanggal Kadaluarsa</th>
                                        <th>Pemasok</th>
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

<div class="modal fade" id="editMedicineModal" tabindex="-1" aria-labelledby="editMedicineLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditObat" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editMedicineLabel">Edit Obat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_obat" id="edit_id_obat">

          <div class="mb-3">
            <label for="edit_nama" class="form-label">Nama Obat</label>
            <input type="text" class="form-control" id="edit_nama" name="nama" required>
          </div>

          <div class="mb-3">
            <label for="edit_kategori" class="form-label">Kategori</label>
            <select class="form-select" id="edit_kategori" name="kategori_id" required>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_pemasok" class="form-label">Pemasok</label>
            <select class="form-select" id="edit_pemasok" name="pemasok_id" required>
            </select>
          </div>

            <div class="mb-3">
                <label for="edit_harga_beli" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" id="edit_harga_beli" name="harga_beli" required>
            </div>

          <div class="mb-3">
            <label for="edit_harga_jual" class="form-label">Harga Jual</label>
            <input type="number" class="form-control" id="edit_harga_jual" name="harga_jual" required>
          </div>

          <div class="mb-3">
            <label for="edit_stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="edit_stok" name="stok" required>
          </div>

          <div class="mb-3">
            <label for="edit_kadaluarsa" class="form-label">Kadaluarsa</label>
            <input type="date" class="form-control" id="edit_kadaluarsa" name="tanggal_kadaluarsa" required>
          </div>

          <div class="mb-3">
            <label for="edit_gambar" class="form-label">Gambar Obat (biarkan kosong jika tidak diubah)</label>
            <input type="file" class="form-control" id="edit_gambar" name="gambar" accept="image/*">
          </div>

          <div id="currentImage" class="mb-3">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </div>
    </form>
  </div>
</div>


<script>
$(document).ready(function() {
      $.ajax({
        url: 'delete_out_of_stock.php',
        method: 'POST',
        success: function(response) {
            console.log('Delete out of stock response:', response);
            // Optionally refresh the table after deletion
            $('#medicineTable').DataTable().ajax.reload();
        },
        error: function(xhr, status, error) {
            console.error('Error calling delete_out_of_stock.php:', error);
        }
    });
    let table = $('#medicineTable').DataTable({
        destroy: true,
        ajax: {
            url: 'api/get_obat.php',
            data: ''
        },
        columns: [
            { data: 'id_obat' },
            { data: 'nama' },
            { data: 'harga_beli' },
            { data: 'harga_jual' },
            { data: 'stok' },
            { data: 'tanggal_kadaluarsa' },
            {data: 'nama_pemasok'},
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
                    return `
                        <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id_obat}">Edit</button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="${row.id_obat}">Delete</button>
                    `;
                },
                orderable: false,
            }
        ],
        order: [[0, 'asc']]
    });


let kategoriList = [];
let pemasokList = [];

function loadKategori(callback) {
  $.getJSON('api/get_kategori.php', function(data) {
    kategoriList = data.map(item => ({
      id: item.id_kategori,
      nama: item.nama_kategori
    }));
    if (callback) callback();
  });
}

function loadPemasok(callback) {
  $.getJSON('api/get_Pemasok.php', function(data) {
    pemasokList = data.map(item => ({
      id: item.id_pemasok,
      nama: item.nama_pemasok
    }));
    if (callback) callback();
  });
}

function fillSelectOptions(selector, list, selectedId) {
    let $select = $(selector);
    $select.empty();
    list.forEach(item => {
        let id = item.id;       
        let nama = item.nama;   
        let selected = id == selectedId ? 'selected' : '';
        $select.append(`<option value="${id}" ${selected}>${nama}</option>`);
    });
}

$('#medicineTable tbody').on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        let med = table.rows().data().toArray().find(m => m.id_obat == id);
        if (!med) return;

        function openEditModal() {
            $('#edit_id_obat').val(med.id_obat);
            $('#edit_nama').val(med.nama);
            fillSelectOptions('#edit_kategori', kategoriList, med.kategori_id);
            fillSelectOptions('#edit_pemasok', pemasokList, med.pemasok_id);
            $('#edit_harga_beli').val(med.harga_beli);
            $('#edit_harga_jual').val(med.harga_jual);
            $('#edit_stok').val(med.stok);
            $('#edit_kadaluarsa').val(med.tanggal_kadaluarsa?.slice(0, 10) || '');

            if (med.gambar_url) {
                $('#currentImage').html(`<img src="${med.gambar_url}" alt="Gambar Obat" style="max-width: 100px;">`);
            } else {
                $('#currentImage').html('Tidak ada gambar');
            }
            console.log(med);


            $('#editMedicineModal').modal('show');
        }

        if (kategoriList.length === 0 || pemasokList.length === 0) {
            loadKategori(function () {
                loadPemasok(function () {
                    openEditModal();
                });
            });
        } else {
            openEditModal();
        }
    });

    $('#formEditObat').on('submit', function(e) {
        e.preventDefault();
        console.log('Submit formEditObat triggered');

        let formData = new FormData(this);

        $.ajax({
            url: 'api/update_obat.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Response from server:', response);
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    $('#editMedicineModal').modal('hide');
                    alert('Obat berhasil diperbarui!');
                    table.ajax.reload();
                    location.reload();
                } else {
                    alert('Gagal memperbarui obat: ' + res.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Terjadi kesalahan saat mengupdate obat.");
                console.error(error);
            }
        });
    });
$('#medicineTable tbody').on('click', '.btn-delete', function() {
    let data = table.row($(this).parents('tr')).data();
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Obat: ${data.nama}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'api/delete_obat.php',
                type: 'POST',
                data: { id_obat: data.id_obat },
                  success: function(response) {
                      let res = (typeof response === 'string') ? JSON.parse(response) : response;
                      if (res.status === 'success') {
                          Swal.fire('Terhapus!', res.message, 'success');
                          table.ajax.reload(null, false); 
                      } else {
                          Swal.fire('Gagal!', res.message, 'error'); 
                      }
                  },
                error: function() {
                    Swal.fire('Error!', 'Gagal menghapus data. ', 'error');
                }
            });
        }
    });
});
});
document.addEventListener('DOMContentLoaded', function () {
  const filterSelect = document.getElementById("filterSelect");
  const filterFields = document.getElementById("filterFields");
  const filterForm = document.getElementById("filterForm");
  const filterResult = document.getElementById("filterResult");
  const originalTableContainer = document.getElementById("originalTableContainer");

  filterSelect.addEventListener("change", function () {
    const selected = this.value;
    filterFields.innerHTML = "";

    if (!selected) {
      originalTableContainer.classList.remove("d-none");
      filterResult.classList.add("d-none");
      originalTableContainer.classList.remove("d-none");
      return;
    }

    originalTableContainer.classList.add("d-none");

    if (selected === "stok_rendah") {
      filterFields.innerHTML = `
        <label for="maxStok">Stok Maksimal</label>
        <input type="number" class="form-control" id="maxStok" name="maxStok"  required>
      `;
} else if (selected === "kategori") {
  filterFields.innerHTML = `
    <label for="kategori">Kategori</label>
    <select class="form-control" id="kategori" name="kategori" required>
      <option value="">-- Pilih Kategori --</option>
      <option value="1">Antibiotik</option>
      <option value="2">Analgesik</option>
      <option value="3">Vitamin</option>
      <option value="4">Antiseptik</option>
    </select>
  `;
} else if (selected === "kadaluarsa") {
      filterFields.innerHTML = `
        <label for="days">Dalam berapa hari ke depan</label>
        <input type="number" class="form-control" id="days" name="days" required>
      `;
    }
  });
  

  filterForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(filterForm);

    fetch("api/filter_obat.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      filterResult.innerHTML = data;
      filterResult.classList.remove("d-none");
    })
    .catch(err => {
      console.error("Gagal:", err);
    });
  });
});
</script>
</body>
</html>