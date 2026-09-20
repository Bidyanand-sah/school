<?php
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
    echo json_encode(["success" => false, "message" => "Invalid notice ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT pinned FROM notice WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Notice not found"]);
    exit;
}

$newPinned = $row['pinned'] ? 0 : 1;

$updateStmt = $conn->prepare("UPDATE notice SET pinned = ? WHERE id = ?");
$updateStmt->bind_param("ii", $newPinned, $id);
$success = $updateStmt->execute();
$updateStmt->close();

echo json_encode($success
    ? ["success" => true, "pinned" => $newPinned, "message" => $newPinned ? "Notice pinned" : "Notice unpinned"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>