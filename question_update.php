<?php
session_start();
include "standard_constants.php";
include "config.php";
// echo "<pre>";
// print_r($_POST);
// echo "<pre>";
// print_r($_FILES);
// die;
// /* ================= BASIC DATA ================= */
$question_id = intval($_POST['question_id'] ?? 0);
$course_id = intval($_POST['course_id'] ?? 0);
$lesson_id = intval($_POST['lesson_id'] ?? 0);
$school_id = $_POST['school_id'] ?? 0;


$question_text = trim($_POST['question_text'] ?? '');

if (!$question_id || !$course_id || !$lesson_id || !$question_text) {
    $_SESSION['msg'] = "Invalid data!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_question_bank.php");
    exit;
}

/* ================= FILE UPLOAD HELPER ================= */
function uploadFile($file, $folder = "uploads/")
{
    if (!empty($file['name'])) {
        if (!is_dir($folder))
            mkdir($folder, 0777, true);
        $name = time() . "_" . basename($file['name']);
        $path = $folder . $name;
        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $path;
        }
    }
    return null;
}



/* ================= MAIN IMAGE UPDATE (SAFE) ================= */
$main_image_sql = "";
$params = [$course_id, $lesson_id, $school_id, $question_text];
$types = "iiis";

if (!empty($_FILES['mainImage']['name'])) {
    $main_image = uploadFile($_FILES['mainImage']);
    if ($main_image) {
        $main_image_sql = ", main_image=?";
        $params[] = $main_image;
        $types .= "s";
    }
}

$params[] = $question_id;
$types .= "i";

$stmt = $conn->prepare("
    UPDATE questions 
    SET course_id=?, lesson_id=?, school_id=?, question_text=? $main_image_sql
    WHERE question_id=?
");
$stmt->bind_param($types, ...$params);
$stmt->execute();

/* ======================================================
  IMAGE-SELECT / CLICK-SELECT-IMAGE
====================================================== */


foreach ($_POST['options'] as $i => $opt) {

    $option_key = trim($opt['id'] ?? '');
    $label = trim($opt['label'] ?? '');
    $is_correct = (($_POST['correct_option'] ?? '') == $option_key) ? 1 : 0;

    /* ===============================
       FETCH OLD IMAGE IF EXISTS
    ================================ */
    $image = null;


    if (!empty($option_key)) {

        // option_key actually contains ID (int)
        $stmtOld = $conn->prepare(" SELECT image  FROM options WHERE id = ?");
        $stmtOld->bind_param("i", $option_key);
        $stmtOld->execute();

        $old = $stmtOld->get_result()->fetch_assoc();

        // DEBUG
        // var_dump($old);

        if ($old && !empty($old['image'])) {
            $image = $old['image']; // ✅ preserve old image
        }
    }



    /* ===============================
       IMAGE UPLOAD
    ================================ */
    if (!empty($_FILES['options']['name'][$i]['image'])) {
        $image = uploadFile([
            'name' => $_FILES['options']['name'][$i]['image'],
            'tmp_name' => $_FILES['options']['tmp_name'][$i]['image']
        ]);
    }

    /* ===============================
       UPDATE ONLY (NO DUPLICATE)
    ================================ */
    if ($option_key !== '') {

        // ✅ UPDATE EXISTING OPTION (IMAGE INCLUDED)
        $stmt = $conn->prepare("
                UPDATE options 
                SET label = ?, image = ?, is_correct = ?
                WHERE id = ? AND question_id = ?
            ");
        $stmt->bind_param(
            "ssiii",
            $label,
            $image,
            $is_correct,
            $option_key,
            $question_id
        );
        $stmt->execute();


    } else {

        $is_correct = (($_POST['correct_option'] ?? '') == "0") ? 1 : 0;


        $stmt = $conn->prepare("
                INSERT INTO options (question_id, label, image, is_correct)
                VALUES (?, ?, ?, ?)
            ");
        $stmt->bind_param(
            "issi",
            $question_id,
            $label,
            $image,
            $is_correct
        );
        $stmt->execute();

    }


}





// ======================================================
// ✅ Success
// ======================================================
$_SESSION['msg'] = "Quetion Updated successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

header("Location: manage_question_bank.php");
exit;
