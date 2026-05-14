<?php
include 'config.php';
header('Content-Type: application/json; charset=utf-8');

$question    = trim($_POST['question_text'] ?? '');
$question_id = (int)($_POST['question_id'] ?? 0);

$response = ['duplicate' => false];

if ($question !== '') {

    if ($question_id > 0) {
        // EDIT MODE
        $sql = "
          SELECT question_id
          FROM questions
          WHERE question_text = ?
          AND question_id != ?
          LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $question, $question_id);
    } else {
        // ADD MODE
        $sql = "
          SELECT question_id
          FROM questions
          WHERE question_text = ?
          LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $question);
    }

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['duplicate'] = true;
    }
}

echo json_encode($response);
exit; // 🔥 VERY IMPORTANT
