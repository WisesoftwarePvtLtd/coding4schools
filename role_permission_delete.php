<?php
session_start();        
include "config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$roleId     = isset($_GET['roleId']) ? intval($_GET['roleId']) : 0;
if ($id <= 0) {
    $_SESSION['msg'] = "Invalid delete request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_role_permission.php?role_id=$roleId");
    exit();
}

$sql = "DELETE FROM role_permissions WHERE role_permission_id = $id";

if ($conn->query($sql)) {
    $_SESSION['msg'] = "Permission removed from role successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Error deleting record: " . $conn->error;
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

header("Location: manage_role_permission.php?role_id=$roleId");
exit();
?>
