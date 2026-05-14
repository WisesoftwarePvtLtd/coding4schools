<?php
include "config.php";
include "standard_constants.php";
$id         = $_POST['id'];        // user_id
$first      = $_POST['first'];
$family     = $_POST['family'];
$username   = $_POST['username'];
$password   = $_POST['password'];
$gender     = $_POST['gender'];
$schoolId = intval($_POST['schoolId'] ?? 0);


$fullname   = $first . ' ' . $family;

if (!$id) {
    die("Invalid");
}

// NOW RUN ORIGINAL CODE
$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
$stmt->bind_param("si", $username, $id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "duplicate";
    exit();
}
$stmt->close();

// Encrypt if password provided
$key = DECRYPT_KEY;
$iv  = substr(hash("sha256", $key), 0, 16);

if ($password != "") {
    $encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);

    // Update USER table including password
    $user_sql = "UPDATE users SET username=?, full_name=?, password_hash=?, school_id=? WHERE user_id=?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "sssii", $username, $fullname, $encryptedPassword, $schoolId, $id);

} else {
    // Update USER table excluding password
    $user_sql = "UPDATE users SET username=?, full_name=?, school_id=? WHERE user_id=?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "ssii", $username, $fullname, $schoolId, $id);
}

if (!mysqli_stmt_execute($user_stmt)) {
    die("User update failed");
}

mysqli_stmt_close($user_stmt);

// ----------------------------------
// UPDATE TEACHERS TABLE
// ----------------------------------

$teacher_sql = "UPDATE teachers SET teacher_name=?, gender=? WHERE user_id=?";
$teacher_stmt = mysqli_prepare($conn, $teacher_sql);
mysqli_stmt_bind_param($teacher_stmt, "ssi", $fullname, $gender, $id);

if (mysqli_stmt_execute($teacher_stmt)) {
    echo "success";
} else {
    echo "error";
}

mysqli_stmt_close($teacher_stmt);
?>
