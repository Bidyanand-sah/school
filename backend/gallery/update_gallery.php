<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

require_once __DIR__ . '/../comp/image_helper.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id     = intval($_POST['id'] ?? 0);
$detail = trim($_POST['detail'] ?? '');

if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid photo ID"]);
    exit;
}

$stmt = $conn->prepare("SELECT img FROM gallery WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current) {
    echo json_encode(["success" => false, "message" => "Photo not found"]);
    exit;
}

$oldImg = $current['img'];
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

    $uploadDir = __DIR__ . "/../../uploads/gallery/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileName = uniqid("gallery_", true) . ".jpg";

    $targetPath = $uploadDir . $fileName;

    if (compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {

    
    
        if (!empty($oldImg)) {
            $oldFilePath = __DIR__ . "/../../" . $oldImg;
            if (file_exists($oldFilePath)) unlink($oldFilePath);
        }
        $imgPath = "uploads/gallery/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
}

$updateStmt = $conn->prepare("UPDATE gallery SET img=?, detail=? WHERE id=?");
$updateStmt->bind_param("ssi", $imgPath, $detail, $id);
$success = $updateStmt->execute();
$updateStmt->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Photo updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}
$conn->close();
?>