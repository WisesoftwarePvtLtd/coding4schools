<?php
session_start();
include "config.php";
include "standard_constants.php";
$username = $_POST['username'];
$student_number = $_POST['student_number'];
$password = $_POST['password'];
$student_name = $_POST['student_name'];
$father_name = $_POST['father_name'];
$family_name = $_POST['family_name'];
$gender = $_POST['gender'];
$grade = $_POST['grade'];
$section = !empty($_POST['section']) ? intval($_POST['section']) : NULL;
$user_type = $_POST['userType'];
$schoolId = intval($_POST['userSchoolId'] ?? 0);

$student_full_name   = $student_name . ' ' . $father_name;
$full_name   = $student_name . ' ' . $father_name. ' ' .$family_name;

if ($username == "" || $student_number == "" || $student_name == "" || $father_name == "" || $family_name == "" || $gender == "" || $grade == "" || $password == "") {
    $_SESSION['msg'] = " Missing Fields";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; 
    header("Location: add_student.php");
    exit();
}
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['msg']  = "Username already exists!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: add_student.php");
    exit();
}
$stmt->close();
// Encrypt Password
$key = DECRYPT_KEY;
$iv  = substr(hash("sha256", $key), 0, 16);
$encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);
// ----------------------------------
// 1) INSERT INTO USER TABLE
// ----------------------------------
$user_sql = "INSERT INTO users (username, password_hash, full_name, user_type, school_id) VALUES (?, ?, ?, ?,?)";
$user_stmt = mysqli_prepare($conn, $user_sql);
mysqli_stmt_bind_param(
    $user_stmt,
    "ssssi",
    $username,
    $encryptedPassword,
    $full_name,
    $user_type,
    $schoolId
);
if (!mysqli_stmt_execute($user_stmt)) {
    $_SESSION['msg']  = "User insert failed";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: add_student.php");
    exit();
}
// Get auto generated user_id
$user_id = mysqli_insert_id($conn);

mysqli_stmt_close($user_stmt);
// 2) INSERT INTO STUDENTS TABLE
$student_stmt = mysqli_prepare($conn, "INSERT INTO students (student_number, student_name, family_name, user_id, gender) VALUES (?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($student_stmt, "sssss", $student_number,  $student_full_name,  $family_name, $user_id, $gender);
if (!mysqli_stmt_execute($student_stmt)) {
    $_SESSION['msg']  = "Student insert failed";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: add_student.php");
    exit();
}
// Get auto generated user_id
$student_id = mysqli_insert_id($conn);
mysqli_stmt_close($student_stmt);
// 2) INSERT INTO SECTION STUDENTS TABLE
$stmt = mysqli_prepare($conn, "INSERT INTO section_students (student_id, grade_id, section_id) VALUES (?, ?,?)");
mysqli_stmt_bind_param($stmt, "iii", $student_id, $grade, $section);
if (!mysqli_stmt_execute($stmt)) {
    $_SESSION['msg']  = "Student insert failed in sections";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: add_student.php");
    exit();
}
mysqli_stmt_close($stmt);
// ----------------------------------
// 2) INSERT INTO USER ROLES TABLE
// ----------------------------------
$user_role_sql = "INSERT INTO user_roles (role_id, user_id) VALUES (?, ?)";
$user_role_stmt = mysqli_prepare($conn, $user_role_sql);

$role_id = STUDENT; // assign constant to variable
mysqli_stmt_bind_param($user_role_stmt, "ii", $role_id, $user_id);
if (mysqli_stmt_execute($user_role_stmt)) {
    $_SESSION['msg']  = "Student added successfully!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    header("Location: manage-student.php");
    exit;
} else {
    $_SESSION['msg']  = "Something went wrong!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: add_student.php");
    exit;
}
