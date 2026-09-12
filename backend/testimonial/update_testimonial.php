<?php
// backend/testimonial/update_testimonial.php — sirf admin panel use karega
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id          = intval($_POST['id'] ?? 0);
$name        = trim($_POST['name'] ?? '');
$studentClass = trim($_POST['class'] ?? 'Parent');
$reviewText  = trim($_POST['review_text'] ?? '');
$rating      = intval($_POST['rating'] ?? 5);

if ($id <= 0 || $name === '' || $reviewText === '') {
    echo json_encode(["success" => false, "message" => "ID, Name and Review are required"]);
    exit;
}
if (mb_strlen($reviewText) > 500) {
    echo json_encode(["success" => false, "message" => "Review should be under 500 characters"]);
    exit;
}
if ($rating < 1) $rating = 1;
if ($rating > 5) $rating = 5;
if ($studentClass === '') $studentClass = 'Parent';

$stmt = $conn->prepare("UPDATE testimonials SET name=?, `class`=?, review_text=?, rating=? WHERE id=?");
$stmt->bind_param("sssii", $name, $studentClass, $reviewText, $rating, $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode($success
    ? ["success" => true, "message" => "Testimonial updated successfully"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>