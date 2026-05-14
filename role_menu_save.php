<?php
session_start();
include "standard_constants.php";
include "config.php";
// print_r($_POST);die;

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $roleMenuId = isset($_POST['roleMenuId']) ? intval($_POST['roleMenuId']) : 0;
    $roleId     = isset($_POST['roleId']) ? intval($_POST['roleId']) : 0;
    $menuId     = isset($_POST['menuId']) ? intval($_POST['menuId']) : 0;

    if ($roleId == 0 || $menuId == 0) {
        $_SESSION['msg'] = "Role and Menu are required!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        header("Location: manage_role_menus.php?role_id=$roleId");
        exit();
    }

    // ----------------------------------------
    // 🚫 CHECK DUPLICATE MAPPING
    // ----------------------------------------
    $checkSql = "SELECT role_menu_id FROM role_menus WHERE role_id = ? AND menu_id = ?";

    if ($roleMenuId > 0) {
        // exclude current record in update
        $checkSql .= " AND role_menu_id != ?";
    }

    $stmtCheck = $conn->prepare($checkSql);

    if ($roleMenuId > 0) {
        $stmtCheck->bind_param("iii", $roleId, $menuId, $roleMenuId);
    } else {
        $stmtCheck->bind_param("ii", $roleId, $menuId);
    }

    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        $_SESSION['msg'] = "This role already has this menu assigned!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        header("Location: manage_role_menus.php?role_id=$roleId");
        exit();
    }

    $stmtCheck->close();


    // ----------------------------------------
    // ➕ INSERT NEW ROLE-MENU
    // ----------------------------------------
    if ($roleMenuId == 0) {
        $sql = "INSERT INTO role_menus (role_id, menu_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $roleId, $menuId);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Menu assigned to role successfully!";
             $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        } else {
            $_SESSION['msg'] = "Error assigning menu!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        }

        $stmt->close();
    }


    // ----------------------------------------
    // ✏ UPDATE ROLE-MENU ASSIGNMENT
    // ----------------------------------------
    else {
        $sql = "UPDATE role_menus SET role_id = ?, menu_id = ? WHERE role_menu_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $roleId, $menuId, $roleMenuId);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Role-menu mapping updated successfully!";
             $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        } else {
            $_SESSION['msg'] = "Error updating mapping!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        }

        $stmt->close();
    }

    header("Location: manage_role_menus.php?role_id=$roleId");
    exit();
}

?>
