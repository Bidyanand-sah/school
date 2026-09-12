<?php
// backend/testimonial/add_testimonial.php
// Public review page aur Admin panel dono isi file ko call karte hain
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Honeypot — bots isko fill kar dete hain, insaan nahi dekh pate (CSS se hidden hai)
$honeypot = trim($_POST['website'] ?? '');
if ($honeypot !== '') {
    // Silently fake success — bot ko pata nahi chalega ki reject hua
    echo json_encode(["success" => true, "message" => "Review submitted"]);
    exit;
}

$name        = trim($_POST['name'] ?? '');
$studentClass = trim($_POST['class'] ?? 'Parent');
$reviewText  = trim($_POST['review_text'] ?? '');
$rating      = intval($_POST['rating'] ?? 5);

if ($name === '' || $reviewText === '') {
    echo json_encode(["success" => false, "message" => "Name and Review are required"]);
    exit;
}
if (mb_strlen($name) > 100) {
    echo json_encode(["success" => false, "message" => "Name too long"]);
    exit;
}
if (mb_strlen($reviewText) > 500) {
    echo json_encode(["success" => false, "message" => "Review should be under 500 characters"]);
    exit;
}
if ($studentClass === '') $studentClass = 'Parent';

// Rating hamesha 1-5 ke beech rahe
if ($rating < 1) $rating = 1;
if ($rating > 5) $rating = 5;

$stmt = $conn->prepare("INSERT INTO testimonials (name, `class`, review_text, rating) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $name, $studentClass, $reviewText, $rating);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Review submitted successfully", "id" => $stmt->insert_id]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>