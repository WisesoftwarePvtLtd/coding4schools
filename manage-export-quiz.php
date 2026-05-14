<?php
session_start();
include 'config.php';
include 'header.php';

$userType = $_SESSION['LoggedInUserType'] ?? '';
$loggedUserId = $_SESSION['LoggedInUserId'] ?? 0;
$selectedcourseId = $_GET['course_id'] ?? '';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';


/* ===============================
   1️⃣ GET TEACHER ID
================================ */
$teacherId = 0;
if ($userType === 'teacher') {
    $stmt = $conn->prepare("SELECT teacher_id FROM teachers WHERE user_id = ?");
    $stmt->bind_param("i", $loggedUserId);
    $stmt->execute();
    $stmt->bind_result($teacherId);
    $stmt->fetch();
    $stmt->close();
}

/* ===============================
   2️⃣ GET courseS (Teacher / Admin)
================================ */
$courses = [];

if ($userType === 'teacher') {
    $stmt = $conn->prepare("
        SELECT DISTINCT b.course_id, b.course_title, st.grade_id
        FROM section_teachers st
        JOIN courses b ON b.course_id = st.course_id
        JOIN grades g ON g.grade_id = st.grade_id
        WHERE st.teacher_id = ? AND g.school_id = ?
    ");
    $stmt->bind_param("ii", $teacherId ,$userSchoolId);
} else {
    $stmt = $conn->prepare("
        SELECT DISTINCT b.course_id, b.course_title
        FROM section_courses sc
        JOIN courses b ON b.course_id = sc.course_id
        JOIN grades g ON g.grade_id = sc.grade_id
        WHERE g.school_id = ?
    ");
    
    $stmt->bind_param("i", $userSchoolId);
}

$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage / Export Quiz Result</title>
</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area text-dark">
                
                    <div class="d-flex justify-content-between mb-3 mt-3">
                        <h3 class="fw-bolder">Manage / Export Quiz Result</h3>
                    </div>

                    <!-- 🔽 course DROPDOWN -->
                    <form method="GET" class="mb-4" style="max-width:400px;">
                        <!-- <label class="fw-bold mb-2">Select course</label> -->
                        <select name="course_id" class="form-control w-50" onchange="this.form.submit()">
                            <option value="">Select course </option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['course_id'] ?>" <?= ($selectedcourseId == $course['course_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['course_title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>

                    <?php if ($selectedcourseId): ?>
                        <!-- ================= SELECTED course ================= -->
                        <?php
                        $courseTitle = '';
                        foreach ($courses as $b) {
                            if ($b['course_id'] == $selectedcourseId) {
                                $courseTitle = $b['course_title'];
                                break;
                            }
                        }
                        ?>
                        <?php
                        /* ===============================
                           3️⃣ GET LESSONS
                        ================================ */
                        $stmt = $conn->prepare(" SELECT GROUP_CONCAT(lesson_id ORDER BY lesson_sort_order SEPARATOR ',') AS lesson_ids,lesson_title FROM lessons WHERE course_id = ?");
                        $stmt->bind_param("i", $selectedcourseId);
                        $stmt->execute();
                        $result = $stmt->get_result()->fetch_assoc();
                        $lessonIds = $result['lesson_ids']; // e.g. "2,5,7,9"
                        $stmt->close();
                        if (empty($lessonIds)) {
                            echo "<div class='text-center'>No lesson found for this course</div>";
                            return;
                        }
                        ?>
                        <?php
                        /* ===============================
                           4️⃣ GET QUIZZES
                        ================================ */
                        $quizSql = " SELECT   q.quiz_id, q.quiz_title, l.lesson_title FROM quiz q JOIN lessons l ON l.lesson_id = q.lesson_id WHERE q.lesson_id IN ($lessonIds) AND (q.school_id = 0 || q.school_id = $userSchoolId) AND q.status = 'DISPATCH' ORDER BY l.lesson_sort_order";
                        $quizResult = $conn->query($quizSql);
                        if ($quizResult->num_rows == 0) {
                            echo "<div class='text-center'>No quiz found for this course</div>";
                        }
                        ?>
                        <?php while ($quiz = $quizResult->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">
                                    <div>
                                        <strong class="text-dark">
                                            📝 <?= htmlspecialchars($quiz['quiz_title']) ?>
                                        </strong>
                                        <div class="exam-card-details">
                                            📘 course: <?= htmlspecialchars($courseTitle) ?> |
                                            📖 Lesson: <?= htmlspecialchars($quiz['lesson_title']) ?>
                                        </div>
                                    </div>
                                    <div class="action-icons">
                                        <a href="export_quiz.php?quiz_id=<?= $quiz['quiz_id'] ?>"
                                            class="btn btn-sm btn-primary w-100">
                                            Export Result
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                    <?php endif; ?>
                
            </div>
        </div>
    </div>
    </div>
</body>

</html>