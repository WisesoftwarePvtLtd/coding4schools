<?php
session_start();
include 'config.php';

if (isset($_POST['new_exam'])) {

    $quiz_title = $_POST['new_exam'];
    $book_id = $_POST['book_grade'];
    $grade_id = $_POST['grade'];

    // Multiple sections array (checkbox)
    $sections = isset($_POST['section']) ? $_POST['section'] : [];

    // Auto date if empty
    $quiz_date = !empty($_POST['quiz_date']) ? $_POST['quiz_date'] : date('Y-m-d');

    // -------------------------
    // 1) INSERT INTO quiz TABLE
    // -------------------------
    $stmt = $conn->prepare("INSERT INTO quiz (quiz_title, book_id, status) VALUES (?, ?, 'work in progress')");
    $stmt->bind_param("si", $quiz_title, $book_id);
    $stmt->execute();
    $quiz_id = $stmt->insert_id;  // last inserted ID


    // -------------------------------------------
    // 2) INSERT MULTIPLE RECORDS INTO quiz_applicable_for
    // -------------------------------------------
    $stmt2 = $conn->prepare("
        INSERT INTO quiz_applicable_for (quiz_id, grade_id, section_id, quiz_date)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($sections as $section_id) {
        $stmt2->bind_param("iiis", $quiz_id, $grade_id, $section_id, $quiz_date);
        $stmt2->execute();
    }

    // -------------------------------------------
    // SUCCESS MESSAGE & REDIRECT
    // -------------------------------------------
    $_SESSION['msg'] = "Quiz saved successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    header("Location: manage_generate_quiz.php");
    exit();
}

?>
