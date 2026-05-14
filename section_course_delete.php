<?php
session_start();
include "standard_constants.php";
include "config.php";

$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$grade   = isset($_GET['grade']) ? htmlspecialchars($_GET['grade']) : '';

if ($id == 0) {
    $_SESSION['msg']  = "Invalid request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: assign-course-section.php?section=$section&grade=$grade");
    exit();
}

$sql = "DELETE FROM section_courses WHERE section_course_id = $id";

if ($conn->query($sql)) {
    $_SESSION['msg']  = "course removed successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
} else {
    $_SESSION['msg']  = "Failed to remove course!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
}

header("Location: assign-course-section.php?section=$section&grade=$grade");
exit();
?>
