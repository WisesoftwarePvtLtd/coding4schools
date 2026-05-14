<?php
include "config.php";

$id = intval($_POST['id']);
if (!$id) die("Invalid");

// DELETE from teachers
$stmt1 = mysqli_prepare($conn, "DELETE FROM teachers WHERE user_id=?");
mysqli_stmt_bind_param($stmt1, "i", $id);
$ok1 = mysqli_stmt_execute($stmt1);

// DELETE from user
$stmt2 = mysqli_prepare($conn, "DELETE FROM users WHERE user_id=?");
mysqli_stmt_bind_param($stmt2, "i", $id);
$ok2 = mysqli_stmt_execute($stmt2);

// DELETE from user role
$stmt3 = mysqli_prepare($conn, "DELETE FROM user_roles WHERE user_id=?");
mysqli_stmt_bind_param($stmt3, "i", $id);
$ok3 = mysqli_stmt_execute($stmt3);

// Check success
if ($ok1 && $ok2) {
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($stmt1);
mysqli_stmt_close($stmt2);
?>

