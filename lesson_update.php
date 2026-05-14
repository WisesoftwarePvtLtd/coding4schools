<?php
session_start();
include "standard_constants.php";
include "config.php";

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ===============================
   BASIC VALIDATION
================================ */
if (
    empty($_POST['lesson_id']) ||
    empty($_POST['course_id'])
) {
    exit("missing_fields");
}

$lesson_id = intval($_POST['lesson_id']);
$course_id = intval($_POST['course_id']);
$lesson_name = trim($_POST['lesson_name']);
$lesson_guideline = trim($_POST['lesson_guideline']);
$lesson_sort_order = intval($_POST['lesson_sort_order'] ?? 0);
$editor_url = trim($_POST['lesson_editor'] ?? '');


/* ===============================
   SAFE DELETE FLAGS
================================ */
$lesson_deleted = $_POST['lesson_deleted'] ?? 0;
$lesson_plan_deleted = $_POST['lesson_plan_pdf_deleted'] ?? 0;
$lesson_summary_deleted = $_POST['lesson_summary_pdf_deleted'] ?? 0;
$lesson_video_deleted = $_POST['lesson_video_deleted'] ?? 0;
$lesson_image_deleted = $_POST['remove_lesson_guideline_image'] ?? 0;
$teacher_game_deleted = $_POST['teacher_game_file_deleted'] ?? 0;
$student_game_deleted = $_POST['student_game_file_deleted'] ?? 0;


/* ===============================
   START TRANSACTION
================================ */
$conn->begin_transaction();

try {

    /* ===============================
       DUPLICATE CHECK (CRITICAL)
       same title + same course
    ================================ */
    if ($lesson_name !== '') {
        $check = $conn->prepare("
        SELECT lesson_id
        FROM lessons
        WHERE course_id = ?
          AND LOWER(lesson_title) = LOWER(?)
          AND lesson_id != ?
        LIMIT 1
    ");
        $check->bind_param("isi", $course_id, $lesson_name, $lesson_id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $conn->rollback();
            echo "Lesson Already Exist ";
            exit;
        }
        $check->close();
    }

    /* =========================
     DUPLICATE SORT ORDER CHECK (UPDATE)
  ========================= */
    $check = $conn->prepare("
    SELECT lesson_id 
    FROM lessons 
    WHERE course_id = ? 
      AND lesson_sort_order = ?
      AND lesson_id != ?
    LIMIT 1
");

    $check->bind_param("iii", $course_id, $lesson_sort_order, $lesson_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->close();
        die("duplicate_sort_order");
    }

    $check->close();

    /* ===============================
       FETCH OLD DATA
    ================================ */
    $stmt = $conn->prepare("SELECT * FROM lessons WHERE lesson_id = ?");
    $stmt->bind_param("i", $lesson_id);
    $stmt->execute();
    $old = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$old) {
        $conn->rollback();
        exit("lesson_not_found");
    }

    /* ===============================
       CREATE FOLDERS
    ================================ */
    $courseFolder = "uploads/courses/course-$course_id";
    $lessonFolder = "$courseFolder/lesson-$lesson_id";

    if (!is_dir($lessonFolder) && !mkdir($lessonFolder, 0777, true)) {
        throw new Exception("folder_create_failed");
    }

    /* ===============================
       FILE UPLOAD FUNCTION
    ================================ */
    function uploadSingle($field, $path, $oldFile = "", $deleteFlag = 0)
    {
        // Case 1: delete requested
        if ($deleteFlag == 1) {
            if ($oldFile && file_exists("$path/$oldFile")) {
                unlink("$path/$oldFile");
            }

            // upload new if provided
            if (!empty($_FILES[$field]['name'])) {
                $clean = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$field]['name']);
                $name = time() . "_" . $clean;
                move_uploaded_file($_FILES[$field]['tmp_name'], "$path/$name");
                return $name;
            }

            return "";
        }

        // Case 2: new upload provided (IMPORTANT FIX)
        if (!empty($_FILES[$field]['name'])) {
            if ($oldFile && file_exists("$path/$oldFile")) {
                unlink("$path/$oldFile");
            }

            $clean = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$field]['name']);
            $name = time() . "_" . $clean;
            move_uploaded_file($_FILES[$field]['tmp_name'], "$path/$name");
            return $name;
        }

        // Case 3: nothing changed
        return $oldFile;
    }

    // function uploadSingle($field, $path, $oldFile = "", $deleteFlag = 0)
    // {
    //     if ($deleteFlag == 1) {
    //         if ($oldFile && file_exists("$path/$oldFile")) {
    //             unlink("$path/$oldFile");
    //         }

    //         if (!empty($_FILES[$field]['name'])) {
    //             $clean = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$field]['name']);
    //             $name  = time() . "_" . $clean;
    //             move_uploaded_file($_FILES[$field]['tmp_name'], "$path/$name");
    //             return $name;
    //         }
    //         return "";
    //     }

    //     if (empty($_FILES[$field]['name'])) {
    //         return $oldFile;
    //     }

    //     if ($oldFile && file_exists("$path/$oldFile")) {
    //         unlink("$path/$oldFile");
    //     }

    //     $clean = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES[$field]['name']);
    //     $name  = time() . "_" . $clean;
    //     move_uploaded_file($_FILES[$field]['tmp_name'], "$path/$name");

    //     return $name;
    // }

    /* ===============================
       PROCESS FILES
    ================================ */
    $lesson_pdf = uploadSingle(
        "lesson",
        $lessonFolder,
        basename($old['lesson_file_path']),
        $lesson_deleted
    );

    $lesson_plan_pdf = uploadSingle(
        "lesson_plan_pdf",
        $lessonFolder,
        basename($old['lesson_plan_path']),
        $lesson_plan_deleted
    );

    $lesson_summary_pdf = uploadSingle(
        "lesson_summary_pdf",
        $lessonFolder,
        basename($old['lesson_summary_path']),
        $lesson_summary_deleted
    );

    $lesson_video = uploadSingle(
        "lesson_video",
        $lessonFolder,
        basename($old['lesson_video_path']),
        $lesson_video_deleted
    );

    $lesson_guideline_image = uploadSingle(
        "lesson_guideline_image",
        $lessonFolder,
        basename($old['lesson_guideline_image']),
        $lesson_image_deleted
    );

    $teacher_game_file = uploadSingle(
        "teacher_game_file",
        $lessonFolder,
        basename($old['teacher_game_file'] ?? ''),
        $teacher_game_deleted
    );

    $student_game_file = uploadSingle(
        "student_game_file",
        $lessonFolder,
        basename($old['student_game_file'] ?? ''),
        $student_game_deleted
    );

    /* ===============================
       BUILD PATHS
    ================================ */
    $lesson_pdf_path = $lesson_pdf ? "$lessonFolder/$lesson_pdf" : "";
    $lesson_plan_pdf_path = $lesson_plan_pdf ? "$lessonFolder/$lesson_plan_pdf" : "";
    $lesson_summary_path = $lesson_summary_pdf ? "$lessonFolder/$lesson_summary_pdf" : "";
    $lesson_video_path = $lesson_video ? "$lessonFolder/$lesson_video" : "";
    $lesson_guideline_image_path = $lesson_guideline_image ? "$lessonFolder/$lesson_guideline_image" : "";
    $teacher_game_file_path = $teacher_game_file ? "$lessonFolder/$teacher_game_file" : "";
    $student_game_file_path = $student_game_file ? "$lessonFolder/$student_game_file" : "";

    /* ===============================
       UPDATE LESSON
    ================================ */
    $upd = $conn->prepare("
    UPDATE lessons SET 
        lesson_title       = ?, 
        lesson_guideline   = ?,
        lesson_sort_order  = ?, 
        lesson_file_path   = ?, 
        lesson_plan_path   = ?, 
        lesson_summary_path= ?,
        lesson_video_path  = ?,
        lesson_guideline_image = ?,
        teacher_game_file  = ?,
        student_game_file  = ?,
        editor_url         = ?
    WHERE lesson_id = ?
");

    $upd->bind_param(
        "ssissssssssi",
        $lesson_name,
        $lesson_guideline,
        $lesson_sort_order,
        $lesson_pdf_path,
        $lesson_plan_pdf_path,
        $lesson_summary_path,
        $lesson_video_path,
        $lesson_guideline_image_path,
        $teacher_game_file_path,
        $student_game_file_path,
        $editor_url,
        $lesson_id
    );

    if (!$upd->execute()) {
        throw new Exception("update_failed");
    }
    $upd->close();

    /* ===============================
       COMMIT
    ================================ */
    $conn->commit();
    echo "success";
    exit;

} catch (Exception $e) {

    /* ===============================
       ROLLBACK
    ================================ */
    $conn->rollback();
    echo "error";
    exit;
}


