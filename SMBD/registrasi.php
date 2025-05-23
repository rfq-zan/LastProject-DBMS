<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register New Customer</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #e6f2ff, #cce6ff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .registrasi-form {
      background-color: #fff;
      border-radius: 10px;
      padding: 30px 25px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      max-width: 400px;
      width: 100%;
      margin: 30px auto;
    }
    .registrasi-form h3 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .registrasi-form label {
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
      font-weight: 500;
      color: #333;
    }
    .registrasi-form input[type="text"],
    .registrasi-form input[type="tel"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
      box-sizing: border-box;
    }
    .registrasi-form input[type="submit"] {
      width: 100%;
      margin-top: 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    .registrasi-form input[type="submit"]:hover {
      background-color: #45a049;
    }
    .cancel-register {
      text-align: center;
      margin-top: 15px;
    }
    .cancel-register a {
      font-size: 14px;
      color: #0077cc;
      text-decoration: none;
      transition: color 0.2s ease;
    }
    .cancel-register a:hover {
      color: #005fa3;
      text-decoration: underline;
    }
    .error-msg {
      color: red;
      font-size: 13px;
      margin-top: 5px;
    }
  </style>

  <script>
    function validateForm() {
      const nama = document.getElementById('regNama').value.trim();
      const telepon = document.getElementById('regTelepon').value.trim();
      const errorMsg = document.getElementById('errorMsg');
      errorMsg.textContent = '';

      if (nama.length < 3) {
        errorMsg.textContent = 'Nama minimal 3 karakter.';
        return false;
      }
      if (!/^\d{10,15}$/.test(telepon)) {
        errorMsg.textContent = 'Telepon harus 10-15 digit angka.';
        return false;
      }
      return true;
    }
  </script>
</head>
<body>

<div class="registrasi-form">
  <h3>Register New Customer</h3>
  <form action="register.php" method="post" onsubmit="return validateForm()">
    <label for="regNama">Nama</label>
    <input type="text" id="regNama" name="nama" placeholder="Enter your full name" required />

    <label for="regTelepon">Phone Number</label>
    <input type="tel" id="regTelepon" name="telepon" placeholder="Enter your phone number" required />

    <div id="errorMsg" class="error-msg"></div>

    <input type="submit" value="Register" />
  </form>
  <div class="cancel-register">
    <a href="login.html">Back to Login</a>
  </div>
</div>

</body>
</html>
