<?php
session_start();
include "header.php";
include "config.php";

if (!isset($_GET['id'])) {
    $_SESSION['msg'] = "Invalid request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_menus.php");
    exit();
}

$menu_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM menus WHERE menu_id = ?");
$stmt->bind_param("i", $menu_id);

if ($stmt->execute()) {
    $_SESSION['msg'] = "Menu deleted successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete menu!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

$stmt->close();
header("Location: manage_menus.php");
exit();
?>
