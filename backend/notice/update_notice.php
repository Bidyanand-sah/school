<?php
header('Content-Type: application/json');
include '../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id       = intval($_POST['id'] ?? 0);
$title    = trim($_POST['title'] ?? '');
$content  = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');

$allowedCategories = ['Urgent', 'Holiday', 'General', 'Exam', 'Event'];

if ($id <= 0 || $title === '' || $category === '') {
    echo json_encode(["success" => false, "message" => "ID, Title and Category are required"]);
    exit;
}
if (!in_array($category, $allowedCategories)) {
    echo json_encode(["success" => false, "message" => "Invalid category"]);
    exit;
}

$stmt = $conn->prepare("SELECT pdf FROM notice WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$current) {
    echo json_encode(["success" => false, "message" => "Notice not found"]);
    exit;
}

$oldPdf = $current['pdf'];
$pdfPath = $oldPdf;

if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
    $fileType = mime_content_type($_FILES['pdf']['tmp_name']);
    if ($fileType !== 'application/pdf') {
        echo json_encode(["success" => false, "message" => "Only PDF files allowed"]);
        exit;
    }
    if ($_FILES['pdf']['size'] > 20 * 1024 * 1024) {
        echo json_encode(["success" => false, "message" => "PDF size should be under 20MB"]);
        exit;
    }

    $uploadDir = __DIR__ . "/../../uploads/notice/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = uniqid("notice_", true) . ".pdf";
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
        if (!empty($oldPdf)) {
            $oldFilePath = __DIR__ . "/../../" . $oldPdf;
            if (file_exists($oldFilePath)) unlink($oldFilePath);
        }
        $pdfPath = "uploads/notice/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "PDF upload failed"]);
        exit;
    }
}

$updateStmt = $conn->prepare("UPDATE notice SET title=?, content=?, category=?, pdf=? WHERE id=?");
$updateStmt->bind_param("ssssi", $title, $content, $category, $pdfPath, $id);
$success = $updateStmt->execute();
$updateStmt->close();

if ($success) {
    echo json_encode(["success" => true, "message" => "Notice updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}
$conn->close();
?>