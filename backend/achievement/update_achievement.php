<?php
require_once __DIR__ . '/../comp/api_auth.php';
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';

// Badi image decode karne ke liye extra memory (agar host allow kare)
@ini_set('memory_limit', '256M');

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

// Upload error ka sahi message
function uploadErrorMessage($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return "Image server ki upload limit se badi hai";
        case UPLOAD_ERR_PARTIAL:
            return "Image adhoori upload hui, dobara try karo";
        case UPLOAD_ERR_NO_TMP_DIR:
        case UPLOAD_ERR_CANT_WRITE:
            return "Server par image save nahi ho paayi";
        default:
            return "Image upload failed";
    }
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

$oldImg     = $current['img'];
$imgPath    = $oldImg;
$newImgSaved = false;
$targetPath = "";

// Image optional hai: sirf tab process hogi jab user ne nayi bheji ho
if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => uploadErrorMessage($_FILES['image']['error'])]);
        exit;
    }
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

    $imgPath     = "uploads/achievements/" . $fileName;
    $newImgSaved = true;
}

$updateStmt = $conn->prepare("UPDATE achievements SET img=?, title=?, description=?, category=?, is_pinned=? WHERE id=?");
$updateStmt->bind_param("ssssii", $imgPath, $title, $description, $category, $isPinned, $id);
$success = $updateStmt->execute();
$dbError = $updateStmt->error;
$updateStmt->close();

if ($success) {
    // DB update ho gaya, ab purani image delete karna safe hai
    if ($newImgSaved && !empty($oldImg)) {
        $oldFilePath = __DIR__ . "/../../" . $oldImg;
        if (file_exists($oldFilePath)) unlink($oldFilePath);
    }
    echo json_encode(["success" => true, "message" => "Achievement updated successfully"]);
} else {
    // DB fail hua to nayi image hata do, purani jagah par hi rahegi
    if ($newImgSaved && file_exists($targetPath)) unlink($targetPath);
    echo json_encode(["success" => false, "message" => "Database error: " . $dbError]);
}

$conn->close();
?>