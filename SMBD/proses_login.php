<?php
include 'config/connection.php';
session_start();

$username = $_POST['username'];
$input = isset($_POST['password']) ? $_POST['password'] : $_POST['phone'];

$stmt = $conn->prepare("CALL proses_login_user(?, ?)");
$stmt->bind_param("ss", $username, $input);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $data = $result->fetch_assoc()) {
    if ($data['ROLE'] === 'admin') {
        $_SESSION['nama'] = $username;
        header("Location: index.php");
        exit;
    } elseif ($data['ROLE'] === 'pelanggan' || $data['ROLE'] === 'pelanggan_baru') {
        $_SESSION['telepon'] = $input;
        $_SESSION['nama'] = $username;
        header("Location: pelanggan_beli.php");
        exit;
    }
} else {
    echo "<script>alert('Login gagal: Nama tidak cocok dengan nomor telepon.'); window.location.href='login.html';</script>";
}

?>
