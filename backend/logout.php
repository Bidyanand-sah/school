<?php
session_start();
session_unset();
session_destroy();

require_once __DIR__ . '/config.php';

header("Location: " . app_url('/backend/login/login.php'));
exit;
?>