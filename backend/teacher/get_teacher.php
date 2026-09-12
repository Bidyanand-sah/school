<?php
// backend/get_teachers.php
// Ye file database se saare teachers nikaal ke JSON format mein bhej deti hai.
// Frontend isi ko call karke bottom scroll section aur "Teachers" count fill karega.

header('Content-Type: application/json');
require_once __DIR__ . '/../con1.php';

$result = $conn->query("SELECT id, name, subject, bio, img, time FROM teacher ORDER BY id DESC");

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = $row;
}

echo json_encode(["success" => true, "teachers" => $teachers]);

$conn->close();
?>
