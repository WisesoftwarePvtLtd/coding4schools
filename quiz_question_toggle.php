<?php
session_start();
include 'config.php';
include 'standard_constants.php';

$quiz_id     = intval($_POST['quiz_id']);
$question_id = intval($_POST['question_id']);
$isOn        = isset($_POST['status']);

if ($isOn) {
    $conn->query("
        INSERT IGNORE INTO quiz_questions (quiz_id, question_id)
        VALUES ($quiz_id, $question_id)
    ");
    $_SESSION['msg'] = "Question added to quiz!";

} else {
    $conn->query("
        DELETE FROM quiz_questions
        WHERE quiz_id=$quiz_id AND question_id=$question_id
    ");
     $_SESSION['msg'] = "Question removed from quiz!";
}
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
