<?php
session_start();
include 'config.php';
include 'standard_constants.php';

/* =========================
   FETCH & VALIDATE INPUT
========================= */
$problem_id = intval($_GET['problem_id'] ?? 0);
$lesson_id  = intval($_GET['lesson_id'] ?? 0);

if ($problem_id <= 0) {
    $_SESSION['msg'] = "Invalid request.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_problem.php?lesson_id=$lesson_id");
    exit;
}

/* =========================
   FETCH IMAGE (for delete)
========================= */
$imgStmt = $conn->prepare("
    SELECT problem_image
    FROM problem
    WHERE problem_id = ?
");
$imgStmt->bind_param("i", $problem_id);
$imgStmt->execute();
$result = $imgStmt->get_result();
$data = $result->fetch_assoc();
$imgStmt->close();

/* =========================
   DELETE IMAGE FILE
========================= */
if (!empty($data['problem_image'])) {
    $imagePath = "uploads/" . $data['problem_image'];
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

/* =========================
   DELETE problem
========================= */
$delStmt = $conn->prepare("
    DELETE FROM problem
    WHERE problem_id = ?
");
$delStmt->bind_param("i", $problem_id);
$delStmt->execute();
$delStmt->close();

/* =========================
   SUCCESS
========================= */
$_SESSION['msg'] = "Problem deleted successfully!";
$_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_problem.php?lesson_id=$lesson_id");
exit;
