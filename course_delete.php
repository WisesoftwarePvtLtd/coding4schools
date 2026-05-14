<?php
require_once "config.php";

/* =========================
   VALIDATE ID
========================= */
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    exit("invalid_id");
}

/* =========================
   CHECK COURSE EXISTS
========================= */
$stmt = $conn->prepare("SELECT course_cover_page FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($cover);
$exists = $stmt->fetch();
$stmt->close();

if (!$exists) {
    exit("not_found");
}

/* =========================
   DELETE DB RECORD FIRST
========================= */
$stmt = $conn->prepare("DELETE FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    exit("db_error");
}
$stmt->close();

/* =========================
   DELETE FILES & FOLDER
========================= */
$courseFolder = __DIR__ . "/uploads/courses/course-$id/";

if (is_dir($courseFolder)) {

    foreach (glob($courseFolder . "*") as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    rmdir($courseFolder);
}

echo "success";
exit;

















