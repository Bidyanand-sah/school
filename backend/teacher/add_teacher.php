<?php
require_once __DIR__ . '/../comp/api_auth.php';
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';

@ini_set('memory_limit', '256M');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
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

$name    = trim($_POST['name'] ?? '');
$type    = trim($_POST['type'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$bio     = trim($_POST['bio'] ?? '');

$allowedTypes = ['Director', 'Principal', 'Vice Principal', 'Teacher'];

if ($name === '' || $type === '') {
    echo json_encode(["success" => false, "message" => "Name and Type are required"]);
    exit;
}
if (!in_array($type, $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid type selected"]);
    exit;
}
if ($type === 'Teacher' && $subject === '') {
    echo json_encode(["success" => false, "message" => "Subject is required for Teacher type"]);
    exit;
}

/* ---------- STEP 1: Image (optional) pehle validate + save ---------- */
$imgPath    = "";
$targetPath = "";

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

    $uploadDir = __DIR__ . "/../../uploads/teachers/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName   = uniqid("teacher_", true) . ".jpg";
    $targetPath = $uploadDir . $fileName;

    if (!compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {
        echo json_encode(["success" => false, "message" => "Image process nahi ho payi"]);
        exit;
    }
    $imgPath = "uploads/teachers/" . $fileName;
}

/* ---------- STEP 2: Naya entry insert karo ---------- */
$stmt = $conn->prepare("INSERT INTO teacher (name, type, subject, bio, img) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $type, $subject, $bio, $imgPath);

if (!$stmt->execute()) {
    $dbError = $stmt->error;
    $stmt->close();
    // Insert fail hua to nayi image hata do (purana data touch nahi hua)
    if ($targetPath !== "" && file_exists($targetPath)) unlink($targetPath);
    echo json_encode(["success" => false, "message" => "Database error: " . $dbError]);
    $conn->close();
    exit;
}

$newId = $stmt->insert_id;
$stmt->close();

/* ---------- STEP 3: Insert safal hua, ab purani leadership entry hatao ---------- */
$singleOnlyTypes = ['Director', 'Principal', 'Vice Principal'];

if (in_array($type, $singleOnlyTypes)) {
    $oldStmt = $conn->prepare("SELECT id, img FROM teacher WHERE type = ? AND id != ?");
    $oldStmt->bind_param("si", $type, $newId);
    $oldStmt->execute();
    $oldResult = $oldStmt->get_result();

    $oldImgs = [];
    while ($oldRow = $oldResult->fetch_assoc()) {
        if (!empty($oldRow['img'])) $oldImgs[] = $oldRow['img'];
    }
    $oldStmt->close();

    $deleteStmt = $conn->prepare("DELETE FROM teacher WHERE type = ? AND id != ?");
    $deleteStmt->bind_param("si", $type, $newId);
    $deleted = $deleteStmt->execute();
    $deleteStmt->close();

    if ($deleted) {
        foreach ($oldImgs as $oldImg) {
            $oldPath = __DIR__ . "/../../" . $oldImg;
            if (file_exists($oldPath)) unlink($oldPath);
        }
    }
}

echo json_encode(["success" => true, "message" => "Teacher added successfully", "id" => $newId]);
$conn->close();
?>