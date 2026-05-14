<?php
session_start();
include 'config.php';
include 'standard_constants.php';

if(isset($_GET['quiz_id'])){
    $quiz_id = intval($_GET['quiz_id']);
    $lesson_id = intval($_GET['lesson_id']);
    $course_id = intval($_GET['course_id']);


    // Update quiz status to 'dispatch'
    $sql = "UPDATE quiz SET status='dispatch' WHERE quiz_id=$quiz_id";
    if($conn->query($sql)){
        $_SESSION['msg'] = "Quiz dispatched successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to dispatch quiz!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }

    // Redirect back to quiz list or same page
    header("Location: manage_quiz.php?lesson_id=$lesson_id&course_id=$course_id");
    exit;;
}
?>
