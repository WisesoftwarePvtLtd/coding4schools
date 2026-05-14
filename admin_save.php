<?php
session_start();
include 'standard_constants.php';
include 'config.php';

// Validate POST
if (!isset($_POST['username']) || trim($_POST['username']) === '') {
    $_SESSION['msg'] = "Username is required!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_admin.php");
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password'] ?? '');
$userId = intval($_POST['userId'] ?? 0);
$user_type = $_POST['user_type'] ?? SCHOOLADMIN;
$school_id = intval($_POST['school_id'] ?? 0); // NEW FIELD

// --------------------------
// ADD NEW USER
// --------------------------
if ($userId === 0) {

    // Check duplicate username
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['msg'] = "Username already exists!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        header("Location: manage_admin.php");
        exit;
    }
    $stmt->close();

    // Password required
    if ($password === '') {
        $_SESSION['msg'] = "Password is required!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        header("Location: manage_admin.php");
        exit;
    }

    // Encrypt password
    $key = DECRYPT_KEY;
    $iv = substr(hash("sha256", $key), 0, 16);
    $encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, user_type, school_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $encryptedPassword, $user_type, $school_id);
    $stmt->execute();

    $user_id = $stmt->insert_id;
    $stmt->close();

    // Assign default role
    // $role_id = STUDENT;

    $roleStmt = $conn->prepare("INSERT INTO user_roles (role_id, user_id) VALUES (?, ?)");
    $roleStmt->bind_param("ii", $user_type, $user_id);
    $roleStmt->execute();
    $roleStmt->close();

    $_SESSION['msg'] = "User added successfully!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

} else {

    // --------------------------
    // UPDATE EXISTING USER
    // --------------------------

    // Fetch existing password
    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id=? LIMIT 1");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($existing_hash);
    $stmt->fetch();
    $stmt->close();

    // Update password only if entered
    if ($password !== '') {
        $key = DECRYPT_KEY;
        $iv = substr(hash("sha256", $key), 0, 16);
        $existing_hash = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);
    }

    // Update user
    $stmt = $conn->prepare("UPDATE users SET username=?, password_hash=?, user_type=?, school_id=? WHERE user_id=?");
    $stmt->bind_param("sssii", $username, $existing_hash, $user_type, $school_id, $userId);
    $stmt->execute();
    $stmt->close();

    $roleStmt = $conn->prepare("UPDATE user_roles SET role_id=? WHERE user_id=?");
    $roleStmt->bind_param("ii", $user_type, $userId);
    $roleStmt->execute();
    $roleStmt->close();

    $_SESSION['msg'] = "User updated successfully!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
}

header("Location: manage_admin.php");
exit;
?>