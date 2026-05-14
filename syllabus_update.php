<?php
session_start();
include "standard_constants.php";
include "config.php";

// DEBUG (temporary) - uncomment to inspect incoming data
// echo "<pre>"; print_r($_POST); print_r($_FILES); die();
$course_id = intval($_POST['course_id']);
$course_name =$_POST['course_name'];
if (empty($_POST['lesson_id']) || empty($_POST['course_id']) || empty($_POST['syllabus_name'])) {
     $_SESSION['msg'] = "Missing required fields!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
      header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));

    exit;
}

$lesson_id  = intval($_POST['lesson_id']);
// $course_id    = intval($_POST['course_id']);
$syllabus_name = trim($_POST['syllabus_name']);
$lesson_type = $_POST['lesson_type'] ?? "";


// -----------------------------
// FETCH OLD DATA
// -----------------------------
$old_q = $conn->query("SELECT * FROM lessons WHERE lesson_id = $lesson_id");
$old = $old_q ? $old_q->fetch_assoc() : null;
if (!$old) die("lesson_not_found");

// -----------------------------
// CREATE FOLDER STRUCTURE
// -----------------------------
$courseFolder = "uploads/courses/course-$course_id";
$lessonFolder = "$courseFolder/syllabus-$lesson_id";

if (!is_dir($courseFolder)) mkdir($courseFolder, 0777, true);
if (!is_dir($lessonFolder)) mkdir($lessonFolder, 0777, true);
$oldFileName = "";

if (!empty($old['lesson_file_path'])) {
    $oldFileName = basename($old['lesson_file_path']);
}

// -----------------------------
// FILE UPLOAD FUNCTION
// -----------------------------
function uploadSingle($fieldName, $path, $oldFile = "")
{
    if (!empty($_FILES[$fieldName]['name']) && is_uploaded_file($_FILES[$fieldName]['tmp_name'])) {

        // delete old file
        if ($oldFile && file_exists("$path/$oldFile")) {
            @unlink("$path/$oldFile");
        }

        $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$fieldName]['name']);
        $newName = time() . "_" . $cleanName;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], "$path/$newName")) {
            return $newName;
        } else {
            return $oldFile; // fallback if move fails
        }
    }
    return $oldFile; // keep old file if no new upload
}

$newFileName = uploadSingle("syllabus", $lessonFolder, $oldFileName);

// 👉 IF NEW FILE UPLOADED
if ($newFileName != "") {
    $syllabus_pdf_path = "$lessonFolder/$newFileName";
} 
// 👉 ELSE KEEP OLD FILE PATH
else {
    $syllabus_pdf_path = $old['lesson_file_path'];
}


// Upload updated files (will keep old if no new upload)
// $syllabus_pdf      = uploadSingle("syllabus", $lessonFolder, $old['lesson_file_path']);
// $syllabus_pdf_path      = ($syllabus_pdf != "")      ? "$lessonFolder/$syllabus_pdf" : "";

// -----------------------------
// UPDATE LESSON (fixed SQL and bind types)
// -----------------------------
$upd = $conn->prepare("
    UPDATE lessons SET 
        lesson_title = ?, 
        lesson_type = ?, 
        lesson_file_path = ? 
    WHERE lesson_id = ?
");

if (!$upd) {
    die("prepare_failed: " . $conn->error);
}

$upd->bind_param(
    "sssi",
    $syllabus_name,
    $lesson_type,
    $syllabus_pdf_path,
    $lesson_id
);

if ($upd->execute()) {
    // ✅ Set success message in session
    $_SESSION['msg'] = "Syllabus Update Successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

    // Redirect
    header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));
    exit;
} else {
    $_SESSION['msg'] = "Syllabus Not Update !";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;

    // Redirect
    header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));
    exit;
}
?>
