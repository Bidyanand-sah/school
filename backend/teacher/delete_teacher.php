<?php
require_once __DIR__ . '/../comp/api_auth.php';
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid teacher ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT img FROM teacher WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Teacher not found"]);
    exit;
}

// Pehle DB se delete, phir file
$deleteStmt = $conn->prepare("DELETE FROM teacher WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$success = $deleteStmt->execute();
$dbError = $deleteStmt->error;
$deleteStmt->close();

if ($success) {
    if (!empty($row['img'])) {
        $imgPath = __DIR__ . "/../../" . $row['img'];
        if (file_exists($imgPath)) unlink($imgPath);
    }
    echo json_encode(["success" => true, "message" => "Teacher deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $dbError]);
}

$conn->close();
?>