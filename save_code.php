<?php
session_start();
include 'config.php';

$user_id = $_SESSION['user_id'] ?? 1;
$course  = $_POST['course'] ?? '';
$type    = $_POST['type'] ?? '';
$code    = $_POST['code'] ?? null;
$ref     = $_POST['ref'] ?? null;

if ($course === '' || $type === '') {
    echo "ERROR: Missing course or type";
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO course_code (user_id, course_name, editor_type, code, project_ref)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        editor_type = VALUES(editor_type),
        code = VALUES(code),
        project_ref = VALUES(project_ref)
");

if (!$stmt) {
    echo "ERROR: Prepare failed - " . $conn->error;
    exit;
}

$stmt->bind_param("issss", $user_id, $course, $type, $code, $ref);

if ($stmt->execute()) {
    echo "SAVED";
} else {
    echo "ERROR: Execute failed - " . $stmt->error;
}
?>


