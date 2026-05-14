<?php
session_start();
include 'config.php';
include 'standard_constants.php';

$quizId    = $_POST['quizId'] ?? "";
$quizTitle = trim($_POST['quizTitle']);

if ($quizId == "") {
    // ADD
    $stmt = $conn->prepare("INSERT INTO quiz (quiz_title) VALUES (?)");
    $stmt->bind_param("s", $quizTitle);
    $stmt->execute();
    $_SESSION['msg']  = "Quiz added successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    // UPDATE
    $stmt = $conn->prepare("UPDATE quiz SET quiz_title=? WHERE quiz_id=?");
    $stmt->bind_param("si", $quizTitle, $quizId);
    $stmt->execute();
    $_SESSION['msg']  = "Quiz updated successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
}

header("Location: manage_quiz.php");
exit;