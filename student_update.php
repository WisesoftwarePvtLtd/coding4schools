<?php
session_start();
include "config.php";
include 'standard_constants.php';
$user_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
//  THIS IS USER ID
$username = $_POST['username'];
$student_number = $_POST['student_number'];
$password = $_POST['password'];
$student_name = $_POST['student_name'];
$father_name = $_POST['father_name'];
$family_name = $_POST['family_name'];
$gender = $_POST['gender'];
$grade = $_POST['grade'];
$section = !empty($_POST['section']) ? intval($_POST['section']) : NULL;
$schoolId = intval($_POST['userSchoolId'] ?? 0);

$student_full_name = $student_name . ' ' . $father_name;
$full_name = $student_name . ' ' . $father_name . ' ' . $family_name;
// Validation
if (
    $user_id === "" || $username === "" || $student_number === "" || $student_name === "" ||
    $father_name === "" || $family_name === "" || $gender === "" || $grade === ""
) {
    $_SESSION['msg'] = " Missing Fields";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: edit_student.php?user_id=$user_id");
    exit();
}
// NOW RUN ORIGINAL CODE
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
$stmt->bind_param("si", $username, $user_id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['msg'] = "Username already exists!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: edit_student.php?user_id=$user_id");
    exit();
}
$stmt->close();
// ----------------------------------------------
// STEP 1: Student ID from students table
// ----------------------------------------------
$q = $conn->prepare("SELECT student_id FROM students WHERE user_id=?");
$q->bind_param("i", $user_id);
$q->execute();
$res = $q->get_result();
$row = $res->fetch_assoc();
$q->close();
$row;
$student_id = $row['student_id'];
// ----------------------------------------------
// STEP 2: UPDATE user TABLE
// ----------------------------------------------
if ($password != "") {
    $key = DECRYPT_KEY;
    $iv = substr(hash("sha256", $key), 0, 16);
    $encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);

    $user_sql = "UPDATE users SET username=?, password_hash=?, full_name=?, school_id=? WHERE user_id=?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("sssii", $username, $encryptedPassword, $full_name,$schoolId, $user_id);

} else {
    $user_sql = "UPDATE users SET username=?, full_name=?, school_id=? WHERE user_id=?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("ssii", $username, $full_name,$schoolId, $user_id);
}
$stmt->execute();
$stmt->close();
// ----------------------------------------------
// STEP 3: UPDATE students TABLE
// ----------------------------------------------
$std_sql = "UPDATE students SET student_number=?, student_name=?, family_name=?, gender=? WHERE student_id=?";
$std = $conn->prepare($std_sql);
$std->bind_param("ssssi", $student_number, $student_full_name, $family_name, $gender, $student_id);
if (!$std->execute()) {
    $_SESSION['msg'] = "Students Update failed";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR; // or 'warning'
    header("Location: edit_student.php?user_id=$user_id");
    exit();
}
$std->close();
// ----------------------------------------------
// STEP 4: UPDATE section_students TABLE
// ----------------------------------------------

$check = $conn->prepare(
    "SELECT section_students_id  FROM section_students WHERE student_id = ?"
);
$check->bind_param("i", $student_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

    // ✅ UPDATE
    $sec_sql = "
        UPDATE section_students 
        SET grade_id = ?, section_id = ?
        WHERE student_id = ?
    ";
    $sec = $conn->prepare($sec_sql);
    $sec->bind_param("iii", $grade, $section, $student_id);

} else {

    // ✅ INSERT
    $sec_sql = "
        INSERT INTO section_students (grade_id, section_id, student_id)
        VALUES (?, ?, ?)
    ";
    $sec = $conn->prepare($sec_sql);
    $sec->bind_param("iii", $grade, $section, $student_id);
}

if (!$sec->execute()) {
    $_SESSION['msg'] = "Student updated failed!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage-student.php");
    exit;
}
$sec->close();
// DONE
$_SESSION['msg'] = "Student updated successfully!";
$_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
header("Location: manage-student.php");
exit;
?>