<?php
header('Content-Type: application/json');
include '../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid achievement ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT img FROM achievements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Achievement not found"]);
    exit;
}

if (!empty($row['img'])) {
    $imgPath = __DIR__ . "/../../" . $row['img'];
    if (file_exists($imgPath)) unlink($imgPath);
}

$deleteStmt = $conn->prepare("DELETE FROM achievements WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$success = $deleteStmt->execute();
$deleteStmt->close();

echo json_encode($success
    ? ["success" => true, "message" => "Achievement deleted successfully"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>