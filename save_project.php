<?php
session_start();
include 'config.php';

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $_SESSION['user_id'] ?? 1;
$course  = $data['course'] ?? '';
$type    = $data['type'] ?? '';
$code    = $data['code'] ?? '';

if ($course == '' || $type == '' || $code == '') {
    echo "ERROR: Missing data";
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO course_code (user_id, course_name, editor_type, code)
    VALUES (?, ?, ?, ?)
");

$stmt->bind_param("isss", $user_id, $course, $type, $code);

if ($stmt->execute()) {
    echo "Project Saved Successfully";
} else {
    echo "Database Error";
}
?>