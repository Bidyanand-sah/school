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

$id      = intval($_POST['id'] ?? 0);
$name    = trim($_POST['name'] ?? '');
$type    = trim($_POST['type'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$bio     = trim($_POST['bio'] ?? '');

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

/* ---------- Current record ---------- */
$stmt = $conn->prepare("SELECT img FROM teacher WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current) {
    echo json_encode(["success" => false, "message" => "Teacher not found"]);
    exit;
}

$oldImg      = $current['img'];
$imgPath     = $oldImg;
$newImgSaved = false;
$targetPath  = "";

/* ---------- Nayi image (optional) ---------- */
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

    $imgPath     = "uploads/teachers/" . $fileName;
    $newImgSaved = true;
}

/* ---------- Pehle DB update ---------- */
$updateStmt = $conn->prepare("UPDATE teacher SET name=?, type=?, subject=?, bio=?, img=? WHERE id=?");
$updateStmt->bind_param("sssssi", $name, $type, $subject, $bio, $imgPath, $id);
$success = $updateStmt->execute();
$dbError = $updateStmt->error;
$updateStmt->close();

if (!$success) {
    if ($newImgSaved && file_exists($targetPath)) unlink($targetPath);
    echo json_encode(["success" => false, "message" => "Database error: " . $dbError]);
    $conn->close();
    exit;
}

/* ---------- DB safal, ab purani image delete ---------- */
if ($newImgSaved && !empty($oldImg)) {
    $oldFilePath = __DIR__ . "/../../" . $oldImg;
    if (file_exists($oldFilePath)) unlink($oldFilePath);
}

/* ---------- Leadership type: same type ki baaki entries hatao (files bhi) ---------- */
$singleOnlyTypes = ['Director', 'Principal', 'Vice Principal'];

if (in_array($type, $singleOnlyTypes)) {
    $othersStmt = $conn->prepare("SELECT img FROM teacher WHERE type = ? AND id != ?");
    $othersStmt->bind_param("si", $type, $id);
    $othersStmt->execute();
    $othersResult = $othersStmt->get_result();

    $otherImgs = [];
    while ($row = $othersResult->fetch_assoc()) {
        if (!empty($row['img'])) $otherImgs[] = $row['img'];
    }
    $othersStmt->close();

    $delStmt = $conn->prepare("DELETE FROM teacher WHERE type = ? AND id != ?");
    $delStmt->bind_param("si", $type, $id);
    $deleted = $delStmt->execute();
    $delStmt->close();

    if ($deleted) {
        foreach ($otherImgs as $oi) {
            $p = __DIR__ . "/../../" . $oi;
            if (file_exists($p)) unlink($p);
        }
    }
}

echo json_encode(["success" => true, "message" => "Teacher updated successfully"]);
$conn->close();
?>