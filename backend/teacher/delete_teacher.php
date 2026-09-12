<?php
// backend/delete_teacher.php
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

// 1) Get image path before deleting the row
$stmt = $conn->prepare("SELECT img FROM teacher WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    echo json_encode(["success" => false, "message" => "Teacher not found"]);
    exit;
}

// 2) Delete the image file if it exists
if (!empty($row['img'])) {
    $imgPath = __DIR__ . "/../../" . $row['img'];   // e.g. /var/www/uploads/teachers/teacher_xxx.jpg
    if (file_exists($imgPath)) {
        unlink($imgPath);
    }
}

// 3) Delete the database row
$deleteStmt = $conn->prepare("DELETE FROM teacher WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$success = $deleteStmt->execute();
$deleteStmt->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Teacher deleted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>