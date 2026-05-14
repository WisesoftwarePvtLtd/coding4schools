<?php
session_start();
include "standard_constants.php";
include "config.php";

$rolePermissionId = $_POST['rolePermissionId'] ?? "";
$roleId = $_POST['roleId'] ?? "";
$permissionId = $_POST['permissionId'] ?? "";

if ($roleId == "" || $permissionId == "") {
    $_SESSION['msg'] = "Role and Permission are required.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_role_permission.php?role_id=$roleId");
    exit();
}

// ------------------------------------------
// CHECK DUPLICATE (same role + permission)
// ------------------------------------------
$check = $conn->query(
    "SELECT * FROM role_permissions 
     WHERE role_id='$roleId' AND permission_id='$permissionId' 
     AND role_permission_id != '$rolePermissionId'"
);

if ($check->num_rows > 0) {
    $_SESSION['msg'] = "This permission is already assigned to this role.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_role_permission.php?role_id=$roleId");
    exit();
}

// ------------------------------------------
// INSERT OR UPDATE
// ------------------------------------------
if ($rolePermissionId == "") {

    // INSERT NEW
    $sql = "INSERT INTO role_permissions (role_id, permission_id) 
            VALUES ('$roleId', '$permissionId')";

    if ($conn->query($sql)) {
        $_SESSION['msg'] = "Permission assigned to role successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Error: " . $conn->error;
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }

} else {

    // UPDATE EXISTING
    $sql = "UPDATE role_permissions 
            SET role_id='$roleId', permission_id='$permissionId'
            WHERE role_permission_id='$rolePermissionId'";

    if ($conn->query($sql)) {
        $_SESSION['msg'] = "Permission updated successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg'] = "Error: " . $conn->error;
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
}

header("Location: manage_role_permission.php?role_id=$roleId");
exit();
?>
