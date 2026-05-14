<?php 
session_start();
include "standard_constants.php";
include "config.php";
$course_id = intval($_POST['course_id']);
$course_name =$_POST['course_name'];
// ----------- BASIC VALIDATION -----------
if (empty($_POST['course_id']) || empty($_POST['syllabus_name'])) {
    $_SESSION['msg'] = "Missing required fields!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
      header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));

    exit;
}

// $course_id = intval($_POST['course_id']);
$syllabus_name = $_POST['syllabus_name'];
$syllabus_type = $_POST['syllabus_type'];
$sort_order = 1000; //big number so that syllabus is display alway last 

// -----------------------------------------
// 1️⃣ Insert basic lesson details
// -----------------------------------------
$stmt = $conn->prepare("INSERT INTO lessons (course_id, lesson_title, lesson_type, lesson_sort_order) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issi", $course_id, $syllabus_name, $syllabus_type,$sort_order);
$stmt->execute();

$lesson_id = $stmt->insert_id;
// print_r($stmt);die;
if (!$lesson_id) {
    die("lesson_not_created");
}

// -----------------------------------------
// 2️⃣ Create Folder Structure
// uploads/books/Book-20/lesson-1
// -----------------------------------------

$courseFolder = "uploads/courses/course-$course_id";
$lessonFolder = "$courseFolder/syllabus-$lesson_id";

if (!is_dir($courseFolder)) mkdir($courseFolder, 0777, true);
if (!is_dir($lessonFolder)) mkdir($lessonFolder, 0777, true);

// -----------------------------------------
// 3️⃣ File Upload Function
// -----------------------------------------
function uploadSingle($fieldName, $path) {
    if (!empty($_FILES[$fieldName]['name'])) {

        $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$fieldName]['name']);
        $newName = time() . "_" . $cleanName;

        move_uploaded_file($_FILES[$fieldName]['tmp_name'], "$path/$newName");
        return $newName;
    }
    return "";
}

// Upload single files
$syllabus_pdf      = uploadSingle('syllabus', $lessonFolder);
$syllabus_pdf_path      = ($syllabus_pdf != "")      ? "$lessonFolder/$syllabus_pdf" : "";


// -----------------------------------------
// 5️⃣ UPDATE LESSON RECORD
// -----------------------------------------

$upd = $conn->prepare("UPDATE lessons 
       SET lesson_file_path=?
       WHERE lesson_id=?");

$upd->bind_param("si", 
    $syllabus_pdf_path,   
    $lesson_id
);

// $upd->execute();

if ($upd->execute()) {
    // ✅ Set success message in session
    $_SESSION['msg'] = "Syllabus Added Successfully!";
     $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

    // Redirect
    header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));
    exit;
} else {
    $_SESSION['msg'] = "Syllabus Not Added!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;

    // Redirect
    header("Location: manage-lesson.php?course_id=$course_id&course_name=" . urlencode($course_name));
    exit;
}

?>
