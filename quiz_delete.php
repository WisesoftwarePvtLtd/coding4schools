<?php
session_start();
include 'config.php';
include 'standard_constants.php';
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : "";
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : "";

if (!isset($_GET['quiz_id'])) {
    $_SESSION['msg'] = "Invalid quiz!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_quiz.php?lesson_id=$lesson_id&course_id=$course_id");
    exit;
}

$quiz_id = intval($_GET['quiz_id']);

// 🔴 First delete quiz questions mapping (IMPORTANT)
$stmt = $conn->prepare("DELETE FROM quiz_questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$stmt->close();

// Delete from quiz_answer
$stmt4 = $conn->prepare("
    DELETE ea
    FROM quiz_answer ea
    INNER JOIN quiz_attempt et 
        ON ea.quiz_attempt_id = et.quiz_attempt_id
    WHERE et.quiz_id = ?
");
$stmt4->bind_param("i", $quiz_id);
$stmt4->execute();

// 2️⃣ Delete quiz_attempt
$stmt = $conn->prepare("DELETE FROM quiz_attempt WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();

// 🔴 Then delete quiz
$stmt = $conn->prepare("DELETE FROM quiz WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$stmt->close();

$_SESSION['msg']  = "Quiz deleted successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_quiz.php?lesson_id=$lesson_id&course_id=$course_id");
exit;
