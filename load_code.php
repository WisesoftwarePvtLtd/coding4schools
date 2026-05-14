<?php
session_start();
include 'config.php';


$user_id = $_SESSION['user_id'] ?? 1;
$course  = $_GET['course'];

$stmt = $conn->prepare("
    SELECT editor_type, code, project_ref
    FROM course_code
    WHERE user_id=? AND course_name=?
    ORDER BY updated_at DESC LIMIT 1
");

$stmt->bind_param("is", $user_id, $course);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode($result);
?>