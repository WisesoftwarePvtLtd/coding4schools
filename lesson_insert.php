<?php
session_start();
require_once "config.php";


/* =========================
   BASIC VALIDATION
========================= */
if (empty($_POST['course_id']) ) {
    exit("missing_fields");
}

if (empty($_POST['lesson_sort_order'])) {
    exit("missing_sort_order");
}

$course_id = intval($_POST['course_id']);
$lesson_name = trim($_POST['lesson_name'] ?? '');
$lesson_guideline = trim($_POST['lesson_guideline']);
$lesson_type = $_POST['lesson_type'] ?? 'lesson';
$lesson_sort_order = intval($_POST['lesson_sort_order']);
$editor_url = trim($_POST['lesson_editor'] ?? '');

// ---------------- DUPLICATE CHECK ----------------

if ($lesson_name !== '') {

    // 🔹 ONLY check duplicate when NOT empty
    $check = $conn->prepare("
        SELECT lesson_id 
        FROM lessons 
        WHERE course_id = ? AND lesson_title = ?
        LIMIT 1
    ");
    $check->bind_param("is", $course_id, $lesson_name);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        die("Lesson Already Exist!");
    }

    $check->close();
}

// 2️⃣ SAME SORT ORDER IN SAME COURSE
$check = $conn->prepare("
    SELECT lesson_id 
    FROM lessons 
    WHERE course_id = ? AND lesson_sort_order = ?
    LIMIT 1
");
$check->bind_param("ii", $course_id, $lesson_sort_order);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    $check->close();
    die("duplicate_sort_order");
}
$check->close();


/* =========================
   INSERT BASIC LESSON
========================= */
$stmt = $conn->prepare("
        INSERT INTO lessons 
        (course_id, lesson_title, lesson_guideline, lesson_type, lesson_sort_order, editor_url)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
$stmt->bind_param("isssis", $course_id, $lesson_name, $lesson_guideline, $lesson_type, $lesson_sort_order, $editor_url);

if (!$stmt->execute()) {
    throw new Exception("lesson_not_created");
}

$lesson_id = $stmt->insert_id;
$stmt->close();

/* =========================
   CREATE FOLDERS
========================= */
$courseFolder = "uploads/courses/course-$course_id";
$lessonFolder = "$courseFolder/lesson-$lesson_id";
if (!is_dir($courseFolder))
    mkdir($courseFolder, 0777, true);
if (!is_dir($lessonFolder))
    mkdir($lessonFolder, 0777, true);

/* =========================
   FILE UPLOAD HELPER
========================= */
function uploadSingle($fieldName, $path)
{
    if (!empty($_FILES[$fieldName]['name'])) {

        $cleanName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$fieldName]['name']);
        $newName = time() . "_" . $cleanName;

        move_uploaded_file($_FILES[$fieldName]['tmp_name'], "$path/$newName");
        return $newName;
    }
    return "";
}

/* =========================
   UPLOAD FILES
========================= */
$lesson_pdf = uploadSingle('lesson', $lessonFolder);
$lesson_plan_pdf = uploadSingle('lesson_plan_pdf', $lessonFolder);
$lesson_summary = uploadSingle('lesson_summary', $lessonFolder);
$lesson_video = uploadSingle('lesson_video', $lessonFolder);
$lesson_guideline_image = uploadSingle('lesson_guideline_image', $lessonFolder);
$teacher_game_file = uploadSingle('teacher_game_file', $lessonFolder);
$student_game_file = uploadSingle('student_game_file', $lessonFolder);

$teacher_game_file_path = ($teacher_game_file != "") ? "$lessonFolder/$teacher_game_file" : "";
$student_game_file_path = ($student_game_file != "") ? "$lessonFolder/$student_game_file" : "";


$lesson_pdf_path = ($lesson_pdf != "") ? "$lessonFolder/$lesson_pdf" : "";
$lesson_plan_pdf_path = ($lesson_plan_pdf != "") ? "$lessonFolder/$lesson_plan_pdf" : "";
$lesson_summary_path = ($lesson_summary != "") ? "$lessonFolder/$lesson_summary" : "";
$lesson_video_path = ($lesson_video != "") ? "$lessonFolder/$lesson_video" : "";
$lesson_guideline_image_path = ($lesson_guideline_image != "") ? "$lessonFolder/$lesson_guideline_image" : "";



/* =========================
   MULTIPLE AUDIO UPLOAD
========================= */


/* =========================
   UPDATE LESSON FILE PATHS
========================= */
$upd = $conn->prepare("
    UPDATE lessons SET
        lesson_file_path = ?,
        lesson_plan_path = ?,
        lesson_summary_path = ?,
        lesson_video_path = ?,
        lesson_guideline_image = ?,
        teacher_game_file = ?,
        student_game_file = ?
    WHERE lesson_id = ?
");

$upd->bind_param(
    "sssssssi",
    $lesson_pdf_path,
    $lesson_plan_pdf_path,
    $lesson_summary_path,
    $lesson_video_path,
    $lesson_guideline_image_path,
    $teacher_game_file_path,
    $student_game_file_path,
    $lesson_id
);
$upd->execute();
$upd->close();

/* =========================
   COMMIT
========================= */
$conn->commit();
echo "success";
exit;














