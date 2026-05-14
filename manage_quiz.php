<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$user_type = $_SESSION['LoggedInUserType'] ?? 0;
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? 0;


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
      AND (q.school_id = 0 || q.school_id = $userSchoolId)
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
    AND ue.school_id = $userSchoolId

    AND ue.is_unlocked = 1
    AND (q.school_id = 0 || q.school_id = $userSchoolId)
    GROUP BY q.quiz_id
    ORDER BY q.quiz_id DESC
    LIMIT $limit OFFSET $offset
";


} 
else  {

    $sql = "
    SELECT q.quiz_id, q.quiz_title,q.status,
           COUNT(qq.quiz_question_id) AS total_questions
    FROM quiz q
    LEFT JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
        JOIN users u ON u.user_id = $user_id
                    
    WHERE q.course_id = $course_id
    AND q.lesson_id = $lesson_id
     AND (
        q.user_id = $user_id
        OR (
            q.status = '" . DISPATCH . "'
            AND (q.school_id = 0 OR q.school_id = $userSchoolId)
        )
      )
    
    GROUP BY q.quiz_id
    ORDER BY q.quiz_id DESC
    LIMIT $limit OFFSET $offset
";
}

// else {

//     $sql = "
//     SELECT q.quiz_id, q.quiz_title,q.status,
//            COUNT(qq.quiz_question_id) AS total_questions
//     FROM quiz q
//     LEFT JOIN quiz_questions qq ON q.quiz_id = qq.quiz_id
      
//     WHERE q.course_id = $course_id
//     AND q.lesson_id = $lesson_id
       
//     AND (q.school_id = 0 || q.school_id = $userSchoolId)
//     GROUP BY q.quiz_id
//     ORDER BY q.quiz_id DESC
//     LIMIT $limit OFFSET $offset
// ";
// }


$quizresult = $conn->query($sql);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>course quizs</title>
</head>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">

                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
                

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder"><?= htmlspecialchars($courseName) ?> -
                            <?= htmlspecialchars($lessonName) ?>
                            - Manage Quiz</h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage-lesson.php?course_id=<?php echo $course_id; ?>&course_name=<?= htmlspecialchars($courseName) ?>"
                                class="btn btn-primary ">Back To Manage Lesson </a>
                            <?php if (userHasPermission(QUIZ_ADD)) { ?>
                                <a href="quiz_add.php?lesson_id=<?php echo $lesson_id; ?>&course_id=<?php echo $course_id; ?>"
                                    class="btn btn-primary"> Add Quiz </a>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if ($quizresult->num_rows > 0):
                       
                        while ($row = $quizresult->fetch_assoc()):
                          
                            ?>

                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between align-items-center">

                                    <!-- quiz Info -->
                                    <div>
                                        <strong class="text-primary">
                                            <i class="fas fa-file-alt"></i>
                                        </strong>
                                        <strong><?= htmlspecialchars($row['quiz_title']) ?></strong>
                                    </div>

                                    <!-- Actions -->
                                    <div class="action-icons">
                                        <?php if (userHasPermission(QUIZ_RESULT_VIEW_ICON)) { ?>

                                          
                                            <a
                                                href="quiz_result.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>">
                                                <i class="fas fa-eye text-primary" title="View Quiz Results"></i>
                                            </a>
                                        <?php } ?>
                                        <?php if (userHasPermission(QUIZ_EDIT)) {
                                            if ($row['status'] !== 'dispatch' || $_SESSION['LoggedInUserType'] == SITEADMIN) { ?>

                                                <!-- Edit Quiz -->
                                                <a
                                                    href="quiz_add.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>">
                                                    <i class="fas fa-edit text-primary" title="Edit"></i>
                                                </a>
                                            <?php }
                                        } ?>

                                        <?php if (userHasPermission(QUIZ_DELETE)) { ?>
                                            <!-- Delete -->
                                            <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete"
                                                onclick="openDeleteModal('quiz_delete.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>', 'This will permanently delete the quiz.\nAll results will also be removed.\n\nDo you want to continue?')">
                                            </i>
                                        <?php } ?>


                                        <!-- Attempt quiz -->
                                        <!-- <a href="quiz_attempt.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>"
                                            class="btn btn-primary" onclick="attemptquiz(<?= $row['quiz_id'] ?>)"> Attempt
                                        </a> -->
                                        <?php
                                        $attemptedquiz = [];
                                        $quiz_id = $row['quiz_id'];
                                        $quiz_completed = false;

                                        if ($user_id > 0 && $quiz_id > 0) {

                                            $sql = " SELECT 'x' FROM quiz_attempt WHERE user_id = $user_id AND quiz_id = $quiz_id AND status = '" . COMPLETED . "'";

                                            $res = $conn->query($sql);

                                            if ($res && $res->num_rows > 0) {
                                                $quiz_completed = true;

                                            }
                                        }





                                        if (userHasPermission(QUIZ_ATTEMPT)) { ?>
                                            <?php if ($quiz_completed) { ?>

                                                <!-- Already Attempted -->
                                                <button class="btn btn-secondary" disabled>
                                                    Completed
                                                </button>

                                            <?php } else { ?>

                                                <!-- Not Attempted -->
                                                <a href="quiz_attempt.php?quiz_id=<?= $row['quiz_id'] ?>&course_id=<?= $course_id ?>&lesson_id=<?= $lesson_id ?>"
                                                    class="btn btn-primary">
                                                    Attempt
                                                </a>

                                            <?php }
                                        } ?>

                                        <?php
                                        if (userHasPermission(QUIZ_DISPATCH)):
                                            $quiz_id = $row['quiz_id'];

                                            // Check if questions assigned
                                            $check = $conn->query("SELECT COUNT(*) AS cnt FROM quiz_questions WHERE quiz_id=$quiz_id");
                                            $hasQuestions = $check->fetch_assoc()['cnt'] > 0;

                                            if ($hasQuestions):
                                                $disabled = ($row['status'] == 'dispatch') ? 'disabled style="pointer-events:none;opacity:0.5;"' : '';
                                                ?>
                                                <a class="btn btn-primary"
                                                    href="dispatch_quiz.php?quiz_id=<?= $quiz_id ?>&lesson_id=<?= $lesson_id ?>&course_id=<?= $course_id ?>"
                                                    <?= $disabled ?>>
                                                    Dispatch Quiz
                                                </a>
                                            <?php endif;
                                        endif;
                                        ?>


                                    </div>

                                </div>
                            </div>

                        <?php endwhile; ?>
                        <?php if ($totalPages > 1): ?>
                            <nav>
                                <ul class="pagination justify-content-center mt-4">

                                    <!-- Previous -->
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                                Prev
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link"
                                                href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- Next -->
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                                Next
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                </ul>
                            </nav>

                        <?php endif; ?>
                    <?php else: ?>
                        <div class='text-center'>No Quiz Found</div>
                    <?php endif; ?>
                    <form method="GET" class="d-flex align-items-center justify-content-center mt-3">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="course_id" value="<?= $course_id ?>">
                        <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">

                        <label class="fw-semibold me-2">Records per page:</label>
                        <select name="limit" class="form-select w-auto" onchange="this.form.submit()">
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </form>

                
            </div>

        </div>
    </div>
    <script>
        function attemptquiz(quizId) {
            setTimeout(function () {
                window.location.href =
                    "quiz_attempt.php?quizid=" + quizId + "&currentQNo=1";
            }, 300);
        }
    </script>
      <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>
</body>

</html>