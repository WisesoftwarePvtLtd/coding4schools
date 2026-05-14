<?php
include "config.php";

$attempt_id  = intval($_POST['attempt_id']);
$question_id = intval($_POST['question_id']);

$res = $conn->query("
    SELECT quiz_attempt_answer,question_type
    FROM quiz_answer
    WHERE quiz_attempt_id = $attempt_id
    AND question_id = $question_id
    LIMIT 1
");

$row = $res->fetch_assoc();

echo json_encode([
  "answer" => $row['quiz_attempt_answer'] ?? "",
  "question_type" => $row['question_type'] ?? ""
]);
?>
