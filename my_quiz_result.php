<?php
session_start();
include 'header.php';
include "config.php";

$attempt_id = intval($_GET['attempt_id'] ?? 0);
if (!$attempt_id) {
    die("Invalid Attempt");
}

$sql = "
SELECT 
    qa.quiz_attempt_id,
    qa.percentage,
    qa.started_at,
    qa.completed_at,

    s.student_name,
    s.family_name,

    q.quiz_title,

    b.course_title,
    l.lesson_title,g.grade_name, 
        CONCAT(sec.section_name, ' - ', sec.gender) AS section_name,
        s.student_number

FROM quiz_attempt qa

JOIN students s 
    ON s.user_id = qa.user_id
JOIN section_students ss ON ss.student_id = s.student_id
    JOIN grades g ON g.grade_id = ss.grade_id
    JOIN sections sec ON sec.section_id = ss.section_id

JOIN quiz q 
    ON q.quiz_id = qa.quiz_id

LEFT JOIN courses b 
    ON b.course_id = q.course_id

LEFT JOIN lessons l 
    ON l.lesson_id = q.lesson_id

WHERE qa.quiz_attempt_id = ?
LIMIT 1
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $attempt_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Result not found");
}

$percentage = round((float) $data['percentage'], 2);
$resultText = ($percentage >= 50) ? "PASS" : "FAIL";
$resultClass = ($percentage >= 50) ? "text-success" : "text-danger";
?>
<style>
    .result-box {
        max-width: 700px;
        margin: 30px auto;
        border: 2px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        background: #fff
    }

    .result-header {
        text-align: center;
        border-bottom: 2px solid #ddd;
        margin-bottom: 15px
    }

    .result-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #ccc
    }

    .pass {
        color: green;
        font-weight: bold
    }

    .fail {
        color: red;
        font-weight: bold
    }
</style>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">
                
                    <div class="text-right">
                    <a href="my_quiz.php" class="btn btn-primary ">Back To My Quiz</a>
                    </div>
                    <div class="result-box">

                        <div class="result-header">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h3 class="fw-bolder">Quiz Result Sheet</h3>

                                
                            </div>
                        </div>

                        <div class="result-row">
                            <div>
                                <strong>Student Name:</strong>
                            </div>
                            <div>
                                <?= htmlspecialchars($data['student_name'] . ' ' . $data['family_name']) ?>
                            </div>
                        </div>
                        <div class="result-row">
                            <div>
                                <strong>Quiz Name:</strong>
                            </div>
                            <div>
                                <?= htmlspecialchars($data['quiz_title']) ?>
                            </div>
                        </div>

                        <div class="result-row">
                            <div><strong>Grade / Section</strong></div>
                            <div>
                                <?= $data['grade_name'] ?> -
                                <?= $data['section_name'] ?>
                            </div>
                        </div>

                        <div class="result-row">
                            <div>
                                <strong>course / lesson</strong>
                            </div>
                            <div>
                                <?= htmlspecialchars($data['course_title'] ?? '-') ?> /
                                <?= htmlspecialchars($data['lesson_title'] ?? '-') ?>
                            </div>
                        </div>

                        <div class="result-row">
                            <div><strong>Student Number</strong></div>
                            <div>
                                <?= $data['student_number'] ?>
                            </div>
                        </div>



                        <div class="result-row">
                            <div>
                                <strong>Start Time:</strong>

                            </div>
                            <div>
                                <?= date("d M Y h:i A", strtotime($data['started_at'])) ?>
                            </div>
                        </div>

                        <div class="result-row">
                            <div>
                                <strong>End Time:</strong>
                            </div>
                            <div>
                                <?= $data['completed_at']
                                    ? date("d M Y h:i A", strtotime($data['completed_at']))
                                    : '-' ?>
                            </div>
                        </div>

                        <div class="result-row">
                            <div>
                                <strong>Percentage:</strong>
                            </div>
                            <div>
                                <span class="fw-bolder <?= ($percentage >= 50 ? 'text-success' : 'text-danger') ?>">
                                    <?= $percentage ?>
                                </span>
                            </div>
                        </div>

                        <div class="result-row">
                            <div>
                                <strong>Result:</strong>
                            </div>
                            <div>
                                <span class="<?= $resultClass ?> fw-bolder">
                                    <?= $resultText ?>
                                </span>
                            </div>
                        </div>

                    </div>

                
            </div>

        </div>
    </div>
</body>

</html>