<?php
// backend/add_teacher.php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';
require_once __DIR__ . '/../comp/image_helper.php';


// Step 1: Sirf POST request allow karo
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Step 2: Text fields lo aur trim karo
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

// Step 3: Agar type "single-only" hai (Director/Principal/Vice Principal), to purana entry delete karo
$singleOnlyTypes = ['Director', 'Principal', 'Vice Principal'];

if (in_array($type, $singleOnlyTypes)) {
    // Pehle purane entry ka image path nikalo, taaki uski file bhi delete kar sakein
    $oldStmt = $conn->prepare("SELECT id, img FROM teacher WHERE type = ?");
    $oldStmt->bind_param("s", $type);
    $oldStmt->execute();
    $oldResult = $oldStmt->get_result();

    while ($oldRow = $oldResult->fetch_assoc()) {
        // Agar purani image thi to disk se bhi hata do
        if (!empty($oldRow['img'])) {
            $oldImgPath = __DIR__ . "/../../" . $oldRow['img'];
            if (file_exists($oldImgPath)) {
                unlink($oldImgPath);
            }
        }
    }
    $oldStmt->close();

    // Ab DB se purana entry delete karo
    $deleteStmt = $conn->prepare("DELETE FROM teacher WHERE type = ?");
    $deleteStmt->bind_param("s", $type);
    $deleteStmt->execute();
    $deleteStmt->close();
}

$imgPath = "";

// Step 4: Agar image bheji gayi hai, to usko validate aur save karo
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($fileType, $allowedImageTypes)) {
        echo json_encode(["success" => false, "message" => "Only JPG, PNG, WEBP images allowed"]);
        exit;
    }

    $maxSize = 20 * 1024 * 1024; // 20MB limit
    if ($_FILES['image']['size'] > $maxSize) {
        echo json_encode(["success" => false, "message" => "Image size should be under 20MB"]);
        exit;
    }

    $uploadDir = __DIR__ . "/../../uploads/teachers/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid("achievement_", true) . ".jpg";

    
    
    $targetPath = $uploadDir . $fileName;

    if (compressAndSaveImage($_FILES['image']['tmp_name'], $targetPath)) {

    
    
        $imgPath = "uploads/teachers/" . $fileName;
    } else {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
}

// Step 5: Naya entry insert karo
$stmt = $conn->prepare("INSERT INTO teacher (name, type, subject, bio, img) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $type, $subject, $bio, $imgPath);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Teacher added successfully",
        "id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>