<?php
session_start();
header('Content-Type: application/json');
include '../con1.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(["success" => false, "message" => "Username and password required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, password FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
    exit;
}

// Login success — session set karo
$_SESSION['admin_id'] = $user['id'];
$_SESSION['admin_username'] = $user['username'];
$_SESSION['logged_in'] = true;

echo json_encode(["success" => true, "message" => "Login successful"]);
$conn->close();
?>