<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<?php
include 'config.php';
include 'header.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

$stmt = $conn->prepare("
    SELECT 
        s.student_id,
        sec.gender,
        g.grade_name,
        sec.section_name,
        sec.section_id
    FROM students s
    JOIN section_students ss ON ss.student_id = s.student_id
    JOIN grades g ON g.grade_id = ss.grade_id
    JOIN sections sec ON sec.section_id = ss.section_id
    WHERE s.user_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$student_id = $student['student_id'];
$grade_name = $student['grade_name'];     // ✅ Grade NAME
$section_name = $student['section_name'];   // ✅ Section NAME
$section_id = $student['section_id'];     // internal use only
$gender = $student['gender'];

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_courses
    FROM section_courses
    WHERE section_id = ?
");
$stmt->bind_param("i", $section_id);
$stmt->execute();
$courseCount = $stmt->get_result()->fetch_assoc()['total_courses'];
?>



<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">
                <!-- Dashboard -->
                <div id="dashboardWrapper">
                    <div class="flex-row">
                        <div class="main-content" style="flex: 3;">
                            <div class="row g-4 mb-3">

                                <div class="col-md-4">
                                    <div class="info-card bg-blue">
                                        <div>
                                            <h5>Enrolled courses</h5>
                                            <h6><?= $courseCount ?></h6>

                                        </div>
                                        <i class="bi bi-person fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card bg-purple">
                                        <div>
                                            <h5>Assigned Grade</h5>
                                            <h6><?= htmlspecialchars($grade_name) ?></h6>

                                        </div>
                                        <i class="bi bi-diagram-3 fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card bg-orange">
                                        <div>
                                            <h5>Assigned Section</h5>
                                            <h6><?= htmlspecialchars($section_name) . "-" . $gender ?></h6>

                                        </div>
                                        <i class="bi bi-grid fs-2"></i>
                                    </div>
                                </div>

                            </div>


                            <div class="row g-4">
                                <div class="col-md-6">
                                    <?php
                                    // include 'config.php';
// include 'header.php';
                                    
                                    // $user_id = $_SESSION['LoggedInUserId'] ?? 0;
                                    
                                    // 🔹 Student ke completed quizzes + percentage
                                    $stmt = $conn->prepare("
    SELECT 
        q.quiz_title,
        CAST(REPLACE(qa.percentage, '%', '') AS DECIMAL(5,2)) AS score
    FROM quiz_attempt qa
    JOIN quiz q ON q.quiz_id = qa.quiz_id
    WHERE qa.user_id = ?
      AND qa.status = 'completed'
    ORDER BY qa.completed_at
");
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    $labels = [];
                                    $scores = [];

                                    while ($row = $res->fetch_assoc()) {
                                        $labels[] = $row['quiz_title'];          // quiz name
                                        $scores[] = (float) $row['score'];        // Percentage
                                    }

                                    // 🔥 Agar koi quiz nahi diya ho
                                    if (empty($labels)) {
                                        $labels = ['No quizs'];
                                        $scores = [0];
                                    }
                                    ?>

                                    <div class="data-card">
                                        <h5>Student Success Rate (Completed quiz)</h5>
                                        <canvas id="successChart"></canvas>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <?php


                                    /* 🔹 Student ka grade & section */
                                    // $stmt = $conn->prepare(" SELECT ss.grade_id, ss.section_id FROM students s JOIN section_students ss ON ss.student_id = s.student_id WHERE s.user_id = ? LIMIT 1");
                                    // $stmt->bind_param("i", $user_id);
                                    // $stmt->execute();
                                    // $student = $stmt->get_result()->fetch_assoc();
                                    
                                    // $grade_id = $student['grade_id'];
                                    // $section_id = $student['section_id'];
                                    $stmt = $conn->prepare("
    SELECT 
        ss.grade_id, 
        ss.section_id,
        sc.course_id,
        l.lesson_id
    FROM students s 
    JOIN section_students ss 
        ON ss.student_id = s.student_id 
    JOIN section_courses sc  
        ON sc.section_id = ss.section_id
    JOIN lessons l 
        ON l.course_id = sc.course_id
    WHERE s.user_id = ?
");

                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();

                                    $result = $stmt->get_result(); // ✅ only once
                                    
                                    while ($row = $result->fetch_assoc()) {
                                        $row['grade_id'];
                                        $row['section_id'];
                                        $row['course_id'];
                                        $row['lesson_id'];
                                    }

                                    /* 🔹 Total quizs assigned to section */
                                    $stmt = $conn->prepare(" SELECT COUNT(DISTINCT ea.quiz_id) AS total_quizs FROM quiz_applicable_for ea
                                    JOIN unlock_quiz ue 
                ON ue.quiz_id = ea.quiz_id
                                    
                                     WHERE ea.grade_id = ? AND ea.section_id = ?
                                     AND ue.is_unlocked = 1");
                                    $stmt->bind_param("ii", $grade_id, $section_id);
                                    $stmt->execute();
                                    $total_quizs = (int) $stmt->get_result()->fetch_assoc()['total_quizs'];

                                    /* 🔹 Completed quizs */
                                    $stmt = $conn->prepare(" SELECT COUNT(DISTINCT quiz_id) AS completed FROM quiz_attempt WHERE user_id = ? AND status = 'completed'");
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $completed = (int) $stmt->get_result()->fetch_assoc()['completed'];


                                    /* 🔹 Attempted (in_progress) quizs */
                                    $stmt = $conn->prepare(" SELECT COUNT(DISTINCT quiz_id) AS attempted FROM quiz_attempt  WHERE user_id = ? AND status = 'in_progress'");
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $attempted = (int) $stmt->get_result()->fetch_assoc()['attempted'];


                                    /* 🔹 Pending quizs count */





                                    $sql = "
SELECT COUNT(DISTINCT q.quiz_id) AS pending_count
FROM students s
JOIN section_students ss 
    ON ss.student_id = s.student_id
JOIN section_courses sc 
    ON sc.section_id = ss.section_id
JOIN lessons l 
    ON l.course_id = sc.course_id

    -- ✅ ADD THIS JOIN (IMPORTANT)
JOIN unlock_lessons ul 
    ON ul.lesson_id = l.lesson_id
    AND ul.grade_id = sc.grade_id
    AND ul.section_id = sc.section_id
    AND ul.is_unlocked = 1

JOIN quiz q 
    ON q.lesson_id = l.lesson_id
JOIN unlock_quiz ue 
    ON ue.quiz_id = q.quiz_id AND ue.is_unlocked = 1
LEFT JOIN quiz_attempt a 
    ON a.quiz_id = q.quiz_id 
    AND a.user_id = ?
    AND a.status IN ('completed', 'in_progress')
WHERE s.user_id = ?
AND a.quiz_id IS NULL
AND ue.school_id = ?

";

                                    $pending_stmt = $conn->prepare($sql);
                                    $pending_stmt->bind_param("iii", $user_id, $user_id, $userSchoolId);
                                    $pending_stmt->execute();

                                    $result = $pending_stmt->get_result();
                                    $row = $result->fetch_assoc();

                                    $pending = (int) $row['pending_count']; // ✅ number mil jayega
                                    
                                    ?>

                                    <div class="data-card">
                                        <h5>Student completion status</h5>
                                        <canvas id="completionstatusChart"></canvas>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart 1

        const quizLabels = <?= json_encode($labels); ?>;
        const quizScores = <?= json_encode($scores); ?>;

        new Chart(document.getElementById('successChart'), {
            type: 'bar',
            data: {
                labels: quizLabels,
                datasets: [{
                    label: 'Score (%)',
                    data: quizScores,
                    backgroundColor: [
                        '#10b981',
                        '#8b5cf6',
                        '#f97316',
                        '#ef4444',
                        '#0ea5e9',
                        '#22c55e'
                    ],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.raw + '%'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                }
            }
        });

        // Chart 3

        new Chart(document.getElementById('completionstatusChart'), {
            type: 'bar',
            data: {
                labels: ['Pending', 'Attempted', 'Completed'],
                datasets: [{
                    data: [
                        <?= $pending ?>,
                        <?= $attempted ?>,
                        <?= $completed ?>
                    ],
                    backgroundColor: ['#ef4444', '#06b6d4', '#10b981'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.raw + ' quizs'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });




    </script>


</body>

</html>