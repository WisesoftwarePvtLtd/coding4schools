<?php
session_start();
include 'config.php';
include 'standard_constants.php';
/* =========================
   FETCH & VALIDATE INPUT
========================= */
$id               = intval($_POST['instruction_id'] ?? 0);
$exercise_id      = intval($_POST['exercise_id'] ?? 0);
$instruction_text = trim($_POST['instruction_text'] ?? '');
$hint_text        = trim($_POST['hint_text'] ?? '');
$hint_video       = trim($_POST['hint_video'] ?? '');

if ($id <= 0 || $exercise_id <= 0 || $instruction_text === '') {
     $_SESSION['msg'] = "Invalid or missing data.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_instruction.php?exercise_id=$exercise_id");
    exit;
}

/* =========================
   HANDLE INSTRUCTION IMAGE
========================= */
$instruction_image_sql = "";
$params = [];
$types  = "";

/* Instruction Image */
if (
    isset($_FILES['instruction_image']) &&
    $_FILES['instruction_image']['error'] === UPLOAD_ERR_OK
) {
    $ext = pathinfo($_FILES['instruction_image']['name'], PATHINFO_EXTENSION);
    $instruction_image = time() . "_inst_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $_FILES['instruction_image']['tmp_name'],
        __DIR__ . "/uploads/" . $instruction_image
    );

    $instruction_image_sql = ", instruction_image = ?";
    $params[] = $instruction_image;
    $types   .= "s";
}

/* =========================
   HANDLE HINT IMAGE
========================= */
if (
    isset($_FILES['hint_image']) &&
    $_FILES['hint_image']['error'] === UPLOAD_ERR_OK
) {

    $ext = pathinfo($_FILES['hint_image']['name'], PATHINFO_EXTENSION);
    $hint_image = time() . "_hint_" . uniqid() . "." . $ext;

    move_uploaded_file(
        $_FILES['hint_image']['tmp_name'],
        __DIR__ . "/uploads/" . $hint_image
    );

    $hint_image_sql = ", hint_image = ?";
    $params[] = $hint_image;
    $types   .= "s";
}
// DUPLICATE CHECK (exclude current instruction)
$chk = $conn->prepare("
    SELECT id
    FROM exercise_instruction_hints
    WHERE exercise_id = ?
      AND instruction_text = ?
      AND id != ?
      LIMIT 1
");
$chk->bind_param("isi", $exercise_id, $instruction_text, $id);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    $_SESSION['msg'] = "Instruction with same name already exists.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_instruction.php?exercise_id=$exercise_id");
    exit;
}
$chk->close();




/* =========================
   BUILD UPDATE QUERY
========================= */
$sql = "
    UPDATE exercise_instruction_hints
    SET instruction_text = ?,
        hint_text = ?,
        hint_video = ?
        $instruction_image_sql
        $hint_image_sql
    WHERE id = ?
";

$params = array_merge(
    [$instruction_text, $hint_text, $hint_video],
    $params,
    [$id]
);

$types = "sss" . $types . "i";

/* =========================
   EXECUTE UPDATE
========================= */
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->close();
$_SESSION['msg'] = "Instruction updated successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_instruction.php?exercise_id=$exercise_id");
exit;

