<?php
session_start();
include "standard_constants.php";
include "config.php";
// print_r($_POST);die;

$course_id = trim($_POST['course_id']);
$lesson_id = trim($_POST['lesson_id']);
$user_id = trim($_POST['user_id']);
$school_id = $_POST['school_id'] ?? 0;
// Helper: Upload File
function uploadFile($file, $folder = "uploads/")
{
  if (!empty($file['name'])) {
    $filename = time() . "_" . basename($file['name']);
    $targetPath = $folder . $filename;
    if (!is_dir($folder))
      mkdir($folder, 0777, true);
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
      return $targetPath;
    }
  }
  return null;
}

// 🟢 Get main question info
$type = $_POST['type'] ?? '';
$question_text = $_POST['question_text'] ?? '';
$correct_answer = '';

if ($type === 'order') {
  // Order question ke liye
  if (!empty($_POST['ordercorrectanswer'])) {
    $correct_answer = trim($_POST['ordercorrectanswer']);
  }
} else {
  // Baaki sab question types ke liye
  if (!empty($_POST['correct_answer'])) {
    $correct_answer = trim($_POST['correct_answer']);
  }
}
$main_image = null;

// 🟢 Validate
if (empty($question_text)) {

  $_SESSION['msg'] = "Please fill in required fields.";
  $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
  header("Location: manage_question_bank.php");
  exit;
}

// 🟢 Handle main image (for image-drag-drop)
if (!empty($_FILES['mainImage']['name'])) {
  $main_image = uploadFile($_FILES['mainImage']);
}

// 🟢 Insert main question
$stmt = $conn->prepare("INSERT INTO questions (course_id, lesson_id,user_id, school_id, question_text, correct_answer, main_image) VALUES (?, ?, ?,?,?,?,?)");
$stmt->bind_param("iiiisss", $course_id, $lesson_id,$user_id,$school_id, $question_text, $correct_answer, $main_image);
$stmt->execute();

$qid = $stmt->insert_id;

// ======================================================
// 1️⃣ IMAGE-SELECT or CLICK-SELECT-IMAGE
// ======================================================

  if (!empty($_POST['options'])) {
    foreach ($_POST['options'] as $i => $opt) {
      $option_key = $opt['id'] ?? '';
      $label = $opt['label'] ?? '';
      $is_correct = (isset($_POST['correct_option']) && $_POST['correct_option'] == $i) ? 1 : 0;

      $img = '';
      if (!empty($_FILES['options']['name'][$i]['image'])) {
        $img = uploadFile([
          'name' => $_FILES['options']['name'][$i]['image'],
          'tmp_name' => $_FILES['options']['tmp_name'][$i]['image']
        ]);
      }

      $query = $conn->prepare("INSERT INTO options (question_id, option_key, label, image, is_correct) VALUES (?, ?, ?, ?, ?)");
      $query->bind_param("isssi", $qid, $option_key, $label, $img, $is_correct);
      $query->execute();
    }
  }







// ======================================================
// ✅ Success
// ======================================================
$_SESSION['msg'] = "Quetion Add successfully!";
 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;


header("Location: manage_question_bank.php");
exit;

?>