<?php
session_start();
include 'standard_constants.php';
include 'config.php';

// Optional: Only allow admins to delete
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'siteadmin') {
//     $_SESSION['msg'] = "Access Denied!";
//     $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
//     header("Location: manage_admin.php");
//     exit;
// }

// // Validate user_id
// if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
//     $_SESSION['msg'] = "Invalid user ID!";
//     $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
//     header("Location: manage_admin.php");
//     exit;
// }

$user_id = intval($_GET['id']);

// Check if the user exists and is of type schooladmin/siteadmin
$sql = "SELECT user_type FROM users WHERE user_id = ? AND user_type IN (" . SCHOOLADMIN . "," . SITEADMIN . ")";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['msg'] = "User not found or cannot delete this user type!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_admin.php");
    exit;
}
// 3. DELETE FROM user_roles
$stmt4 = mysqli_prepare($conn, "DELETE FROM user_roles WHERE user_id=?");
mysqli_stmt_bind_param($stmt4, "i", $user_id);
$ok4 = mysqli_stmt_execute($stmt4);
mysqli_stmt_close($stmt4);
// Delete user
$delete_sql = "DELETE FROM users WHERE user_id = ?";
$stmt_del = $conn->prepare($delete_sql);
$stmt_del->bind_param("i", $user_id);

if ($stmt_del->execute()) {
    $_SESSION['msg'] = "User deleted successfully!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg'] = "Failed to delete user. Try again.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

header("Location: manage_admin.php");
exit;
?>
