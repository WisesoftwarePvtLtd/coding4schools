<?php
session_start();
include "standard_constants.php";
include "config.php";

$roleName = trim($_POST['roleName']);
$roleId   = $_POST['roleId'] ?? "";

if ($roleName == "") {
    $_SESSION['msg'] = "role name is required";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_roles.php");
    exit();
}
/* -------------------------------------------------
   DUPLICATION CHECK (ADD + UPDATE)
---------------------------------------------------*/

// For Update → Exclude same ID
if ($roleId != "") {
    $checkSql = "SELECT COUNT(*) FROM roles WHERE role_name = ? AND role_id != ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("si", $roleName, $roleId);

} else {
    // For Add
    $checkSql = "SELECT COUNT(*) FROM roles WHERE role_name = ?";
    $stmtDup = $conn->prepare($checkSql);
    $stmtDup->bind_param("s", $roleName);
}

$stmtDup->execute();
$stmtDup->bind_result($dupCount);
$stmtDup->fetch();
$stmtDup->close();

if ($dupCount > 0) {
    $_SESSION['msg'] = "Role name already exists!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_roles.php");
    exit();
}

if ($roleId == "") {
    // INSERT
    $stmt = $conn->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->bind_param("s", $roleName);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "role added successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to add role!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
} else {
    // UPDATE
    $stmt = $conn->prepare("UPDATE roles SET role_name = ? WHERE role_id = ?");
    $stmt->bind_param("si", $roleName, $roleId);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "role updated successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Failed to update role!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
}

header("Location: manage_roles.php");
exit();
?>
