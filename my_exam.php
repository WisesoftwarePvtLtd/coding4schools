<?php
session_start();
include 'header.php';
include 'config.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;

if (!$user_id) {
    die("User not logged in");
}

/* 1️⃣ Get all completed quiz attempts of user */
$attemptsQ = $conn->query("
    SELECT qa.quiz_attempt_id, qa.quiz_id, qa.completed_at, q.quiz_title
    FROM quiz_attempt qa
    JOIN quiz q ON q.quiz_id = qa.quiz_id
    WHERE qa.user_id = $user_id
    AND qa.status = 'completed'
    ORDER BY qa.completed_at DESC
");

if ($attemptsQ->num_rows == 0) {
    die("No quiz attempts found");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Quiz Results</title>
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
                        <h3 class="fw-bolder">My Quiz Results</h3>

                        <!-- <a href="lesson.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary ml-5"> <i
                                class="fas fa-arrow-left"></i> Back To Lesson </a> -->
                    </div>



                    <?php
                    /* 2️⃣ Loop all attempts */
                    while ($attempt = $attemptsQ->fetch_assoc()) {

                        $attempt_id = $attempt['quiz_attempt_id'];

                        $answersQ = $conn->query("
        SELECT qa.question_id, qa.quiz_attempt_answer, q.question_type, q.correct_answer
        FROM quiz_answer qa
        JOIN questions q ON q.question_id = qa.question_id
        WHERE qa.quiz_attempt_id = $attempt_id
    ");

                        $total = $correct = $wrong = 0;

                        while ($row = $answersQ->fetch_assoc()) {

                            $total++;
                            $isCorrect = false;

                            switch ($row['question_type']) {

                                case 'image-select':
                                case 'click-select-image':

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
                                    break;

                                case 'image-drag-drop-to-name':

                                    $dragId = intval($row['quiz_attempt_answer']);
                                    $dragQ = $conn->query("
            SELECT is_correct
            FROM drag_options
            WHERE id=$dragId AND question_id={$row['question_id']}
        ");
                                    if ($dragQ && $dragQ->num_rows && $dragQ->fetch_assoc()['is_correct']) {
                                        $isCorrect = true;
                                    }
                                    break;

                                case 'order':

                                    if (
                                        trim($row['quiz_attempt_answer']) !== '' &&
                                        trim($row['quiz_attempt_answer']) === trim($row['correct_answer'])
                                    ) {
                                        $isCorrect = true;
                                    }

                                    break;

case 'drag-match-text-to-image':

    $isCorrect = true;

    // Raw answer from DB
    $rawAnswer = trim($row['quiz_attempt_answer']);

    // Fix invalid JSON (if {} missing)
    if ($rawAnswer !== '' && $rawAnswer[0] !== '{') {
        $rawAnswer = '{' . $rawAnswer . '}';
    }

    // Decode JSON
    $pairs = json_decode($rawAnswer, true);

    if (!is_array($pairs) || empty($pairs)) {
        $isCorrect = false;
        break;
    }

    // Load correct words for this question
    $items = [];
    $res = $conn->query("
        SELECT id, correct_word
        FROM match_items
        WHERE question_id = {$row['question_id']}
    ");

    while ($r = $res->fetch_assoc()) {
        $items[(int)$r['id']] = trim(html_entity_decode($r['correct_word']));
    }

    // Compare each pair
    foreach ($pairs as $leftId => $userRightId) {

        $leftId = (int)$leftId;
        $userRightId = (int)$userRightId;

        // Safety check
        if (!isset($items[$leftId], $items[$userRightId])) {
            $isCorrect = false;
            break;
        }

        // Compare correct_word
        if ($items[$leftId] !== $items[$userRightId]) {
            $isCorrect = false;
            break;
        }
    }

    break;







                                case 'match-line':
                                    if (!empty($row['quiz_attempt_answer'])) {
                                        $isCorrect = true;
                                    }
                                    break;
                            }

                            $isCorrect ? $correct++ : $wrong++;
                        }

                        $percentage = $total ? round(($correct / $total) * 100) : 0;
                        ?>

                        <!-- RESULT CARD -->
                        <div class="result-box">

                            <div class="result-header">
                                <h3><?= htmlspecialchars($attempt['quiz_title']) ?></h3>
                                <small>Attempted on <?= date('d M Y H:i', strtotime($attempt['completed_at'])) ?></small>
                            </div>

                            <div class="result-row">
                                <div>Total Questions</div>
                                <div><?= $total ?></div>
                            </div>

                            <div class="result-row">
                                <div>Correct</div>
                                <div style="color:green"><?= $correct ?></div>
                            </div>

                            <div class="result-row">
                                <div>Wrong</div>
                                <div style="color:red"><?= $wrong ?></div>
                            </div>

                            <div class="result-row">
                                <div>Marks</div>
                                <div><?= $correct ?></div>
                            </div>

                            <div class="result-row">
                                <div>Percentage</div>
                                <div><?= $percentage ?>%</div>
                            </div>

                            <div class="result-row">
                                <div>Result</div>
                                <div>
                                    <?= $percentage >= 40 ? '<span class="pass">PASS</span>' : '<span class="fail">FAIL</span>' ?>
                                </div>
                            </div>

                        </div>

                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
</body>

</html>