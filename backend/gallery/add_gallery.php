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
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

$detail = trim($_POST['detail'] ?? '');
$imgPath = "";

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

    $uploadDir = __DIR__ . "/../../uploads/gallery/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = uniqid("gallery_", true) . ".jpg";

    
    $targetPath = $uploadDir . $fileName;

        if (compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {

        $imgPath = "uploads/gallery/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Image is required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO gallery (img, detail) VALUES (?, ?)");
$stmt->bind_param("ss", $imgPath, $detail);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Photo added successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>