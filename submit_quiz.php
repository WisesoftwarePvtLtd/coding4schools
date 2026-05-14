<?php

include "config.php";

$attempt_id = intval($_POST['attempt_id'] ?? 0);
$quiz_id = intval($_POST['quiz_id'] ?? 0);
$completed_at = $_POST['completed_at'] ?? date('Y-m-d H:i:s');


if (!$attempt_id || !$quiz_id) {
    echo json_encode(["status" => "error", "msg" => "Invalid Attempt or Quiz"]);
    exit;
}

/* ===============================
   1️⃣ TOTAL QUESTIONS
================================ */
$totalRow = $conn->query("
    SELECT COUNT(*) AS total
    FROM quiz_questions
    WHERE quiz_id = $quiz_id
");

if (!$totalRow) {
    echo json_encode([
        "status" => "error",
        "msg" => "Failed to fetch total questions",
        "quiz_id" => $quiz_id,
        "attempt_id" => $attempt_id
    ]);
    exit;
}

$total = (int) $totalRow->fetch_assoc()['total'];
$correct = 0;

/* ===============================
   2️⃣ CHECK ANSWERS
================================ */
$answersQ = $conn->query("
    SELECT 
        qa.question_id,
        qa.quiz_attempt_answer,
        qa.question_type,
        q.correct_answer
    FROM quiz_answer qa
    JOIN questions q ON q.question_id = qa.question_id
    WHERE qa.quiz_attempt_id = $attempt_id
");

if ($answersQ) {
    while ($row = $answersQ->fetch_assoc()) {
        $isCorrect = false;

     

                $selected_option_id = intval($row['quiz_attempt_answer']);

                $optQ = $conn->query("
                SELECT is_correct
                FROM options
                WHERE id = $selected_option_id
                AND question_id = {$row['question_id']}
                LIMIT 1
            ");

                if ($optQ && $optQ->num_rows > 0) {
                    $opt = $optQ->fetch_assoc();
                    if ($opt['is_correct'] == 1) {
                        $isCorrect = true;
                    }
                }
               if ($isCorrect)
            $correct++;
        }

       

        
        // $isCorrect ? $correct++ : $wrong++;
    }


/* ===============================
   3️⃣ MARKS & %
================================ */
$marks = $correct;
$percentage = $total ? round(($correct / $total) * 100, 2) : 0;
// $percentStr = $percentage . '%';
/* ===============================
   4️⃣ UPDATE ATTEMPT
================================ */
$stmt = $conn->prepare("
    UPDATE quiz_attempt
    SET total_marks = ?, student_marks = ?, percentage = ?, status = 'completed', completed_at = ?
    WHERE quiz_attempt_id = ?
");
$stmt->bind_param("iidsi", $total, $marks, $percentage, $completed_at, $attempt_id);
$stmt->execute();

/* ===============================
   5️⃣ RESPONSE
================================ */
echo json_encode([
    "status" => "completed",
    "total" => $total,
    "correct" => $correct,
    "marks" => $marks,
    "percentage" => $percentage
]);





