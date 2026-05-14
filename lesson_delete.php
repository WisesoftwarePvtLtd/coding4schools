<?php
session_start();
include "standard_constants.php";
include "config.php";

if (!isset($_POST['id'])) {
    echo "Invalid request";
    exit;
}

$lesson_id = intval($_POST['id']);

// Fetch lesson
$q = $conn->query("SELECT course_id, lesson_type FROM lessons WHERE lesson_id = $lesson_id");
if ($q->num_rows === 0) {
    echo "Lesson not found";
    exit;
}

$lesson = $q->fetch_assoc();
$course_id = $lesson['course_id'];

// Folder path
if ($lesson['lesson_type'] === "lesson") {
    $lessonFolder = "uploads/courses/course-$course_id/lesson-$lesson_id/";
} elseif ($lesson['lesson_type'] === "culturalActivity") {
    $lessonFolder = "uploads/courses/course-$course_id/culturalActivity-$lesson_id/";
} else {
    $lessonFolder = "uploads/courses/course-$course_id/syllabus-$lesson_id/";
}

// ================================
// 1️⃣ DELETE PRACTICES (IMPORTANT)
// ================================
$conn->query("DELETE FROM lesson_practices WHERE lesson_id = $lesson_id");

// ================================
// 2️⃣ DELETE AUDIO (ONLY FOR LESSON)
// ================================
if ($lesson['lesson_type'] === "lesson") {
    $audios = $conn->query("SELECT lesson_audio_id, audio_file_path FROM lesson_audio WHERE lesson_id = $lesson_id");
    while ($a = $audios->fetch_assoc()) {
        $filePath = $lessonFolder . $a['audio_file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $conn->query("DELETE FROM lesson_audio WHERE lesson_id = $lesson_id");
}

// ================================
// 3️⃣ DELETE FILES + FOLDER
// ================================
function deleteFolder($dir)
{
    if (!is_dir($dir)) return;

    foreach (scandir($dir) as $file) {
        if ($file != '.' && $file != '..') {
            $path = $dir . '/' . $file;
            is_dir($path) ? deleteFolder($path) : unlink($path);
        }
    }
    rmdir($dir);
}

deleteFolder($lessonFolder);

// ================================
// 4️⃣ DELETE LESSON (PARENT)
// ================================
if ($conn->query("DELETE FROM lessons WHERE lesson_id = $lesson_id")) {
    echo "success";
} else {
    echo "Delete failed";
}
