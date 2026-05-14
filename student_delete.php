<?php
include "config.php";
$student_id = $_POST['student_id'] ?? "";
//1. GET user_id FROM students USING student_id//
$q = mysqli_prepare($conn, "SELECT user_id FROM students WHERE student_id = ?");
mysqli_stmt_bind_param($q, "i", $student_id);
mysqli_stmt_execute($q);
$result = mysqli_stmt_get_result($q);
$row = mysqli_fetch_assoc($result);
$user_id = $row['user_id'];
mysqli_stmt_close($q);
//2. DELETE FROM section_students
$stmt1 = mysqli_prepare($conn, "DELETE FROM section_students WHERE student_id = ?");
mysqli_stmt_bind_param($stmt1, "i", $student_id);
$ok1 = mysqli_stmt_execute($stmt1);
mysqli_stmt_close($stmt1);
//3. DELETE FROM students
$stmt2 = mysqli_prepare($conn, "DELETE FROM students WHERE student_id = ?");
mysqli_stmt_bind_param($stmt2, "i", $student_id);
$ok2 = mysqli_stmt_execute($stmt2);
mysqli_stmt_close($stmt2);
//4. DELETE FROM user_roles
$stmt3 = mysqli_prepare($conn, "DELETE FROM user_roles WHERE user_id = ?");
mysqli_stmt_bind_param($stmt3, "i", $user_id);
$ok3 = mysqli_stmt_execute($stmt3);
mysqli_stmt_close($stmt3);
// 5. DELETE FROM users
$stmt4 = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt4, "i", $user_id);
$ok4 = mysqli_stmt_execute($stmt4);
mysqli_stmt_close($stmt4);
//FINAL RESPONSE
if ($ok1 && $ok2 && $ok3 && $ok4) {
    echo "success";
} else {
    echo "delete failed";
}

