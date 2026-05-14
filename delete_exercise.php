<?php
session_start();
include 'config.php';
include 'standard_constants.php';
/* =========================
   VALIDATION
========================= */
$exercise_id = intval($_POST['id'] ?? 0);

if ($exercise_id <= 0) {
    echo "invalid";
    exit;
}

/* =========================
   START TRANSACTION
========================= */
$conn->begin_transaction();

try {

    /* DELETE INSTRUCTIONS + HINTS */
    $stmt = $conn->prepare("
        DELETE FROM exercise_instruction_hints
        WHERE exercise_id = ?
    ");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();
    $stmt->close();

    /* DELETE EXERCISE */
    $stmt = $conn->prepare("
        DELETE FROM exercises
        WHERE exercise_id = ?
    ");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("not_found");
    }

    $stmt->close();

    $conn->commit();
    $_SESSION['msg']  = "Exercise deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

    echo "success";
    exit;

} catch (Exception $e) {

    $conn->rollback();

    $_SESSION['msg']  = "Unable to delete exercise!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;

    echo "error";
    exit;
}
    


