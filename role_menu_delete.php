<?php
session_start();
include "standard_constants.php";
include "config.php";

$roleId     = isset($_GET['roleId']) ? intval($_GET['roleId']) : 0;
 

if (!isset($_GET['id'])) {
    $_SESSION['msg'] = "Invalid request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_role_menus.php?role_id=$roleId");
    exit();
}

$roleMenuId = intval($_GET['id']);

$sql = "DELETE FROM role_menus WHERE role_menu_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $roleMenuId);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Role-menu mapping deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Error deleting mapping!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

$stmt->close();

header("Location: manage_role_menus.php?role_id=$roleId");
exit();
?>
