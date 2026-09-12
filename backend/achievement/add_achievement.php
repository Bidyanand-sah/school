<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../con1.php';
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

$title       = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$category    = trim($_POST['category'] ?? 'Other');
$isPinned    = (isset($_POST['is_pinned']) && $_POST['is_pinned'] == '1') ? 1 : 0;
$imgPath     = "";

if ($title === '') {
    echo json_encode(["success" => false, "message" => "Title is required"]);
    exit;
}

$allowedCategories = ['Academic', 'Sports', 'Cultural', 'Other'];
if (!in_array($category, $allowedCategories)) {
    $category = 'Other';
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileType, $allowedImageTypes)) {
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
     
        $imgPath = "uploads/achievements/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Image is required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO achievements (img, title, description, category, is_pinned) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $imgPath, $title, $description, $category, $isPinned);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Achievement added successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>