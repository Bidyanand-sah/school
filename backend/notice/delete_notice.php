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

$stmt = $conn->prepare("SELECT pdf FROM notice WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Notice not found"]);
    exit;
}

if (!empty($row['pdf'])) {
    $pdfPath = __DIR__ . "/../../" . $row['pdf'];
    if (file_exists($pdfPath)) unlink($pdfPath);
}

$deleteStmt = $conn->prepare("DELETE FROM notice WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$success = $deleteStmt->execute();
$deleteStmt->close();

echo json_encode($success
    ? ["success" => true, "message" => "Notice deleted successfully"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>