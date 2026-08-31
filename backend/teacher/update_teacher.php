<?php
// backend/update_teacher.php
header('Content-Type: application/json');
include '../con1.php';
include '../comp/image_helper.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id          = intval($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$type        = trim($_POST['type'] ?? '');
$subject     = trim($_POST['subject'] ?? '');
$bio         = trim($_POST['bio'] ?? '');

$allowedTypes = ['Director', 'Principal', 'Vice Principal', 'Teacher'];

if ($id <= 0 || $name === '' || $type === '') {
    echo json_encode(["success" => false, "message" => "ID, Name and Type are required"]);
    exit;
}
if (!in_array($type, $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid type"]);
    exit;
}
if ($type === 'Teacher' && $subject === '') {
    echo json_encode(["success" => false, "message" => "Subject is required for Teacher"]);
    exit;
}

// Step 1: Get current image path (if any)
$stmt = $conn->prepare("SELECT img FROM teacher WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();
$stmt->close();
if (!$current) {
    echo json_encode(["success" => false, "message" => "Teacher not found"]);
    exit;
}
$oldImg = $current['img'];

// Step 2: Handle new image upload if provided
$imgPath = $oldImg; // keep old by default
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
    $uploadDir = __DIR__ . "/../../uploads/teachers/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
   
    $fileName = uniqid("achievement_", true) . ".jpg";

    
    
    $targetPath = $uploadDir . $fileName;
    if (compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {

        // Delete old image file if exists
        if (!empty($oldImg)) {
            $oldFilePath = __DIR__ . "/../../" . $oldImg;
            if (file_exists($oldFilePath)) unlink($oldFilePath);
        }
        $imgPath = "uploads/teachers/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
}

// Step 3: Auto‑replace logic for single‑only types (Director/Principal/Vice Principal)
$singleOnlyTypes = ['Director', 'Principal', 'Vice Principal'];
if (in_array($type, $singleOnlyTypes)) {
    // Delete any other entry with same type (except the one we are updating)
    $delStmt = $conn->prepare("DELETE FROM teacher WHERE type = ? AND id != ?");
    $delStmt->bind_param("si", $type, $id);
    $delStmt->execute();
    $delStmt->close();
    // Also delete image files of those removed entries? (optional, but we can skip to keep simple)
}

// Step 4: Update the teacher
$updateStmt = $conn->prepare("UPDATE teacher SET name=?, type=?, subject=?, bio=?, img=? WHERE id=?");
$updateStmt->bind_param("sssssi", $name, $type, $subject, $bio, $imgPath, $id);
$success = $updateStmt->execute();
$updateStmt->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Teacher updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}
$conn->close();
?>