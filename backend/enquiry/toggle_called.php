<?php
// backend/toggle_called.php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid enquiry ID"]);
    exit;
}

// Current status nikalo
$stmt = $conn->prepare("SELECT is_called FROM enquiry WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Enquiry not found"]);
    exit;
}

$newStatus = $row['is_called'] ? 0 : 1;

if ($newStatus) {
    $update = $conn->prepare("UPDATE enquiry SET is_called = 1, called_at = NOW() WHERE id = ?");
} else {
    $update = $conn->prepare("UPDATE enquiry SET is_called = 0, called_at = NULL WHERE id = ?");
}
$update->bind_param("i", $id);

if ($update->execute()) {
    echo json_encode(["success" => true, "is_called" => $newStatus]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$update->close();
$conn->close();
?>