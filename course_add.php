<?php
session_start();
require_once "config.php";

/* =========================
   ALLOW ONLY POST
========================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("invalid_request");
}

/* =========================
   INPUT VALIDATION
========================= */
$title = trim($_POST['title'] ?? '');
$details = trim($_POST['details'] ?? '');
$course_sort_order = intval($_POST['course_sort_order'] ?? 0);


// if ($title === '' || $details === '' || empty($_FILES['cover']['name'])) {
//     exit("missing_fields");
// }

/* =========================
   FILE VALIDATION
========================= */
$hasCover = isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK;

$allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
$ext = '';

if ($hasCover) {
    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExt)) {
        exit("invalid_file_type");
    }
}

// $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
// $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));

// if (!in_array($ext, $allowedExt)) {
//     exit("invalid_file_type");
// }

/* =========================
   DUPLICATE COURSE CHECK
========================= */
$dupStmt = $conn->prepare("
    SELECT course_id 
    FROM courses 
    WHERE LOWER(course_title) = LOWER(?) 
    LIMIT 1
");
$dupStmt->bind_param("s", $title);
$dupStmt->execute();
$dupStmt->store_result();

if ($dupStmt->num_rows > 0) {
    $dupStmt->close();
    exit("duplicate"); // 🔥 frontend already handles this
}
$dupStmt->close();

/* =========================
   DUPLICATE SORT ORDER CHECK
========================= */
$sortStmt = $conn->prepare("
    SELECT course_id 
    FROM courses 
    WHERE course_sort_order = ? 
    LIMIT 1
");
$sortStmt->bind_param("i", $course_sort_order);
$sortStmt->execute();
$sortStmt->store_result();

if ($sortStmt->num_rows > 0) {
    $sortStmt->close();
    exit("duplicate_sort_order");
}
$sortStmt->close();


/* =========================
   START TRANSACTION
========================= */
$conn->begin_transaction();

try {
    /* =========================
       INSERT COURSE (WITHOUT COVER)
    ========================= */
    $stmt = $conn->prepare("
        INSERT INTO courses
        (course_title, course_details, course_cover_page, course_sort_order)
        VALUES (?,?,?,?)
    ");

    $emptyCover = '';

    $stmt->bind_param(
        "sssi",
        $title,
        $details,
        $emptyCover,
        $course_sort_order
    );

    if (!$stmt->execute()) {
        throw new Exception("course_insert_failed");
    }

    $course_id = $stmt->insert_id;
    $stmt->close();

    /* =========================
       IMAGE UPLOAD
    ========================= */
    if ($hasCover) {
        $uploadDir = "uploads/courses/course-" . $course_id . "/";

        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception("directory_create_failed");
        }

        $filename = "cover_" . time() . "." . $ext;

        if (!move_uploaded_file($_FILES['cover']['tmp_name'], $uploadDir . $filename)) {
            throw new Exception("upload_failed");
        }

        /* =========================
           UPDATE COVER NAME
        ========================= */
        $stmt = $conn->prepare("
        UPDATE courses
        SET course_cover_page = ?
        WHERE course_id = ?
    ");
        $stmt->bind_param("si", $filename, $course_id);

        if (!$stmt->execute()) {
            throw new Exception("cover_update_failed");
        }
        $stmt->close();
    }

    /* =========================
       COMMIT TRANSACTION
    ========================= */
    $conn->commit();
    echo "success";
    exit;
} catch (Exception $e) {

    /* =========================
       ROLLBACK ON ERROR
    ========================= */
    $conn->rollback();

    if (isset($uploadDir) && is_dir($uploadDir)) {
        array_map('unlink', glob($uploadDir . "*"));
        @rmdir($uploadDir);
    }

    echo $e->getMessage();
    exit;
}
