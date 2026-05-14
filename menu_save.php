<?php
session_start();
include "standard_constants.php";
include "config.php";

$menuName = trim($_POST['menuName']);
$menuId   = $_POST['menuId'] ?? "";
$menuLink = trim($_POST['menuLinkPath'] ?? "");      // menu link path
$menuIcon = trim($_POST['menuIcon'] ?? "");      // menu icon class

if ($menuName == "") {
    $_SESSION['msg'] = "Menu name is required";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_menus.php");
    exit();
}

/* -------------------------------------------------
   DUPLICATION CHECK (ADD + UPDATE)
---------------------------------------------------*/

// If updating, exclude same ID
if ($menuId != "") {
    $checkSql = "SELECT COUNT(*) FROM menus WHERE menu = ? AND menu_id != ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("si", $menuName, $menuId);
} else {
    // For adding
    $checkSql = "SELECT COUNT(*) FROM menus WHERE menu = ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("s", $menuName);
}

$stmtDup->execute();
$stmtDup->bind_result($dupCount);
$stmtDup->fetch();
$stmtDup->close();

if ($dupCount > 0) {
    $_SESSION['msg'] = "Menu name already exists!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_menus.php");
    exit();
}

// Get the current max menu_order
$stmt = $conn->prepare("SELECT MAX(menu_order) FROM menus");
$stmt->execute();
$stmt->bind_result($maxOrder);
$stmt->fetch();
$stmt->close();
$menuOrder = $maxOrder + 1;

if ($menuId == "") {
    // INSERT NEW MENU
    $stmt = $conn->prepare("INSERT INTO menus (menu, menu_order, menu_link_path, menu_icon) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $menuName, $menuOrder, $menuLink, $menuIcon);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Menu added successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to add menu!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }

} else {
    // UPDATE EXISTING MENU
    $stmt = $conn->prepare("UPDATE menus SET menu = ?, menu_link_path = ?, menu_icon = ? WHERE menu_id = ?");
    $stmt->bind_param("sssi", $menuName, $menuLink, $menuIcon, $menuId);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Menu updated successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to update menu!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
}

header("Location: manage_menus.php");
exit();
?>
