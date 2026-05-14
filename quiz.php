<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$user_type = $_SESSION['LoggedInUserType'] ?? 0;


$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$offset = ($page - 1) * $limit;

if (!$course_id || !$lesson_id) {
    die("Invalid course or Lesson");
}

/* Fetch course Name */
$courseName = "";
$b = $conn->query("SELECT course_title FROM courses WHERE course_id = $course_id LIMIT 1");
if ($b && $b->num_rows > 0) {
    $courseName = $b->fetch_assoc()['course_title'];
}

/* Fetch Lesson Name */
$lessonName = "";
$l = $conn->query("SELECT lesson_title FROM lessons WHERE lesson_id = $lesson_id LIMIT 1");
if ($l && $l->num_rows > 0) {
    $lessonName = $l->fetch_assoc()['lesson_title'];
}

$countSql = "
SELECT COUNT(DISTINCT q.quiz_id) AS total
FROM quiz q
LEFT JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
";

if ($user_type == STUDENT) {
    $countSql .= "
    JOIN unlock_quiz ue ON ue.quiz_id = q.quiz_id
    WHERE q.course_id = $course_id
      AND q.lesson_id = $lesson_id
      AND ue.is_unlocked = 1
    ";
} else {
    $countSql .= "
    WHERE q.course_id = $course_id
      AND q.lesson_id = $lesson_id
    ";
}

$totalResult = $conn->query($countSql);
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

/* quizs / quizzes fetch */
if ($user_type == STUDENT) {

    $sql = "
    SELECT q.quiz_id, q.quiz_title,q.status,
           COUNT(qq.quiz_question_id) AS total_questions
    FROM quiz q
    JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
     JOIN unlock_quiz ue 
                ON ue.quiz_id = q.quiz_id
    WHERE q.course_id = $course_id
    AND q.lesson_id = $lesson_id
    AND ue.is_unlocked = 1
    GROUP BY q.quiz_id
    ORDER BY q.quiz_id DESC
    LIMIT $limit OFFSET $offset
";


} else {

    $sql = "
    SELECT q.quiz_id, q.quiz_title,q.status,
           COUNT(qq.quiz_question_id) AS total_questions
    FROM quiz q
    LEFT JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
    WHERE q.course_id = $course_id
    AND q.lesson_id = $lesson_id
    GROUP BY q.quiz_id
    ORDER BY q.quiz_id DESC
    LIMIT $limit OFFSET $offset
";
}


$quizresult = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Quiz</title>
</head>

<body>
<div class="container-fluid" style="padding:30px;">
    <div class="layout-row">

        <!-- Sidebar -->
        <div class="sidebar">
            <?php include 'menus.php'; ?>
        </div>

        <!-- Main Area -->
        <div class="main-area text-dark">
            <div class="container-fluid p-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bolder">Course Quiz</h3>

                    <a  href="lesson.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary ml-5"> <i
                class="fas fa-arrow-left"></i> Back To Lesson </a>
                </div>

                <?php if ($resultquiz->num_rows > 0): ?>
                    <?php while ($row = $resultquiz->fetch_assoc()): ?>

                        <div class="grade-box mb-3">
                            <div class="grade-row d-flex justify-content-between align-items-center">

                                <!-- Exam Info -->
                                <div>
                                    <strong class="text-primary">
                                        <i class="fas fa-file-alt"></i>
                                    </strong>
                                    <strong><?= htmlspecialchars($row['quiz_title']) ?></strong>
                                </div>

                                <!-- Actions -->
                                <div class="action-icons">
                                    <!-- Attempt Exam -->
                                    <a href="quiz_attempt.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>"
                                       class="btn btn-primary" onclick="attemptQuiz(<?= $row['quiz_id'] ?>)"> Attempt
                                    </a>
                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No exams found for this course.</p>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>
<script>
function attemptQuiz(quizId) {
    setTimeout(function () {
        window.location.href =
            "quiz_attempt.php?quizid=" + quizId + "&currentQNo=1";
    }, 300);
}
</script>
</body>
</html>
