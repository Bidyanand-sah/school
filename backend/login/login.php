<?php
session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../../frontend/admin/index.php"); // <-- login ke baad kis page pe jana hai, ye link tum lagana
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Bright Future International School</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-left">
        <div class="brand">
            <i class="fas fa-school"></i>
            <span>Bright Future <em>International School</em></span>
        </div>
        <h1>Admin panel access, secure and simple.</h1>
        <p>Manage teachers, gallery, notices and enquiries — all in one dashboard.</p>
    </div>

    <div class="login-right">
        <div class="login-card">
            <h2>Welcome back</h2>
            <p class="subtitle">Sign in to continue</p>

            <form id="loginForm">
                <label>Username</label>
                <input type="text" id="username" placeholder="admin" autocomplete="username" required>

                <label>Password</label>
                <input type="password" id="password" placeholder="••••••••" autocomplete="current-password" required>

                <div id="errorMsg" class="error-msg"></div>

                <button type="submit" id="loginBtn">Login</button>
            </form>

            <div class="footer-text">Bright Future International School</div>
        </div>
    </div>
</div>

<script src="login.js"></script>
</body>
</html>