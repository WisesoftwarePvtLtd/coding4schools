<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    $_SESSION['msg'] = "Invalid Request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_generate_quiz.php");
    exit();
}

$quiz_id = intval($_GET['id']);

// Delete from quiz_applicable_for
$stmt1 = $conn->prepare("DELETE FROM quiz_applicable_for WHERE quiz_id=?");
$stmt1->bind_param("i", $quiz_id);
$stmt1->execute();

// Delete from quiz
$stmt2 = $conn->prepare("DELETE FROM quiz WHERE quiz_id=?");
$stmt2->bind_param("i", $quiz_id);

if ($stmt2->execute()) {
    $_SESSION['msg'] = "Quiz deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete quiz!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

header("Location: manage_generate_quiz.php");
exit();
