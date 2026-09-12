<?php
require_once __DIR__ . '/config.php';
session_start();
session_unset();
session_destroy();


header("Location: " . app_url('/backend/login/login.php'));
exit;
?>