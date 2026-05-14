<?php
session_start();
include "standard_constants.php";
include "config.php";

$question_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($question_id <= 0) {
    $_SESSION['msg'] = "Invalid Question ID";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_question_bank.php");
    exit;
}

/* ===============================
   GET QUESTION TYPE + MAIN IMAGE
================================ */
$stmt = $conn->prepare("SELECT question_type, main_image FROM questions WHERE question_id = ?");
$stmt->bind_param("i", $question_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['msg'] = "Question not found";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_question_bank.php");
    exit;
}

$q = $result->fetch_assoc();
$type = $q['question_type'];
$main_image = $q['main_image'];

/* ===============================
   DELETE MAIN IMAGE (IF ANY)
================================ */
if (!empty($main_image) && file_exists($main_image)) {
    unlink($main_image);
}

$imgQ = $conn->prepare("SELECT image FROM options WHERE question_id=?");
$imgQ->bind_param("i", $question_id);
$imgQ->execute();
$imgs = $imgQ->get_result();

while ($row = $imgs->fetch_assoc()) {
    if (!empty($row['image']) && file_exists($row['image'])) {
        unlink($row['image']);
    }
}

$del = $conn->prepare("DELETE FROM options WHERE question_id=?");
$del->bind_param("i", $question_id);
$del->execute();


/* ======================================================
   DELETE MAIN QUESTION
====================================================== */
$delQ = $conn->prepare("DELETE FROM questions WHERE question_id=?");
$delQ->bind_param("i", $question_id);

if ($delQ->execute()) {
    $_SESSION['msg'] = "Question deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed deleting main question! Check FK constraints.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

header("Location: manage_question_bank.php");
exit;
