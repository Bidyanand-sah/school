<?php
// backend/comp/api_auth.php
// Admin-only backend files ke liye. Redirect nahi karta, JSON error deta hai.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}