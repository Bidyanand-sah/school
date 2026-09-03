<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$dataFile   = __DIR__ . '/data/site_content.json';
$backupFile = __DIR__ . '/data/site_content_backup.json';
$content = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

// Backup before update
if (file_exists($dataFile)) copy($dataFile, $backupFile);

// ==================== TEXT FIELDS UPDATE ====================
$textFields = [
    'site_name', 'whatsapp_number', 'whatsapp_message',
    'hero_heading', 'hero_subtext',
    'hero_badge_text',
    'stat1_value', 'stat1_label',
    'stat2_value', 'stat2_label',
    'stat3_value', 'stat3_label',
    'stat4_value', 'stat4_label',
    'about_tag', 'about_heading', 'about_text',
    'about_point1_title', 'about_point1_text',
    'about_point2_title', 'about_point2_text',
    'about_point3_title', 'about_point3_text',
    'about_point4_title', 'about_point4_text',
    'footer_brand', 'footer_tagline',
    'footer_social_facebook', 'footer_social_instagram',
    'footer_social_youtube', 'footer_social_twitter',
    'footer_link1_text', 'footer_link1_url',
    'footer_link2_text', 'footer_link2_url',
    'footer_link3_text', 'footer_link3_url',
    'footer_link4_text', 'footer_link4_url',
    'footer_address', 'footer_phone', 'footer_email', 'footer_copyright',
    'contact_heading', 'contact_subtext',
    'contact_address', 'contact_phone', 'contact_email', 'contact_hours', 'contact_map_link'
];

foreach ($textFields as $field) {
    $content[$field] = trim($_POST[$field] ?? $content[$field] ?? '');
}

// ==================== IMAGE UPLOAD LOGIC ====================
$uploadDir = __DIR__ . '/../../uploads/site/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$imageFields = ['site_logo', 'hero_bg', 'about_img'];

foreach ($imageFields as $field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array(mime_content_type($_FILES[$field]['tmp_name']), $allowed)) {
            echo json_encode(["success" => false, "message" => "Only JPG, PNG, WEBP allowed for $field"]);
            exit;
        }

        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $fileName = uniqid($field . '_', true) . '.' . $ext;

        if (move_uploaded_file($_FILES[$field]['tmp_name'], $uploadDir . $fileName)) {
            // Delete old image
            if (!empty($content[$field])) {
                $oldPath = $uploadDir . basename($content[$field]);
                if (file_exists($oldPath)) unlink($oldPath);
            }
            $content[$field] = '/sms_teacher/uploads/site/' . $fileName;
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload $field"]);
            exit;
        }
    }
}

// ==================== ATOMIC SAVE ====================
$tmp = $dataFile . '.tmp';
if (file_put_contents($tmp, json_encode($content, JSON_PRETTY_PRINT)) === false) {
    echo json_encode(["success" => false, "message" => "Could not save"]);
    exit;
}
rename($tmp, $dataFile);

echo json_encode(["success" => true, "message" => "All settings saved successfully!"]);
?>