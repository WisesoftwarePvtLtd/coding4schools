<?php
session_start();
include "standard_constants.php";
include "config.php";

$permissionName = trim($_POST['permissionName']);
$permissionId = $_POST['permissionId'] ?? "";

if ($permissionName == "") {
    $_SESSION['msg'] = "permission name is required";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_permissions.php");
    exit();
}


/* -------------------------------------------------
   DUPLICATION CHECK (ADD + UPDATE)
---------------------------------------------------*/

// For Update (exclude same ID)
if ($permissionId != "") {
    $checkSql = "SELECT COUNT(*) FROM permissions WHERE permission = ? AND permission_id != ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("si", $permissionName, $permissionId);

} else {
    // For Add
    $checkSql = "SELECT COUNT(*) FROM permissions WHERE permission = ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("s", $permissionName);
}

$stmtDup->execute();
$stmtDup->bind_result($dupCount);
$stmtDup->fetch();
$stmtDup->close();

if ($dupCount > 0) {
    $_SESSION['msg'] = "Permission name already exists!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_permissions.php");
    exit();
}

if ($permissionId == "") {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO permissions (permission) VALUES (?)");
    $stmt->bind_param("s", $permissionName);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "permission added successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to add permission!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
} else {
    // UPDATE
    $stmt = $conn->prepare("UPDATE permissions SET permission = ? WHERE permission_id = ?");
    $stmt->bind_param("si", $permissionName, $permissionId);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "permission updated successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to update permission!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
}

header("Location: manage_permissions.php");
exit();
?>