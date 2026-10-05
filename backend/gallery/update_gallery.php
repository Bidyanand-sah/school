<?php
require_once __DIR__ . '/../comp/api_auth.php';
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';

@ini_set('memory_limit', '256M');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

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

$oldImg      = $current['img'];
$imgPath     = $oldImg;
$newImgSaved = false;
$targetPath  = "";

// Image optional hai: sirf tab process hogi jab nayi bheji ho
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

    $uploadDir = __DIR__ . "/../../uploads/gallery/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName   = uniqid("gallery_", true) . ".jpg";
    $targetPath = $uploadDir . $fileName;

    if (!compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {
        echo json_encode(["success" => false, "message" => "Image process nahi ho payi"]);
        exit;
    }

    $imgPath     = "uploads/gallery/" . $fileName;
    $newImgSaved = true;
}

$updateStmt = $conn->prepare("UPDATE gallery SET img=?, detail=? WHERE id=?");
$updateStmt->bind_param("ssi", $imgPath, $detail, $id);
$success = $updateStmt->execute();
$dbError = $updateStmt->error;
$updateStmt->close();

if ($success) {
    // DB update ho gaya, ab purani image delete karna safe hai
    if ($newImgSaved && !empty($oldImg)) {
        $oldFilePath = __DIR__ . "/../../" . $oldImg;
        if (file_exists($oldFilePath)) unlink($oldFilePath);
    }
    echo json_encode(["success" => true, "message" => "Photo updated successfully"]);
} else {
    if ($newImgSaved && file_exists($targetPath)) unlink($targetPath);
    echo json_encode(["success" => false, "message" => "Database error: " . $dbError]);
}

$conn->close();
?>