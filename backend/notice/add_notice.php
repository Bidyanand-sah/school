<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

$title    = trim($_POST['title'] ?? '');
$content  = trim($_POST['content'] ?? '');
$category = trim($_POST['category'] ?? '');

$allowedCategories = ['Urgent', 'Holiday', 'General', 'Exam', 'Event'];

if ($title === '' || $category === '') {
    echo json_encode(["success" => false, "message" => "Title and Category are required"]);
    exit;
}
if (!in_array($category, $allowedCategories)) {
    echo json_encode(["success" => false, "message" => "Invalid category selected"]);
    exit;
}

$pdfPath = "";

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
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid("notice_", true) . ".pdf";
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $targetPath)) {
        $pdfPath = "uploads/notice/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "PDF upload failed"]);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO notice (title, content, category, pdf) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $content, $category, $pdfPath);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Notice added successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>