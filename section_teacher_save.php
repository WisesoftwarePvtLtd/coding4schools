<?php
session_start();
include "standard_constants.php";
include "config.php";

$teacherId  = intval($_POST['teacher_id']);
$sectionId  = intval($_POST['section_id']);
$gradeId    = intval($_POST['grade_id']);
$sectionTeacherId = intval($_POST['section_teacher_id'] ?? 0);

// Validation
if ($teacherId == 0 || $sectionId == 0) {
    $_SESSION['msg']  = "Teacher and Section are required!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: assign-teacher-section.php?section=$sectionId&grade=$gradeId");
    exit();
}

/* ---------------------------------------------------------
   🔥 1) DUPLICATE CHECK 
--------------------------------------------------------- */

// Insert time → check direct duplicate
// Update time → check except current section_teacher_id

if ($sectionTeacherId == 0) {

    // INSERT duplicate check
    $dup = $conn->prepare("
        SELECT section_teacher_id 
        FROM section_teachers 
        WHERE teacher_id = ? AND section_id = ? AND grade_id = ?
    ");
    $dup->bind_param("iii", $teacherId, $sectionId, $gradeId);

} else {

    // UPDATE duplicate check (ignore itself)
    $dup = $conn->prepare("
        SELECT section_teacher_id 
        FROM section_teachers 
        WHERE teacher_id = ? AND section_id = ? AND grade_id = ? 
        AND section_teacher_id != ?
    ");
    $dup->bind_param("iiii", $teacherId, $sectionId, $gradeId, $sectionTeacherId);
}

$dup->execute();
$dup->store_result();

if ($dup->num_rows > 0) {
    $_SESSION['msg']  = "This teacher is already assigned to this section!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: assign-teacher-section.php?section=$sectionId&grade=$gradeId");
    exit();
}

$dup->close();

/* ---------------------------------------------------------
   🔥 2) INSERT / UPDATE
--------------------------------------------------------- */

if ($sectionTeacherId == 0) {

    // INSERT
    $stmt = $conn->prepare("INSERT INTO section_teachers (teacher_id, section_id, grade_id) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $teacherId, $sectionId, $gradeId);

    if ($stmt->execute()) {
        $_SESSION['msg']  = "Teacher assigned successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg']  = "Failed to assign teacher!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }

} else {

    // UPDATE
    $stmt = $conn->prepare("UPDATE section_teachers SET teacher_id = ? WHERE section_teacher_id = ?");
    $stmt->bind_param("ii", $teacherId, $sectionTeacherId);

    if ($stmt->execute()) {
        $_SESSION['msg']  = "Teacher updated successfully!";
         $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
    } else {
        $_SESSION['msg']  = "Failed to update teacher!";
        $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    }
}

$stmt->close();

header("Location: assign-teacher-section.php?section=$sectionId&grade=$gradeId");
exit();
?>
