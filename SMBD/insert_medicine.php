<?php 
include("nav.php");
require_once("config/connection.php");

$sqlKategori = "SELECT id_kategori, nama_kategori FROM kategori_obat ORDER BY nama_kategori ASC";
$resultKategori = $conn->query($sqlKategori);
$kategoriList = [];
if ($resultKategori->num_rows > 0) {
    while ($row = $resultKategori->fetch_assoc()) {
        $kategoriList[] = $row;
    }
}

$sqlPemasok = "SELECT id_pemasok, nama_pemasok FROM pemasok ORDER BY nama_pemasok ASC";
$resultPemasok = $conn->query($sqlPemasok);
$pemasokList = [];
if ($resultPemasok->num_rows > 0) {
    while ($row = $resultPemasok->fetch_assoc()) {
        $pemasokList[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert Medicine</title>
</head>
<style>
    body {
        background: #0f2027;  
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        color: #e0e6f1;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-label, label {
        color: #a9c9ff;
        font-weight: 600;
    }

    .form-control, .form-select {
        background: #122c54;
        border: 1.5px solid #3a8ddb;
        color: #cbdcff;
        border-radius: 10px;
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
        color: white;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, #00DFD8, #007CF0);
        box-shadow: 0 10px 25px rgba(0, 223, 216, 0.9);
    }

    .card {
        background: rgba(15, 32, 39, 0.85);
        border-radius: 15px;
        border: 1px solid #3a8ddb;
        box-shadow: 0 8px 20px rgba(58, 141, 219, 0.25);
        padding: 20px;
    }

    .card-header {
        background: linear-gradient(90deg, #007CF0, #00DFD8);
        font-weight: 700;
        font-size: 1.4rem;
        color: #fff;
        text-shadow: 0 0 8px #00dff0;
        border-radius: 15px 15px 0 0;
        padding: 15px;
    }
</style>

<body>
<div class="row">
  <div class="col-md-6 offset-md-3">
    <div class="card mt-3">
      <h3 class="card-header bg-primary text-white border-bottom border-light">Form Tambah Obat</h3>
      <div class="card-body">
        <form id="medicineForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="nama">Nama Obat:</label>
            <input type="text" id="nama" name="nama" class="form-control" required minlength="3">
          </div>

          <div class="form-group">
            <label for="image">Upload Gambar (jpg/jpeg/png):</label>
            <input type="file" id="image" name="image" class="form-control" accept=".jpg,.jpeg,.png" required>
          </div>

          <div class="form-group">
            <label for="kategori">Kategori:</label>
            <select id="kategori" name="id_kategori" class="form-control" required>
              <option value="">--Pilih Kategori--</option>
              <?php foreach($kategoriList as $kategori): ?>
                <option value="<?= htmlspecialchars($kategori['id_kategori']) ?>">
                  <?= htmlspecialchars($kategori['nama_kategori']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="pemasok">Pemasok:</label>
            <select id="pemasok" name="id_pemasok" class="form-control" required>
              <option value="">--Pilih Pemasok--</option>
              <?php foreach($pemasokList as $pemasok): ?>
                <option value="<?= htmlspecialchars($pemasok['id_pemasok']) ?>">
                  <?= htmlspecialchars($pemasok['nama_pemasok']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="harga_beli">Harga Beli per Kotak (Rp):</label>
            <input type="number" id="harga_beli" name="harga_beli" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="harga_jual">Harga Jual per Kotak (Rp):</label>
            <input type="number" id="harga_jual" name="harga_jual" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="stok">Jumlah Kotak:</label>
            <input type="number" id="stok" name="stok" class="form-control" min="1" required>
          </div>

          <div class="form-group">
            <label for="tanggal_kadaluarsa">Tanggal Kadaluarsa:</label>
            <input type="date" id="tanggal_kadaluarsa" name="tanggal_kadaluarsa" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary btn-block mt-3">Insert Medicine</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$('#medicineForm').submit(function(e) {
    e.preventDefault();
    const hargaBeli = parseInt($('#harga_beli').val());
    const hargaJual = parseInt($('#harga_jual').val());
    const stok = parseInt($('#stok').val());
    const tanggal_kadaluarsa = $('#tanggal_kadaluarsa').val();
    const image = $('#image')[0].files[0];

    if (!image || !['image/jpeg', 'image/jpg', 'image/png'].includes(image.type)) {
        Swal.fire('Error', 'Gambar harus jpg, jpeg, atau png', 'error');
        return;
    }

    if (hargaJual < hargaBeli) {
        Swal.fire('Error', 'Harga jual tidak boleh lebih rendah dari harga beli', 'error');
        return;
    }

    if (new Date(tanggal_kadaluarsa) <= new Date()) {
        Swal.fire('Error', 'Tanggal kadaluarsa harus di masa depan', 'error');
        return;
    }

    const btnSubmit = $('button[type="submit"]');
    btnSubmit.html('<span class="spinner-border spinner-border-sm"></span> Loading...').prop('disabled', true);

    var formData = new FormData($('#medicineForm')[0]);

    $.ajax({
        url: 'insert_to_medicine.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            console.log("Response dari server:", response);
            if (response.message) {
                let pesan = '';
                if (Array.isArray(response.message)) {
                    response.message.forEach(item => {
                        if (item.pesan) {
                            pesan += item.pesan + '<br>';
                        } else {
                            pesan += JSON.stringify(item) + '<br>';
                        }
                    });
                } else if (typeof response.message === 'object') {
                    pesan = JSON.stringify(response.message, null, 2).replace(/\n/g, '<br>');
                } else {
                    pesan = response.message;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    html: pesan
                });
                $('#medicineForm')[0].reset();
                $('button[type="submit"]').html('Insert Medicine').prop('disabled', false);
            } else if (response.error) {
                Swal.fire('Error', response.error, 'error');
                $('button[type="submit"]').html('Insert Medicine').prop('disabled', false);
            } else {
                Swal.fire('Error', 'Response tidak dikenali', 'error');
                $('button[type="submit"]').html('Insert Medicine').prop('disabled', false);
            }
        }
    });
});
</script>
</body>
</html>