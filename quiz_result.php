<?php
session_start();
include 'header.php';
include "config.php";

$quiz_id = intval($_GET['quiz_id'] ?? 0);
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : "";
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

$lessonName = "";

$stmt = $conn->prepare("SELECT lesson_title FROM lessons WHERE lesson_id = ?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$stmt->bind_result($lessonName);
$stmt->fetch();
$stmt->close();
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : "";
$courseName = "";

$stmt = $conn->prepare("SELECT course_title FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($courseName);
$stmt->fetch();
$stmt->close();

$gradeFilter = $_GET['grade'] ?? '';

$grades = [];
$gradeQuery = $conn->query("SELECT grade_id, grade_name FROM grades  WHERE school_id = $userSchoolId ORDER BY grade_name");

while ($g = $gradeQuery->fetch_assoc()) {
    $grades[] = $g;
}
$sections = [];

if (!empty($gradeFilter)) {

    $stmt = $conn->prepare("
        SELECT section_id, section_name, gender 
        FROM sections 
        WHERE grade_id = ? 
        ORDER BY section_name
    ");

    $stmt->bind_param("i", $gradeFilter);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $sections[] = $row;
    }
}

$sectionFilter = $_GET['section'] ?? '';
$resultFilter = $_GET['result'] ?? '';

$sql = "
    SELECT s.student_number, 
    CONCAT(s.student_name, ' ', s.family_name) AS student_name, 
    g.grade_name, CONCAT(sec.section_name, ' - ', sec.gender ) AS section_name, qa.student_marks, 
    qa.total_marks, 
    qa.percentage, 
    qa.completed_at, 
    qa.started_at,
    q.quiz_title,
    b.course_title,
    l.lesson_title
    FROM quiz_attempt qa 
    JOIN students s ON s.user_id = qa.user_id 
    JOIN section_students ss ON ss.student_id = s.student_id 
    JOIN grades g ON g.grade_id = ss.grade_id 
    JOIN sections sec ON sec.section_id = ss.section_id 
    JOIN quiz q ON q.quiz_id = qa.quiz_id 
    JOIN courses b ON b.course_id = q.course_id 
    JOIN lessons l ON l.lesson_id = q.lesson_id 
    WHERE qa.quiz_id = ? AND g.school_id = ?
    AND qa.status = 'completed' 
    
";

if (!empty($gradeFilter)) {
    $sql .= " AND g.grade_id = " . intval($gradeFilter);
}
if (!empty($sectionFilter)) {
    $sql .= " AND sec.section_id = " . intval($sectionFilter);
}

if ($resultFilter == "pass") {
    $sql .= " AND qa.percentage >= 50";
}

if ($resultFilter == "fail") {
    $sql .= " AND qa.percentage < 50";
}
$sql .= " ORDER BY qa.completed_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $quiz_id,$userSchoolId);
$stmt->execute();
$result = $stmt->get_result();


if (!$result) {
    die("Result not found");
}

$hasAttempts = ($result->num_rows > 0);
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
<html>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">
                
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder"><?= $courseName ?> - <?= $lessonName ?></h3>
                        <a href="manage_quiz.php?lesson_id=<?php echo $lesson_id; ?>&course_id=<?php echo $course_id; ?>"
                            class="btn btn-primary">
                            Back To Manage Quiz</a>
                    </div>
                    <div class="filter-box mb-4 ">

                        <form method="GET">

                            <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                            <input type="hidden" name="course_id" value="<?= $course_id ?>">

                            <div class="row g-3">

                                <!-- <div class="col-md-4">
                                    <span class="fw-semibold">Search Section</span>
                                    <input type="text" name="section" class="form-control"
                                        value="<?= $_GET['section'] ?? '' ?>">
                                </div> -->
                                <div class="col-md-3">
                                    <span class="fw-semibold">Grade</span>

                                    <select name="grade" class="form-control" onchange="this.form.submit()">

                                        <option value="">All Grades</option>

                                        <?php foreach ($grades as $g): ?>

                                            <option value="<?= $g['grade_id'] ?>" <?= ($gradeFilter == $g['grade_id']) ? 'selected' : '' ?>>

                                                <?= $g['grade_name'] ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>
                                <div class="col-md-3">

                                    <span class="fw-semibold">Section</span>

                                    <select name="section" class="form-control">

                                        <option value="">All Sections</option>

                                        <?php foreach ($sections as $sec): ?>

                                            <option value="<?= $sec['section_id'] ?>"
                                                <?= ($sectionFilter == $sec['section_id']) ? 'selected' : '' ?>>

                                                <?= $sec['section_name'] ?> - <?= $sec['gender'] ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                                <div class="col-md-3">
                                    <span class="fw-semibold">Result</span>
                                    <select name="result" class="form-control">
                                        <option value="">All</option>
                                        <option value="pass" <?= (($_GET['result'] ?? '') == "pass") ? "selected" : "" ?>>
                                            Pass
                                        </option>
                                        <option value="fail" <?= (($_GET['result'] ?? '') == "fail") ? "selected" : "" ?>>
                                            Fail
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>

                                    <a href="quiz_result.php?quiz_id=<?= $quiz_id ?>&lesson_id=<?= $lesson_id ?>&course_id=<?= $course_id ?>"
                                        class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>

                                </div>

                            </div>
                        </form>

                    </div>
                    <?php if (!$hasAttempts): ?>
                        <div class="text-center">
                            <i class="fas fa-info-circle"></i>
                            student have not attempted any exam yet.
                        </div>
                    <?php else: ?>
                        <?php while ($data = $result->fetch_assoc()):
                            $percentage = round((float) $data['percentage'], 2);
                            $resultText = ($percentage >= 50) ? "PASS" : "FAIL";
                            $resultClass = ($percentage >= 50) ? "text-success" : "text-danger"; ?>
                            <div class="result-box">

                                <div class="result-header">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h3 class="fw-bolder">Quiz Result Sheet</h3>

                                    </div>

                                    <div class="result-row">
                                        <div>
                                            <strong>Student Name:</strong>
                                        </div>
                                        <div>
                                            <?= htmlspecialchars($data['student_name']) ?>
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
                        <?php endwhile; ?>
                    <?php endif; ?>

                

            </div>
        </div>
</body>

</html>