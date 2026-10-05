<?php
require_once __DIR__ . '/../comp/api_auth.php';
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';

// Badi image decode karne ke liye extra memory (agar host allow kare)
@ini_set('memory_limit', '256M');

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

// Upload error ka sahi message
function uploadErrorMessage($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "Image server ki upload limit se badi hai";
        case UPLOAD_ERR_PARTIAL:
            return "Image adhoori upload hui, dobara try karo";
        case UPLOAD_ERR_NO_FILE:
            return "Image is required";
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
            return "Server par image save nahi ho paayi";
        default:
            return "Image upload failed";
    }
}

if (!isset($_FILES['image'])) {
    echo json_encode(["success" => false, "message" => "Image is required"]);
    exit;
}
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => uploadErrorMessage($_FILES['image']['error'])]);
    exit;
}

// File ka asli type content se check hota hai (helper wala function)
if (getImageMime($_FILES['image']['tmp_name']) === false) {
    echo json_encode(["success" => false, "message" => "Ye image format support nahi hai"]);
    exit;
}
if ($_FILES['image']['size'] > 20 * 1024 * 1024) {
    echo json_encode(["success" => false, "message" => "Image size should be under 20MB"]);
    exit;
}

$uploadDir = __DIR__ . "/../../uploads/achievements/";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$fileName   = uniqid("achievement_", true) . ".jpg";
$targetPath = $uploadDir . $fileName;

if (!compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {
    echo json_encode(["success" => false, "message" => "Image process nahi ho payi"]);
    exit;
}
$imgPath = "uploads/achievements/" . $fileName;

$stmt = $conn->prepare("INSERT INTO achievements (img, title, description, category, is_pinned) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $imgPath, $title, $description, $category, $isPinned);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Achievement added successfully", "id" => $stmt->insert_id]);
} else {
    // DB fail hua to saved image hata do, warna file bekaar padi rahegi
    if (file_exists($targetPath)) unlink($targetPath);
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>