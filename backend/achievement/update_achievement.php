<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id          = intval($_POST['id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = trim($_POST['category'] ?? 'Other');
$isPinned    = (isset($_POST['is_pinned']) && $_POST['is_pinned'] == '1') ? 1 : 0;

if ($id <= 0 || $title === '') {
    echo json_encode(["success" => false, "message" => "ID and Title are required"]);
    exit;
}

$allowedCategories = ['Academic', 'Sports', 'Cultural', 'Other'];
if (!in_array($category, $allowedCategories)) {
    $category = 'Other';
}

$stmt = $conn->prepare("SELECT img FROM achievements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current) {
    echo json_encode(["success" => false, "message" => "Achievement not found"]);
    exit;
}

$oldImg  = $current['img'];
$imgPath = $oldImg;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);
    if (!in_array($fileType, $allowed)) {
        echo json_encode(["success" => false, "message" => "Only JPG, PNG, WEBP images allowed"]);
        exit;
    }
    if ($_FILES['image']['size'] > 20 * 1024 * 1024) {
        echo json_encode(["success" => false, "message" => "Image size should be under 20MB"]);
        exit;
    }

    $uploadDir = __DIR__ . "/../../uploads/achievements/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = uniqid("achievement_", true) . ".jpg";

    $targetPath = $uploadDir . $fileName;

    if (compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {

        if (!empty($oldImg)) {
            $oldFilePath = __DIR__ . "/../../" . $oldImg;
            if (file_exists($oldFilePath)) unlink($oldFilePath);
        }
        $imgPath = "uploads/achievements/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
}

$updateStmt = $conn->prepare("UPDATE achievements SET img=?, title=?, description=?, category=?, is_pinned=? WHERE id=?");
$updateStmt->bind_param("ssssii", $imgPath, $title, $description, $category, $isPinned, $id);
$success = $updateStmt->execute();
$updateStmt->close();

echo json_encode($success
    ? ["success" => true, "message" => "Achievement updated successfully"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>