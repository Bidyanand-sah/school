<?php
// backend/testimonial/delete_testimonial.php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid testimonial ID"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
$stmt->bind_param("i", $id);
$success = $stmt->execute();
$stmt->close();

echo json_encode($success
    ? ["success" => true, "message" => "Testimonial deleted"]
    : ["success" => false, "message" => "Database error: " . $conn->error]);

$conn->close();
?>