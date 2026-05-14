<?php
include "config.php";

$attempt_id  = intval($_POST['attempt_id']);
$question_id = intval($_POST['question_id']);
$question_type = $_POST['question_type'];

$answer      = $conn->real_escape_string($_POST['answer']);

/* DELETE old answer */
$conn->query("
    DELETE FROM quiz_answer
    WHERE quiz_attempt_id = $attempt_id
    AND question_id = $question_id
");

/* INSERT new answer */
$conn->query("
    INSERT INTO quiz_answer
    (quiz_attempt_id, question_id, question_type, quiz_attempt_answer)
    VALUES ($attempt_id, $question_id, '$question_type', '$answer')
");

echo json_encode(["status" => "saved"]);
