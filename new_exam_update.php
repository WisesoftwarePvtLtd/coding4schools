<?php
session_start();
include "standard_constants.php";
include "config.php";

if (!isset($_POST['update_quiz'])) {
    $_SESSION['msg'] = "Invalid Request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_generate_quiz.php");
    exit();
}

$quiz_id    = intval($_POST['quiz_id']);
$quiz_title = trim($_POST['quiz_title']);
$book_id    = intval($_POST['book_grade']);
$grade_id   = intval($_POST['grade']);
$new_sections = isset($_POST['section']) ? $_POST['section'] : [];  // array
$quiz_date  = !empty($_POST['quiz_date']) ? $_POST['quiz_date'] : date('Y-m-d');

/* ------------------------------------
   1) UPDATE QUIZ TABLE
-------------------------------------*/
$stmt1 = $conn->prepare("UPDATE quiz SET quiz_title=?, book_id=? WHERE quiz_id=?");
$stmt1->bind_param("sii", $quiz_title, $book_id, $quiz_id);
$stmt1->execute();



/* ------------------------------------
   2) GET OLD SECTIONS
-------------------------------------*/
$old_sections = [];
$old = $conn->query("SELECT section_id FROM quiz_applicable_for WHERE quiz_id=$quiz_id");

while ($r = $old->fetch_assoc()) {
    $old_sections[] = $r['section_id'];
}



/* ------------------------------------
   3) FIND SECTIONS TO INSERT (new - old)
-------------------------------------*/
$sections_to_add = array_diff($new_sections, $old_sections);


/* ------------------------------------
   4) FIND SECTIONS TO DELETE (old - new)
-------------------------------------*/
$sections_to_delete = array_diff($old_sections, $new_sections);



/* ------------------------------------
   5) DELETE ONLY REMOVED SECTIONS
-------------------------------------*/
foreach ($sections_to_delete as $sec_id) {
    $sec_id = intval($sec_id);

    $conn->query("
        DELETE FROM quiz_applicable_for 
        WHERE quiz_id=$quiz_id AND section_id=$sec_id
    ");
}



/* ------------------------------------
   6) INSERT ONLY NEW SECTIONS
-------------------------------------*/
$stmtInsert = $conn->prepare("
    INSERT INTO quiz_applicable_for (quiz_id, grade_id, section_id, quiz_date)
    VALUES (?, ?, ?, ?)
");

foreach ($sections_to_add as $sec_id) {
    $sec_id = intval($sec_id);

    $stmtInsert->bind_param("iiis", $quiz_id, $grade_id, $sec_id, $quiz_date);
    $stmtInsert->execute();
}



/* ------------------------------------
   7) UPDATE quiz_date (IMPORTANT)
-------------------------------------*/
$conn->query("
    UPDATE quiz_applicable_for 
    SET grade_id=$grade_id, quiz_date='$quiz_date'
    WHERE quiz_id=$quiz_id
");



/* ------------------------------------
   DONE
-------------------------------------*/
$_SESSION['msg'] = "Quiz updated successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_generate_quiz.php");
exit();

?>
