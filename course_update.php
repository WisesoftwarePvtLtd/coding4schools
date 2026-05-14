<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config.php";

/* =========================
   ALLOW ONLY POST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("invalid_request");
}

/* =========================
   VALIDATION
========================= */
$course_id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$course_title  = trim($_POST['title'] ?? '');
$course_details = trim($_POST['details'] ?? '');
$course_sort_order = intval($_POST['course_sort_order'] ?? 0);




// if (!$course_id || $course_title === '' || $course_details === '') {
//     exit("invalid_input");
// }

/* =========================
   START TRANSACTION
========================= */
$conn->begin_transaction();

try {

    /* =========================
       DUPLICATE CHECK (EXCLUDE SELF)
    ========================= */
    $check = $conn->prepare("
        SELECT course_id 
        FROM courses 
        WHERE LOWER(course_title) = LOWER(?)
          AND course_id != ?
        LIMIT 1
    ");
    $check->bind_param("si", $course_title, $course_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $conn->rollback();
        echo "duplicate";
        exit;
    }
    $check->close();

    /* =========================
   DUPLICATE SORT ORDER CHECK (EXCLUDE SELF)
========================= */
$sortCheck = $conn->prepare("
    SELECT course_id 
    FROM courses 
    WHERE course_sort_order = ?
      AND course_id != ?
    LIMIT 1
");
$sortCheck->bind_param("ii", $course_sort_order, $course_id);
$sortCheck->execute();
$sortCheck->store_result();

if ($sortCheck->num_rows > 0) {
    $conn->rollback();
    echo "duplicate_sort_order";
    exit;
}
$sortCheck->close();

    /* =========================
       FETCH OLD COVER
    ========================= */
    $stmt = $conn->prepare("
        SELECT course_cover_page 
        FROM courses 
        WHERE course_id = ?
    ");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $stmt->bind_result($oldCover);
    $exists = $stmt->fetch();
    $stmt->close();

    if (!$exists) {
        $conn->rollback();
        exit("not_found");
    }

    /* =========================
       COVER UPLOAD
    ========================= */
    $finalCover = $oldCover;
    $uploadDir = "uploads/courses/course-" . $course_id . "/";

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        throw new Exception("directory_create_failed");
    }

    if (!empty($_FILES['cover']['name']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new Exception("invalid_file_type");
        }

        $newCover = "cover_" . time() . "." . $ext;

        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $uploadDir . $newCover)) {
            throw new Exception("upload_failed");
        }

        if ($oldCover && file_exists($uploadDir . $oldCover)) {
            unlink($uploadDir . $oldCover);
        }

        $finalCover = $newCover;
    }

    /* =========================
       UPDATE COURSE
    ========================= */
    $stmt = $conn->prepare("
        UPDATE courses SET
            course_title       = ?,
            course_details     = ?,
            course_cover_page  = ?,
            course_sort_order  = ?
          
        WHERE course_id = ?
    ");

    $stmt->bind_param(
        "sssii",
        $course_title,
        $course_details,
        $finalCover,
        $course_sort_order,
        $course_id
    );

    if (!$stmt->execute()) {
        throw new Exception("update_failed");
    }
    $stmt->close();

    /* =========================
       COMMIT
    ========================= */
    $conn->commit();
    echo "success";
    exit;

} catch (Exception $e) {

    /* =========================
       ROLLBACK
    ========================= */
    $conn->rollback();
    echo "error";
    exit;
}



