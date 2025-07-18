<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit();
}

// Process traditional login
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_submit'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];

    // Replace this logic with your database validation
    if ($u === 'admin' && $p === 'admin123') {
        $_SESSION['loggedin'] = true;
        $_SESSION['user'] = $u;
        header('Location: dashboard.php');
        exit();
    } else {
        $login_error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | InquiryX</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #e0eafc, #cfdef3);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      background: #fff;
      width: 820px;
      height: 520px;
      display: flex;
      box-shadow: 0 0 25px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
      position: relative;
    }
    .left-panel {
      background-color: #0056b3;
      color: #fff;
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .left-panel h1 { font-size: 36px; margin-bottom: 20px; }
    .left-panel p { font-size: 16px; line-height: 1.5; }
    .right-panel {
      width: 50%;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .logo {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin: 0 auto 15px;
    }
    .right-panel h2 {
      margin-bottom: 15px;
      text-align: center;
      color: #333;
    }
    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .btn {
        width: 100%;
        background-color: #0056b3;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 5px;
        font-weight: bold;
        margin-top: 10px;
        cursor: pointer;
    }
    .btn:hover { background-color: #00408a; }
    .forgot {
        text-align: right;
        margin-top: 5px;
    }
    .forgot a {
        font-size: 13px;
        color: #0056b3;
        text-decoration: none;
    }
    .error {
        color: red;
        margin-top: 10px;
        text-align: center;
    }
    .google-btn {
        display: block;
        margin: 20px auto 0;
        text-align: center;
    }
    footer {
        position: absolute;
        bottom: 10px;
        text-align: center;
        width: 100%;
        font-size: 13px;
        color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <h1>Welcome to InquiryX</h1>
      <p>Manage all your business inquiries from a unified dashboard. Track customers, send notifications, and elevate your workflow.</p>
    </div>
    <div class="right-panel">
      <img class="logo" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Logo">
      <h2>Login to InquiryX</h2>
      <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="forgot"><a href="#">Forgot Password?</a></div>
        <button class="btn" type="submit" name="login_submit">Login</button>
      </form>
      <?php if ($login_error): ?>
        <div class="error"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>
      <div class="google-btn">
        <a href="glogin.php">
          <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
               alt="Sign in with Google">
        </a>
      </div>
    </div>
  </div>
  <footer>&copy; <?= date('Y') ?> InquiryX. All rights reserved.</footer>
</body>
</html>
