<?php
include "config.php";

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    die("invalid");
}

$stmt = mysqli_prepare($conn, "DELETE FROM sections WHERE section_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>
