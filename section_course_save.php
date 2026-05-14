<?php
session_start();
include "standard_constants.php";
include "config.php";

// Input values
$sectioncourseId = intval($_POST['section_course_id'] ?? 0);
$courseId        = intval($_POST['course_id']);
$sectionId     = intval($_POST['section_id']);
$gradeId       = intval($_POST['grade_id']);

// Validate
if ($courseId == 0 || $sectionId == 0) {
    $_SESSION['msg']  = "Please select a course.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: assign-course-section.php?section=$sectionId&grade=$gradeId");
    exit();
}

/* ---------------------------------------------------------
   🔥 DUPLICATE CHECK 
--------------------------------------------------------- */

if ($sectioncourseId == 0) {

    // INSERT duplicate check
    $dup = $conn->prepare("
        SELECT section_course_id 
        FROM section_courses 
        WHERE course_id = ? AND section_id = ?
    ");
    $dup->bind_param("ii", $courseId, $sectionId);

} else {

    // UPDATE duplicate check → ignore current row
    $dup = $conn->prepare("
        SELECT section_course_id 
        FROM section_courses 
        WHERE course_id = ? AND section_id = ? 
        AND section_course_id != ?
    ");
    $dup->bind_param("iii", $courseId, $sectionId, $sectioncourseId);
}

$dup->execute();
$dup->store_result();

if ($dup->num_rows > 0) {
    $_SESSION['msg']  = "This course is already assigned to this section!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: assign-course-section.php?section=$sectionId&grade=$gradeId");
    exit();
}

$dup->close();

/* ---------------------------------------------------------
   🔥 INSERT / UPDATE OPERATION
--------------------------------------------------------- */

if ($sectioncourseId == 0) {

    // INSERT
    $stmt = $conn->prepare("INSERT INTO section_courses (course_id,grade_id, section_id) VALUES (?, ?,?)");
    $stmt->bind_param("iii", $courseId,$gradeId  , $sectionId);
    $stmt->execute();

    $_SESSION['msg']  = "course assigned successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

} else {

    // UPDATE
    $stmt = $conn->prepare("UPDATE section_courses SET course_id = ? WHERE section_course_id = ?");
    $stmt->bind_param("ii", $courseId, $sectioncourseId);
    $stmt->execute();

    $_SESSION['msg']  = "course updated successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
}

$stmt->close();

header("Location: assign-course-section.php?section=$sectionId&grade=$gradeId");
exit();

?>
