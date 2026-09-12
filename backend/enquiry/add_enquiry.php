<?php
// backend/add_enquiry.php
header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $phone === '') {
    echo json_encode(["success" => false, "message" => "Name and Phone are required"]);
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    echo json_encode(["success" => false, "message" => "Enter a valid 10 digit phone number"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO enquiry (name, phone, email, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $email, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Enquiry submitted successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>