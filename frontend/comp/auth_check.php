<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: " . app_url('/backend/login/login.php'));
    exit;
}
?>