<?php
session_start();
include 'config.php';
include 'standard_constants.php';

$id = intval($_GET['id'] ?? 0);
$lesson_id = intval($_GET['lesson_id'] ?? 0);

$exercise_id = intval($_GET['exercise_id'] ?? 0);

/* =========================
   DELETE FROM COMBINED TABLE
========================= */
$instructionqry = $conn->prepare(
    "DELETE FROM exercise_instruction_hints WHERE id = ?"
);
$instructionqry->bind_param("i", $id);


if ($instructionqry->execute()) {
    $_SESSION['msg'] = "Instruction deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete Instruction!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

$instructionqry->close();
header("Location: edit_exercise.php?exercise_id=$exercise_id&lesson_id=$lesson_id");
exit();
