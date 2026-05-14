<?php
include 'config.php';

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

// STEP 1: Fetch question IDs
$practiceQue = $conn->query("SELECT * FROM lesson_practices WHERE lesson_id = $lesson_id ORDER BY RAND()");
$questionIds = [];

while ($row = $practiceQue->fetch_assoc()) {
    $questionIds[] = $row['question_id'];
}

$questions = [];

// No questions?
if (count($questionIds) == 0) {
    echo json_encode([]);
    exit;
}

$ids = implode(",", $questionIds);

// STEP 2: Load questions
$qResult = $conn->query("SELECT * FROM questions WHERE question_id IN ($ids) ORDER BY question_id ASC");

// STEP 3: Build final questions JSON
while ($q = $qResult->fetch_assoc()) {

    $qid = $q['question_id'];
    $type = $q['question_type'];
    $correct_answer = $q['correct_answer'];
    $options = [];

            // $optResult = $conn->query("SELECT option_key AS id, label, image, is_correct FROM options WHERE question_id = $qid");
            // while ($row = $optResult->fetch_assoc()) {
            //     if ($row['is_correct'] == 1) {
            //         $correct_answer = $row['id'];
            //     }
            //     $options[] = [
            //         "id" => $row['id'],
            //         "label" => $row['label'],
            //         "src" => $row['image'],
            //         "is_correct" => (bool)$row['is_correct']
            //     ];
            // }
             $optResult = $conn->query("SELECT id ,option_key, label, image, is_correct FROM options WHERE question_id = $qid");
            while ($row = $optResult->fetch_assoc()) {
                if ($row['is_correct'] == 1) {
                    $correct_answer = $row['id'];
                }
                $options[] = [
                    "option_id" => $row['id'],
                    "id" => $row['option_key'],
                    "label" => $row['label'],
                    "src" => $row['image'],
                    "is_correct" => (bool) $row['is_correct']
                ];
            }

    $questions[] = [
        "id" => $qid,
        "type" => $type,
        "question" => $q['question_text'],
        "correct_answer" => $correct_answer,
        "main_image" => $q['main_image'],
        "options" => $options
    ];
}

header("Content-Type: application/json");
echo json_encode($questions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
