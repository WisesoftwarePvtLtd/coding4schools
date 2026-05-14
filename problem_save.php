<?php
session_start();
include 'config.php';
include 'standard_constants.php';

$problem_id = intval($_POST['problem_id'] ?? 0);
$lesson_id = intval($_POST['lesson_id'] ?? 0);
$problem_name = trim($_POST['problem_name'] ?? '');
$problem_text = trim($_POST['problem_text'] ?? '');
$editor_url = trim($_POST['problem_editor'] ?? '');

if ($lesson_id <= 0 || $problem_name === '') {
    $_SESSION['msg'] = "Invalid or missing data.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_problem.php?lesson_id=$lesson_id");
    exit;
}

# DUPLICATE CHECK
$chk = $conn->prepare("
SELECT problem_id
FROM problem
WHERE lesson_id=? AND problem_name=? AND problem_id!=?
LIMIT 1
");
$chk->bind_param("isi", $lesson_id, $problem_name, $problem_id);
$chk->execute();
$chk->store_result();

if ($chk->num_rows > 0) {
    $_SESSION['msg'] = "Problem with same name already exists.";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_problem.php?lesson_id=$lesson_id");
    exit;
}
$chk->close();

# IMAGE UPLOAD
$problem_image = null;

if (!empty($_FILES['problem_image']['name'])) {

    $ext = pathinfo($_FILES['problem_image']['name'], PATHINFO_EXTENSION);
    $problem_image = time() . '_' . uniqid() . '.' . $ext;

    move_uploaded_file(
        $_FILES['problem_image']['tmp_name'],
        "uploads/" . $problem_image
    );
}

# UPDATE
if ($problem_id > 0) {

    if ($problem_image) {

        $stmt = $conn->prepare("
        UPDATE problem
        SET problem_name=?, problem_text=?, editor_url=?, problem_image=?
        WHERE problem_id=?
        ");

        $stmt->bind_param(
            "ssssi",
            $problem_name,
            $problem_text,
            $editor_url,
            $problem_image,
            $problem_id
        );

    } else {

        $stmt = $conn->prepare("
        UPDATE problem
        SET problem_name=?, problem_text=?, editor_url=?
        WHERE problem_id=?
        ");

        $stmt->bind_param(
            "sssi",
            $problem_name,
            $problem_text,
            $editor_url,
            $problem_id
        );
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Problem updated successfully!";

} else {

    # INSERT

    $stmt = $conn->prepare("
INSERT INTO problem
(lesson_id, problem_name, problem_text, editor_url, problem_image)
VALUES (?,?,?,?,?)
");

    $stmt->bind_param(
        "issss",
        $lesson_id,
        $problem_name,
        $problem_text,
        $editor_url,
        $problem_image
    );

    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Problem added successfully!";
}

$_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_problem.php?lesson_id=$lesson_id");
exit;
?>