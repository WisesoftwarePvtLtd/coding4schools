<?php
include "config.php";

if (!isset($_GET['id']) || !isset($_GET['action'])) {
    header("Location: manage_menus.php");
    exit;
}

$menuId = intval($_GET['id']);
$action = $_GET['action'];

// Get current menu order
$sql = "SELECT menu_order FROM menus WHERE menu_id = $menuId";
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    header("Location: manage_menus.php");
    exit;
}

$currentOrder = intval($row['menu_order']);

// Get max order
$maxSql = "SELECT MAX(menu_order) AS maxOrder FROM menus";
$maxRes = mysqli_query($conn, $maxSql);
$maxRow = mysqli_fetch_assoc($maxRes);
$maxOrder = intval($maxRow['maxOrder']);

if ($action == "up" && $currentOrder > 1) {

    $swapOrder = $currentOrder - 1;

    // Find the menu with swapOrder
    $sql2 = "SELECT menu_id FROM menus WHERE menu_order = $swapOrder";
    $res2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($res2);
    $swapMenuId = $row2['menu_id'];

    // Swap
    mysqli_query($conn, "UPDATE menus SET menu_order = $swapOrder WHERE menu_id = $menuId");
    mysqli_query($conn, "UPDATE menus SET menu_order = $currentOrder WHERE menu_id = $swapMenuId");

}

elseif ($action == "down" && $currentOrder < $maxOrder) {

    $swapOrder = $currentOrder + 1;

    // Find the menu with swapOrder
    $sql2 = "SELECT menu_id FROM menus WHERE menu_order = $swapOrder";
    $res2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($res2);
    $swapMenuId = $row2['menu_id'];

    // Swap
    mysqli_query($conn, "UPDATE menus SET menu_order = $swapOrder WHERE menu_id = $menuId");
    mysqli_query($conn, "UPDATE menus SET menu_order = $currentOrder WHERE menu_id = $swapMenuId");

}

header("Location: manage_menus.php");
exit;
?>