<?php
include 'config.php';

header("Content-Type: application/json; charset=UTF-8");

$quiz_id = intval($_GET['quiz_id'] ?? 0);
if (!$quiz_id) {
    echo json_encode([]);
    exit;
}

/* ---------------------------------
 STEP 1: GET QUESTION IDS (ORDER SAFE)
--------------------------------- */
$qidResult = $conn->query("
    SELECT question_id 
    FROM quiz_questions 
    WHERE quiz_id = $quiz_id
    ORDER BY RAND()
");

$questionIds = [];
while ($row = $qidResult->fetch_assoc()) {
    $questionIds[] = (int)$row['question_id'];
}

if (!$questionIds) {
    echo json_encode([]);
    exit;
}

$ids = implode(',', $questionIds);

/* ---------------------------------
 STEP 2: LOAD QUESTIONS
--------------------------------- */
$qResult = $conn->query("
    SELECT * 
    FROM questions 
    WHERE question_id IN ($ids)
");

$questions = [];

while ($q = $qResult->fetch_assoc()) {

    $qid = (int)$q['question_id'];
    $type = $q['question_type'];
    $correct_answer = $q['correct_answer'];
    $options = [];



            $opt = $conn->query("
                SELECT id,option_key, label, image, is_correct
                FROM options
                WHERE question_id = $qid
            ");

            while ($row = $opt->fetch_assoc()) {
                if ($row['is_correct']) {
                    $correct_answer = $row['option_key'];
                }

                $options[] = [
                    "option_id" => $row['id'],
                    "id" => $row['option_key'],
                    "label" => $row['label'],
                    "src" => $row['image'],
                    "is_correct" => (bool)$row['is_correct']
                ];
            }
           
    

    $questions[] = [
        "id" => $qid,
        "type" => $type,
        "question" => $q['question_text'],
        "main_image" => $q['main_image'],
        "correct_answer" => $correct_answer,
        "options" => $options
    ];

}


echo json_encode($questions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
