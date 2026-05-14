<?php
session_start();
include "standard_constants.php";
include 'config.php';

$id = (int) ($_GET['id'] ?? 0);
$type = $_GET['type'] ?? '';
$question_id = $_GET['question_id'] ?? '';
if ($id <= 0) {
    $_SESSION['msg'] = "Invalid option ID";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: question_edit.php?id=$question_id");
    exit;
}



$sql = "DELETE FROM options WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Option deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete option!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

$stmt->close();
header("Location: question_edit.php?id=$question_id");
exit;
