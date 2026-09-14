<?php
// backend/admin/update_account.php
// Logged-in admin apna username/password yahan se change karta hai
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Login check
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['admin_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit;
}

$adminId        = intval($_SESSION['admin_id']);
$currentPassword = trim($_POST['current_password'] ?? '');
$newUsername     = trim($_POST['new_username'] ?? '');
$newPassword     = trim($_POST['new_password'] ?? '');
$confirmPassword = trim($_POST['confirm_password'] ?? '');

if ($currentPassword === '') {
    echo json_encode(["success" => false, "message" => "Current password is required"]);
    exit;
}

// Kam se kam ek cheez to change honi chahiye
if ($newUsername === '' && $newPassword === '') {
    echo json_encode(["success" => false, "message" => "Enter a new username or new password to update"]);
    exit;
}

if ($newPassword !== '' && $newPassword !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "New password and confirm password do not match"]);
    exit;
}

if ($newPassword !== '' && strlen($newPassword) < 6) {
    echo json_encode(["success" => false, "message" => "New password should be at least 6 characters"]);
    exit;
}

// Current admin row nikalo
$stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$admin) {
    echo json_encode(["success" => false, "message" => "Admin not found"]);
    exit;
}

// Current password verify karo — sabse zaroori step
if (!password_verify($currentPassword, $admin['password'])) {
    echo json_encode(["success" => false, "message" => "Current password is incorrect"]);
    exit;
}

// Naya username final decide karo
$finalUsername = $newUsername !== '' ? $newUsername : $admin['username'];

// Agar username change ho raha hai to check karo koi aur use nahi kar raha
if ($newUsername !== '' && $newUsername !== $admin['username']) {
    $checkStmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
    $checkStmt->bind_param("si", $newUsername, $adminId);
    $checkStmt->execute();
    $exists = $checkStmt->get_result()->fetch_assoc();
    $checkStmt->close();

    if ($exists) {
        echo json_encode(["success" => false, "message" => "This username is already taken"]);
        exit;
    }
}

// Naya password final decide karo (agar nahi diya to purana hi rakho)
$finalPasswordHash = $newPassword !== '' ? password_hash($newPassword, PASSWORD_DEFAULT) : $admin['password'];

$updateStmt = $conn->prepare("UPDATE admin_users SET username = ?, password = ? WHERE id = ?");
$updateStmt->bind_param("ssi", $finalUsername, $finalPasswordHash, $adminId);
$success = $updateStmt->execute();
$updateStmt->close();

if ($success) {
    // Session bhi turant update karo taaki UI sahi dikhe
    $_SESSION['admin_username'] = $finalUsername;
    echo json_encode(["success" => true, "message" => "Account updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>