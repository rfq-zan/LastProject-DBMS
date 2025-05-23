<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pharmacy Login</title>
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

    .login-container {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 100, 200, 0.15);
      width: 350px;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 8px;
      background: linear-gradient(90deg, #0077cc, #00aaff);
    }

    h2 {
      text-align: center;
      color: #005fa3;
      margin-bottom: 30px;
      font-weight: 600;
      font-size: 28px;
    }

    .pharmacy-icon {
      text-align: center;
      margin-bottom: 20px;
      color: #0077cc;
      font-size: 40px;
      cursor: pointer;
      transition: transform 0.3s;
    }

    .pharmacy-icon:hover {
      transform: scale(1.1);
    }

    label {
      display: block;
      margin-bottom: 8px;
      color: #005fa3;
      font-weight: 500;
      font-size: 14px;
    }

    input[type="text"],
    input[type="password"],
    input[type="tel"] {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 1px solid #cce0ff;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s;
      box-sizing: border-box;
    }

    input[type="text"]:focus,
    input[type="password"]:focus,
    input[type="tel"]:focus {
      border-color: #0077cc;
      box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.1);
      outline: none;
    }

    input[type="submit"] {
      width: 100%;
      background: linear-gradient(to right, #0077cc, #00aaff);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      margin-top: 10px;
      transition: all 0.3s;
    }

    input[type="submit"]:hover {
      background: linear-gradient(to right, #006bb3, #0099e6);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 100, 200, 0.2);
    }

    .forgot-password {
      text-align: right;
      margin-top: 10px;
    }

    .forgot-password a {
      color: #0077cc;
      text-decoration: none;
      font-size: 13px;
      transition: color 0.2s;
    }

    .forgot-password a:hover {
      color: #005fa3;
      text-decoration: underline;
    }

    .footer-text {
      text-align: center;
      margin-top: 25px;
      color: #6699cc;
      font-size: 12px;
    }

    .admin-mode {
      text-align: center;
      color: #ff5555;
      font-size: 12px;
      margin-bottom: 10px;
      font-weight: bold;
      display: none;
    }
        .registrasi-form {
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #cce6ff;
    }
    .registrasi-form h3 {
      text-align: center;
      color: #005fa3;
      margin-bottom: 20px;
      font-weight: 600;
      font-size: 22px;
    }
    .registrasi-form label {
      display: block;
      margin-bottom: 8px;
      color: #005fa3;
      font-weight: 500;
      font-size: 14px;
    }
    .registrasi-form input[type="text"],
    .registrasi-form input[type="tel"] {
      width: 100%;
      padding: 12px 15px;
      margin-bottom: 20px;
      border: 1px solid #cce0ff;
      border-radius: 8px;
      font-size: 14px;
      box-sizing: border-box;
      transition: all 0.3s;
    }
    .registrasi-form input[type="text"]:focus,
    .registrasi-form input[type="tel"]:focus {
      border-color: #0077cc;
      box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.1);
      outline: none;
    }
    .registrasi-form input[type="submit"] {
      width: 100%;
      background: linear-gradient(to right, #00aa55, #00cc77);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      transition: all 0.3s;
    }
    .registrasi-form input[type="submit"]:hover {
      background: linear-gradient(to right, #009944, #00bb66);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 170, 85, 0.3);
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="pharmacy-icon" id="pharmacyIcon" title="Click for Admin Login">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#0077cc" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 2v20M2 12h20M5 5l14 14M19 5l-14 14"/>
        <circle cx="12" cy="12" r="4"/>
      </svg>
    </div>
    <div class="admin-mode" id="adminMode">Admin Mode</div>
    <h2>Pharmacy Portal</h2>
    <form action="proses_login.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter your username" required>

      <label for="loginField" id="loginLabel">Phone Number</label>
      <input type="tel" id="loginField" name="phone" placeholder="Enter your phone number" required>

      <input type="submit" value="Sign In">
      <div class="reg">
        <a href="registrasi.php">New User ?</a>

    <div class="footer-text">
      © 2023 Pharmacy Management System
    </div>


  <script>
    const pharmacyIcon = document.getElementById('pharmacyIcon');
    const loginLabel = document.getElementById('loginLabel');
    const loginField = document.getElementById('loginField');
    const adminMode = document.getElementById('adminMode');
    let isAdminMode = false;

    pharmacyIcon.addEventListener('click', () => {
      isAdminMode = !isAdminMode;

      if (isAdminMode) {
        loginLabel.textContent = 'Password';
        loginField.type = 'password';
        loginField.placeholder = 'Enter admin password';
        loginField.name = 'password';
        adminMode.style.display = 'block';
        registrasiFormContainer.style.display = 'none';
        isRegisterVisible = false;
      } else {
        loginLabel.textContent = 'Phone Number';
        loginField.type = 'tel';
        loginField.placeholder = 'Enter your phone number';
        loginField.name = 'phone';
        adminMode.style.display = 'none';
      }
    });
      showRegisterLink.addEventListener('click', (e) => {
    e.preventDefault();
    if (!isAdminMode) {
      isRegisterVisible = !isRegisterVisible;
      registrasiFormContainer.style.display = isRegisterVisible ? 'block' : 'none';
    }
  });
  </script>
</body>
</html>