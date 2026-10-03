<?php
// backend/comp/superadmin_auth.php
// Pehle login check, phir superadmin role check.

require_once __DIR__ . '/api_auth.php';

if (($_SESSION['admin_role'] ?? '') !== 'superadmin') {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Forbidden: superadmin only"]);
    exit;
}