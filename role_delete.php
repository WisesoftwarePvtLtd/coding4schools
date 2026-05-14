<?php
session_start();
include "standard_constants.php";
include "config.php";

if (!isset($_GET['id'])) {
    $_SESSION['msg'] = "Invalid request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_roles.php");
    exit();
}

$role_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM roles WHERE role_id = ?");
$stmt->bind_param("i", $role_id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "role deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete role!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

$stmt->close();
header("Location: manage_roles.php");
exit();
?>
