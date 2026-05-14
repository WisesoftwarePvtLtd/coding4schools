<?php
include "config.php";

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("invalid_id");
}

$stmt = mysqli_prepare($conn, "DELETE FROM grades WHERE grade_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>
