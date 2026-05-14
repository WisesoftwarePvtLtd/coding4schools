<?php
session_start();
include "standard_constants.php";
include "config.php";

$first      = trim($_POST['first'] ?? "");
$family     = trim($_POST['family'] ?? "");
$username   = trim($_POST['username'] ?? "");
$password   = trim($_POST['password'] ?? "");
$gender     = trim($_POST['gender'] ?? "");
$user_type  = trim($_POST['userType'] ?? TEACHER);
$schoolId = intval($_POST['schoolId'] ?? 0);

$fullname   = $first . ' ' . $family;

// ----------------------------
// VALIDATION
// ----------------------------
if ($first === "" || $family === "" || $username === "" || $password === "") {
   echo "error";
    exit();
}

$stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "duplicate";
    exit();
}

$stmt->close();



// ----------------------------
// ENCRYPT PASSWORD
// ----------------------------
$key = DECRYPT_KEY;
$iv  = substr(hash("sha256", $key), 0, 16);
$encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);

// ----------------------------
// 1) INSERT INTO USERS TABLE
// ----------------------------
$user_sql = "INSERT INTO users (username, password_hash, full_name, user_type, school_id) 
             VALUES (?, ?, ?, ?,?)";
$user_stmt = $conn->prepare($user_sql);

$user_stmt->bind_param("ssssi",
    $username,
    $encryptedPassword,
    $fullname,
    $user_type,
    $schoolId
);

if (!$user_stmt->execute()) { 
    echo "error";
    exit();
   
}

$user_id = $conn->insert_id; // GET AUTO ID
$user_stmt->close();

// ----------------------------
// 2) INSERT INTO TEACHERS TABLE
// ----------------------------
$teacher_sql = "INSERT INTO teachers (teacher_name, user_id, gender) 
                VALUES (?, ?, ?)";
$teacher_stmt = $conn->prepare($teacher_sql);

$teacher_stmt->bind_param("sis",
    $fullname,
    $user_id,
    $gender
);

if (!$teacher_stmt->execute()) {
    echo "error";
    exit();
}

$teacher_stmt->close();

// ----------------------------
// 3) INSERT INTO USER ROLES
// ----------------------------
$user_role_sql = "INSERT INTO user_roles (role_id, user_id) VALUES (?, ?)";
$role_stmt = $conn->prepare($user_role_sql);

$role_id = TEACHER; // constant

$role_stmt->bind_param("ii", $role_id, $user_id);

if ($role_stmt->execute()) {
    echo "success";
    exit();
} else {

    echo "error";
    exit();
    
}

$role_stmt->close();

header("Location: manage-teacher.php");
exit;
?>
