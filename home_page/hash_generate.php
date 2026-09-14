<?php
// TEMPORARY FILE — hash banane ke baad ye file delete kar dena, isse security risk hai

$plainPassword = "superadmin"; // <-- ise change karo

echo password_hash($plainPassword, PASSWORD_DEFAULT);
?>