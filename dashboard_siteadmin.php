<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';
$user_id = $_SESSION['LoggedInUserId'] ?? 0;

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

                                <?php

                                /* ========= NO OF TEACHERS ========= */
                                $stmt = $conn->prepare(" SELECT COUNT(DISTINCT t.teacher_id) AS total FROM teachers t INNER JOIN users u ON t.user_id = u.user_id WHERE u.school_id = ?");

                                $stmt->bind_param("i", $userSchoolId);
                                $stmt->execute();
                                $teacherCount = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

                                /* ========= NO OF STUDENTS ========= */
                                $stmt = $conn->prepare(" SELECT COUNT(DISTINCT s.student_id) AS total FROM students s INNER JOIN users u ON s.user_id = u.user_id WHERE u.school_id = ?");

                                $stmt->bind_param("i", $userSchoolId);
                                $stmt->execute();
                                $studentCount = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

                                /* ========= NO OF GRADES ========= */
                                $stmt = $conn->prepare(" SELECT COUNT(DISTINCT grade_id) AS total FROM grades  WHERE school_id = ?");
                                $stmt->bind_param("i", $userSchoolId);
                                $stmt->execute();
                                $gradeCount = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

                                /* ========= NO OF SECTIONS ========= */
                                /* ========= NO OF SECTIONS ========= */
                                $stmt = $conn->prepare("
                                    SELECT COALESCE(COUNT(DISTINCT s.section_id), 0) AS total
                                    FROM sections s
                                    JOIN grades g ON g.grade_id = s.grade_id
                                    WHERE g.school_id = ?
                                ");

                                $stmt->bind_param("i", $userSchoolId);
                                $stmt->execute();

                                $sectionCount = (int) ($stmt->get_result()->fetch_assoc()['total'] ?? 0);

                                /* ========= NO OF courseS ========= */
                                $courseSql = "SELECT COALESCE(COUNT(DISTINCT course_id), 0) AS total FROM courses";
                                $courseCount = (int) ($conn->query($courseSql)->fetch_assoc()['total'] ?? 0);

                                /* ========= NO OF LESSONS ========= */
                                $lessonSql = "SELECT COALESCE(COUNT(DISTINCT lesson_id), 0) AS total FROM lessons WHERE lesson_type = 'lesson'";
                                $lessonCount = (int) ($conn->query($lessonSql)->fetch_assoc()['total'] ?? 0);



                                /* ========= NO OF QUIZ ========= */
                                // $stmt = $conn->prepare("
                                //     SELECT COUNT(DISTINCT q.quiz_id) AS total
                                //     FROM quiz q
                                //     JOIN courses c ON c.course_id = q.course_id
                                //     JOIN section_courses sc ON sc.course_id = c.course_id
                                //     JOIN grades g ON g.grade_id = sc.grade_id
                                //     WHERE g.school_id = ?
                                // ");
                                
                                //                                 $stmt = $conn->prepare("
//     SELECT COUNT(DISTINCT q.quiz_id) AS total
//     FROM quiz q
// where (q.school_id = 0 OR q.school_id = ?)
// ");
                                $stmt = $conn->prepare("
                                    SELECT COUNT(DISTINCT q.quiz_id) AS total
                                    FROM quiz q
                                    WHERE 
                                    (
                                        (q.status = 'DISPATCH' AND (q.school_id = 0 OR q.school_id = ?))
                                        OR
                                        ((q.status IS NULL OR q.status != 'DISPATCH') AND q.user_id = ?)
                                    )
                                ");

                                $stmt->bind_param("ii", $userSchoolId, $user_id);
                                $stmt->execute();

                                $quizCount = (int) ($stmt->get_result()->fetch_assoc()['total'] ?? 0);


                                ?>

                                <div class="col-md-3">
                                    <div class="info-card bg-blue">
                                        <div>
                                            <h5>No of Teachers</h5>
                                            <h2><?= $teacherCount ?></h2>
                                        </div>
                                        <i class="bi bi-person-badge fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-card bg-orange">
                                        <div>
                                            <h5>No of Students</h5>
                                            <h2><?= $studentCount ?></h2>
                                        </div>
                                        <i class="bi bi-person-lines-fill fs-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-card bg-purple">
                                        <div>
                                            <h5>No of Grades</h5>
                                            <h2><?= $gradeCount ?></h2>
                                        </div>
                                        <i class="bi bi-award fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="info-card bg-green">
                                        <div>
                                            <h5>No of Sections</h5>
                                            <h2><?= $sectionCount ?></h2>
                                        </div>
                                        <i class="bi bi-grid-3x3-gap-fill fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-3">
                                    <div class="info-card bg-red">
                                        <div>
                                            <h5>No of courses</h5>
                                            <h2><?= $courseCount ?></h2>
                                        </div>
                                        <i class="bi bi-course-half fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-3">
                                    <div class="info-card bg-yellow">
                                        <div>
                                            <h5>No of Lessons</h5>
                                            <h2><?= $lessonCount ?></h2>
                                        </div>
                                        <i class="bi bi-journal-coursemark fs-2"></i>
                                    </div>
                                </div>



                                <div class="col-md-3 mt-3">
                                    <div class="info-card bg-pink">
                                        <div>
                                            <h5>No of Quizes</h5>
                                            <h2><?= $quizCount ?></h2>
                                        </div>
                                        <i class="bi bi-question-square-fill fs-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <?php


                                    $sql = "
SELECT 
    g.grade_name,
    COUNT(DISTINCT ss.student_id) AS student_count
FROM grades g
LEFT JOIN section_students ss ON ss.grade_id = g.grade_id
WHERE g.school_id = $userSchoolId
GROUP BY g.grade_id
ORDER BY g.grade_id
";

                                    $result = $conn->query($sql);

                                    $gradeLabels = [];
                                    $studentCounts = [];
                                    $bgColors = [];

                                    $colors = ['#10b981', '#8b5cf6', '#f97316', '#ef4444', '#06b6d4', '#eab308'];

                                    $i = 0;
                                    while ($row = $result->fetch_assoc()) {
                                        $gradeLabels[] = $row['grade_name'];
                                        $studentCounts[] = (int) $row['student_count'];
                                        $bgColors[] = $colors[$i % count($colors)];
                                        $i++;
                                    }
                                    ?>

                                    <div class="data-card">
                                        <h5>Gradewise No of Students</h5>

                                        <div class="chart-legend">
                                            <?php foreach ($gradeLabels as $index => $grade): ?>
                                                <span class="color-box" style="background:<?= $bgColors[$index] ?>"></span>
                                                <?= htmlspecialchars($grade) ?>
                                            <?php endforeach; ?>
                                        </div>

                                        <canvas id="gradewisestudentChart"></canvas>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <?php
                                    /*
                                      Grade-wise average percentage
                                      - Only completed attempts
                                      - Percentage stored as '85%'
                                    */

                                    //                                     $sql = "
// SELECT 
//     g.grade_name,
//     ROUND(AVG(CAST(REPLACE(qa.percentage, '%', '') AS DECIMAL(5,2))), 2) AS avg_percentage
// FROM quiz_attempt qa
// JOIN quiz q ON q.quiz_id = qa.quiz_id
// JOIN quiz_applicable_for qaf ON qaf.quiz_id = q.quiz_id
// JOIN grades g ON g.grade_id = qaf.grade_id
// WHERE qa.status = 'completed'
// AND g.school_id = $userSchoolId
// GROUP BY g.grade_id
// ORDER BY g.grade_id
// ";
                                    
                                    $sql = "
SELECT 
    g.grade_name,
    ROUND(AVG(CAST(REPLACE(qa.percentage, '%', '') AS DECIMAL(5,2))), 2) AS avg_percentage
FROM quiz_attempt qa
JOIN quiz q ON q.quiz_id = qa.quiz_id
JOIN section_courses sc ON sc.course_id = q.course_id
JOIN grades g ON g.grade_id = sc.grade_id
WHERE qa.status = 'completed'
AND g.school_id = $userSchoolId
GROUP BY g.grade_id, g.grade_name
ORDER BY g.grade_id
";

                                    $result = $conn->query($sql);

                                    $gradeavgLabels = [];
                                    $avgPercentages = [];

                                    while ($row = $result->fetch_assoc()) {
                                        $gradeavgLabels[] = $row['grade_name'];
                                        $avgPercentages[] = (float) $row['avg_percentage'];
                                    }
                                    ?>

                                    <div class="data-card">
                                        <h5>Gradewise Average Marks</h5>
                                        <p class="text-center">
                                            <span class="color-box" style="background:#06b6d4"></span>
                                            Grades Average Percentage
                                        </p>
                                        <canvas id="gradewiseaverageChart"></canvas>
                                    </div>
                                </div>

                            </div>

                            <!-- <div class="data-card">
                                <h5>Grade Average Per Course</h5>
                                <div class="chart-legend text-center px-4 py-2">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                        <div><span class="color-box"
                                                style="background:#8b5cf6; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 1a</div>
                                        <div><span class="color-box"
                                                style="background:#f97316; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 1b</div>
                                        <div><span class="color-box"
                                                style="background:#ef4444; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 2a</div>
                                        <div><span class="color-box"
                                                style="background:#10b981; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 2b</div>
                                        <div><span class="color-box"
                                                style="background:#06b6d4; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 3a</div>
                                    </div>
                                </div>
                                <canvas id="courseChart"></canvas>
                            </div> -->

                            <!-- <div class="data-card">
                                <h5>School's Grade Average Per Gender</h5>
                                <div class="gender-legend text-center">
                                    <span class="color-box" style="background-color: #2f1ee6ff;"></span> Boy
                                    <span class="color-box" style="background-color: #e8ade4ff;"></span> Girl
                                </div>
                                <canvas id="genderChart"></canvas>
                            </div> -->

                        </div>

                        <!-- <div class="right-content" style="flex: 1;">
                           



                            <div class="mt-5">
                                <h5>Additional Info</h5>
                                <ul>
                                    <li>📊 No of Teachers: 12</li>
                                    <li>🎯 Active Courses: 18</li>
                                    <li>⏰ Upcoming quizzes: 4</li>
                                    <li>📝 New Assignments: 3</li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Chart 1

        const gradeLabels = <?= json_encode($gradeLabels) ?>;
        const studentCounts = <?= json_encode($studentCounts) ?>;
        const bgColors = <?= json_encode($bgColors) ?>;

        new Chart(document.getElementById('gradewisestudentChart'), {
            type: 'bar',
            data: {
                labels: gradeLabels,
                datasets: [{
                    label: 'No of Students',
                    data: studentCounts,
                    backgroundColor: bgColors,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1   // 👈 ye add karo
                        },
                        title: {
                            display: true,
                            text: 'Students Count'
                        }
                    }
                }
            }
        });



        // Chart 2

        const $gradeavgLabels = <?= json_encode($gradeavgLabels) ?>;
        const avgPercentages = <?= json_encode($avgPercentages) ?>;

        new Chart(document.getElementById('gradewiseaverageChart'), {
            type: 'line',
            data: {
                labels: $gradeavgLabels,
                datasets: [{
                    label: 'Average %',
                    data: avgPercentages,
                    borderColor: '#06b6d4',
                    backgroundColor: '#06b6d4',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average Percentage'
                        }
                    }
                }
            }
        });


    </script>



</body>

</html>