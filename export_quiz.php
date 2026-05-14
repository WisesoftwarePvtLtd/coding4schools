<?php
session_start();
include 'config.php';

$quizId = (int) $_GET['quiz_id'];

/* ===============================
   1️⃣ GET QUIZ TITLE
================================ */
$quizTitle = 'quiz_result';

$stmt = $conn->prepare("SELECT quiz_title FROM quiz WHERE quiz_id = ?");
$stmt->bind_param("i", $quizId);
$stmt->execute();
$stmt->bind_result($quizTitleDb);

if ($stmt->fetch()) {
    $quizTitle = preg_replace('/[^a-zA-Z0-9_]/', '_', $quizTitleDb);
}
$stmt->close();

/* ===============================
   2️⃣ GET QUIZ RESULT WITH GRADE & SECTION
================================ */

$sql = "
    SELECT s.student_number, 
    CONCAT(s.student_name, ' ', s.family_name) AS student_name, 
    g.grade_name, CONCAT(sec.section_name, ' - ', sec.gender ) AS section_name, qa.student_marks, 
    qa.total_marks, 
    qa.percentage, 
    qa.completed_at, 
    q.quiz_title
    FROM quiz_attempt qa 
    JOIN students s ON s.user_id = qa.user_id 
    JOIN section_students ss ON ss.student_id = s.student_id 
    JOIN grades g ON g.grade_id = ss.grade_id 
    JOIN sections sec ON sec.section_id = ss.section_id 
    JOIN quiz q ON q.quiz_id = qa.quiz_id 

    WHERE qa.quiz_id = ? 
    AND qa.status = 'completed' 
    ORDER BY qa.completed_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quizId);
$stmt->execute();
$result = $stmt->get_result();

/* ===============================
   3️⃣ CSV DOWNLOAD HEADERS
================================ */
$filename = $quizTitle . '_result_' . date('Ymd_His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

/* ===============================
   4️⃣ CSV HEADER ROW
================================ */
fputcsv($output, [
    'Student Number',
    'Student Name',
    'Grade',
    'Section',
    'Quiz Name',
    'Marks Obtained',
    'Total Marks',
    'Percentage',
    'Completed At'
]);

/* ===============================
   5️⃣ CSV DATA ROWS
================================ */
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['student_number'],
        $row['student_name'],
        $row['grade_name'] ?? '-',
        $row['section_name'] ?? '-',
        $row['quiz_title'],
        $row['student_marks'],
        $row['total_marks'],
        $row['percentage'],
        $row['completed_at']
    ]);
}

fclose($output);
$stmt->close();
exit;
