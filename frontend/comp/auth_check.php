<?php
session_start();
require_once __DIR__ . '/../../backend/config.php';
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: " . app_url('/backend/login/login.php'));
    exit;
}
?>