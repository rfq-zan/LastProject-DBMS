<?php
include("config/connection.php");

$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$telepon = isset($_POST['telepon']) ? trim($_POST['telepon']) : '';

if (strlen($nama) < 3 || !preg_match('/^\d{10,15}$/', $telepon)) {
    echo "Data tidak valid. <a href='registrasi.php'>Kembali</a>";
    exit;
}

$nama = $conn->real_escape_string($nama);
$telepon = $conn->real_escape_string($telepon);

$sql = "INSERT INTO pelanggan (nama, telepon) VALUES ('$nama', '$telepon')";

if ($conn->query($sql) === TRUE) {
    echo "Registrasi berhasil! Redirecting to login...";
    header("refresh:3;url=login.html");
    exit;
} else {
    echo "Error: " . $conn->error . "<br><a href='registrasi.php'>Kembali</a>";
}


$conn->close();
?>
