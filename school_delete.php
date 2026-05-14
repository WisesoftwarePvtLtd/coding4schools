<?php
session_start();
include "config.php";

$school_id = intval($_GET['school_id'] ?? 0);

if ($school_id <= 0) {
    die("Invalid school");
}

/* GET IMAGE */

$stmt = $conn->prepare("
SELECT school_profile_image
FROM schools
WHERE school_id = ?
");

$stmt->bind_param("i", $school_id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("School not found");
}

$image = $data['school_profile_image'];

/* DELETE IMAGE FILE */

if (!empty($image) && file_exists($image)) {
    unlink($image);
}

/* DELETE SCHOOL */

$stmt = $conn->prepare("
DELETE FROM schools
WHERE school_id = ?
");

$stmt->bind_param("i", $school_id);

if ($stmt->execute()) {

    $_SESSION['msg'] = "School Deleted Successfully";
    $_SESSION['transaction_status'] = "success";

} else {

    $_SESSION['msg'] = "Failed to delete school";
    $_SESSION['transaction_status'] = "error";

}

/* REDIRECT */

header("Location: manage_schools.php");
exit;