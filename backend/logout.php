<?php
session_start();
session_unset();
session_destroy();
header("Location: /sms_teacher/backend/login/login.php");
exit;
?>