<?php
session_start();
include "config.php";

header('Content-Type: application/json');

$user_id     = $_SESSION['LoggedInUserId'] ?? 1;
$lesson_id   = intval($_POST['lesson_id'] ?? 0);
$exercise_id = intval($_POST['exercise_id'] ?? 0);
$code        = $_POST['code'] ?? '';

$stmt = $conn->prepare(
  "INSERT INTO submissions (user_id, lesson_id, exercise_id, code)
   VALUES (?,?,?,?)
   ON DUPLICATE KEY UPDATE code = VALUES(code)"
);
$stmt->bind_param("iiis",
  $user_id, $lesson_id, $exercise_id, $code
);
$stmt->execute();

echo json_encode(["status"=>"saved"]);
exit;
