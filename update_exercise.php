<?php
session_start();
include 'config.php';
include 'standard_constants.php';
function uploadFile($file, $folder = "uploads/")
{
    if (!empty($file['name'])) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time(). "_" . uniqid() . "." . $ext;
        $target = $folder . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $filename;
        }
    }
    return null;
}
/* =========================
   FETCH & VALIDATE INPUT
========================= */
$exercise_id = intval($_POST['exercise_id'] ?? 0);
$lesson_id   = intval($_POST['lesson_id'] ?? 0);
$title       = trim($_POST['title'] ?? '');
$editor_id   = intval($_POST['editor_id'] ?? 0);

$instruction_text = trim($_POST['instruction_text'] ?? '');
$hint_text        = trim($_POST['hint_text'] ?? '');
$hint_video       = trim($_POST['hint_video'] ?? '');

if ($exercise_id <= 0 || $lesson_id <= 0 || $editor_id <= 0 || $title === '') {
    $_SESSION['msg'] = "Invalid or missing data.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_exercise.php?lesson_id=$lesson_id");
    exit;
}

$chk = $conn->prepare("SELECT exercise_id FROM exercises WHERE lesson_id = ? AND exercise_name = ? AND exercise_id != ?");
$chk->bind_param("isi", $lesson_id, $title, $exercise_id);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    $_SESSION['msg'] = "Exercise with same name already exists.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: edit_exercise.php?exercise_id=$exercise_id");
    exit;
}
$chk->close();
/* =========================
   UPDATE EXERCISE
========================= */
$stmt = $conn->prepare("
    UPDATE exercises
    SET exercise_name = ?, editor_id = ?
    WHERE exercise_id = ?
");
$stmt->bind_param("sii", $title, $editor_id, $exercise_id);
$stmt->execute();
$stmt->close();

/* =========================
   HANDLE INSTRUCTION IMAGE
========================= */
$instruction_image = uploadFile($_FILES['instruction_image'] ?? []);
$hint_image = uploadFile($_FILES['hint_image'] ?? []);



/* =========================
   DELETE OLD INSTRUCTIONS
========================= */
$del = $conn->prepare("DELETE FROM exercise_instruction_hints WHERE exercise_id = ?");
$del->bind_param("i", $exercise_id);
$del->execute();
$del->close();

/* =========================
   INSERT NEW DATA
========================= */
$stmt = $conn->prepare("
    INSERT INTO exercise_instruction_hints
    (exercise_id, instruction_text, instruction_image,
     hint_text, hint_image, hint_video)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssss",
    $exercise_id,
    $instruction_text,
    $instruction_image,
    $hint_text,
    $hint_image,
    $hint_video
);

$stmt->execute();
$stmt->close();

$_SESSION['msg'] = "Exercise updated successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_exercise.php?lesson_id=$lesson_id");
exit;




