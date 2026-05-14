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
$teacher_id = $_SESSION['LoggedInTeacherId'] ?? 0;
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

// print_r($user_id);
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
                                $selected_grade = isset($_GET['grade_id']) ? $_GET['grade_id'] : 'all';

                                // First, get teacher's assigned grades
                                $stmt = $conn->prepare(" SELECT DISTINCT g.grade_id   FROM section_teachers st  INNER JOIN grades g ON g.grade_id = st.grade_id WHERE st.teacher_id = (SELECT teacher_id FROM teachers WHERE user_id = ?)");
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $teacherGrades = $stmt->get_result();

                                $gradeIds = [];
                                while ($row = $teacherGrades->fetch_assoc()) {
                                    $gradeIds[] = $row['grade_id'];
                                }

                                // If no grades assigned
                                if (empty($gradeIds)) {
                                    $studentCount = 0;
                                    $sectionCount = 0;

                                } else {
                                    $gradeIdsStr = implode(',', $gradeIds);

                                    // Only apply grade filter if selected grade is in teacher's grades
                                    if ($selected_grade != 'all' && in_array($selected_grade, $gradeIds)) {
                                        $gradeFilter = "AND ss.grade_id = " . intval($selected_grade);
                                        $sectionFilter = "AND s.grade_id = " . intval($selected_grade);
                                    } else {
                                        $gradeFilter = "";
                                        $sectionFilter = "";
                                    }

                                    // Count students safely
                                    $studentQuery = " SELECT COUNT(DISTINCT ss.student_id) AS c FROM section_teachers st INNER JOIN section_students ss ON ss.section_id = st.section_id INNER JOIN teachers t ON t.teacher_id = st.teacher_id WHERE t.user_id = $user_id AND st.grade_id IN ($gradeIdsStr) $gradeFilter";
                                    $studentResult = $conn->query($studentQuery);
                                    $studentCount = $studentResult->fetch_assoc()['c'];

                                    // Count sections safely
                                    $sectionQuery = " SELECT COUNT(DISTINCT s.section_id) as c FROM section_teachers s WHERE s.grade_id IN ($gradeIdsStr) $sectionFilter";
                                    $sectionResult = $conn->query($sectionQuery);
                                    // if (!$sectionResult) {
                                    //     die("Section query failed: " . $conn->error);
                                    // }
                                    $sectionCount = $sectionResult->fetch_assoc()['c'];
                                }

                                // Now $studentCount and $sectionCount are safe to use
                                ?>
                                <?php
                                // ---------------- quiz COUNT ----------------
                                // if (empty($gradeIds)) {
                                //     $quizCount = 0;
                                // } else {
                                
                                //     if ($selected_grade != 'all' && in_array($selected_grade, $gradeIds)) {
                                //         // Grade wise quiz count
                                //         $quizSql = " SELECT COUNT(DISTINCT q.quiz_id) AS c FROM quiz q INNER JOIN quiz_applicable_for qaf ON qaf.quiz_id = q.quiz_id WHERE q.user_id = $user_id AND qaf.grade_id = " . intval($selected_grade);
                                //     } else {
                                //         // All grades quiz count
                                //         $quizSql = "SELECT COUNT(DISTINCT q.quiz_id) AS c FROM quiz q INNER JOIN quiz_applicable_for qaf ON qaf.quiz_id = q.quiz_id  WHERE q.user_id = $user_id AND qaf.grade_id IN ($gradeIdsStr)";
                                //     }
                                
                                //     $quizResult = $conn->query($quizSql);
                                //     $quizCount = $quizResult->fetch_assoc()['c'] ?? 0;
                                // }
                                if (empty($gradeIds)) {
                                    $quizCount = 0;
                                } else {

                                    
                                    if ($selected_grade != 'all' && in_array($selected_grade, $gradeIds)) {

                                        // ✅ Grade wise count
                                        $stmt = $conn->prepare("
            SELECT COUNT(DISTINCT q.quiz_id) AS total
            FROM quiz q
            JOIN courses c ON c.course_id = q.course_id
            JOIN section_courses sc ON sc.course_id = c.course_id
            JOIN grades g ON g.grade_id = sc.grade_id
            WHERE g.school_id = ?
            AND (q.school_id = 0 OR q.school_id = ?)
             AND q.status = 'DISPATCH'
            AND sc.grade_id = ?
        ");

                                        $stmt->bind_param("iii", $userSchoolId, $userSchoolId, $selected_grade);

                                    } else {

                                        // ✅ All grades count
                                        $stmt = $conn->prepare("
            SELECT COUNT(DISTINCT q.quiz_id) AS total
            FROM quiz q
            JOIN courses c ON c.course_id = q.course_id
            JOIN section_courses sc ON sc.course_id = c.course_id
            JOIN grades g ON g.grade_id = sc.grade_id
            WHERE g.school_id = ?
            AND (q.school_id = 0 OR q.school_id = ?)
             AND q.status = 'DISPATCH'
            AND sc.grade_id IN ($gradeIdsStr)
        ");

                                        $stmt->bind_param("ii", $userSchoolId, $userSchoolId);
                                    }

                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $quizCount = $result->fetch_assoc()['total'] ?? 0;

                                }
                                ?>


                                <div class="col-md-4">
                                    <div class="info-card bg-blue">
                                        <div>
                                            <h5>Number of Students</h5>
                                            <h2><?= $studentCount; ?></h2>
                                        </div>
                                        <i class="bi bi-people fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card bg-purple">
                                        <div>
                                            <h5>Number of Sections</h5>
                                            <h2><?= $sectionCount; ?></h2>
                                        </div>
                                        <i class="bi bi-diagram-3 fs-2"></i>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card bg-orange">
                                        <div>
                                            <h5>Number of Generated quizes</h5>
                                            <h2><?= $quizCount; ?></h2>
                                        </div>
                                        <i class="bi bi-question-circle fs-2"></i>
                                    </div>
                                </div>

                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <?php $successLabels = [];
                                    $successData = [];
                                    $legendData = [];

                                    $colors = ['#10b981', '#8b5cf6', '#f97316', '#ef4444', '#06b6d4'];
                                    $i = 0;

                                    $stmt = $conn->prepare(" SELECT DISTINCT g.grade_id, g.grade_name FROM section_teachers st 
                                    JOIN grades g ON g.grade_id = st.grade_id 
                                    JOIN teachers t ON t.teacher_id = st.teacher_id WHERE t.user_id = ?");

                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($g = $result->fetch_assoc()) {

                                        // 🔥 FILTER BASED ON DROPDOWN
                                        if ($selected_grade !== 'all' && $g['grade_id'] != $selected_grade) {
                                            continue;
                                        }

                                        $grade_id = $g['grade_id'];
                                        $grade_name = $g['grade_name'];
                                        $color = $colors[$i % count($colors)];

                                        // total students
                                        $totalQ = "
        SELECT COUNT(DISTINCT ss.student_id) AS total
        FROM section_teachers st
        JOIN section_students ss ON ss.section_id = st.section_id
        JOIN teachers t ON t.teacher_id = st.teacher_id
        WHERE t.user_id = $user_id
        AND st.grade_id = $grade_id
    ";
                                        $total = $conn->query($totalQ)->fetch_assoc()['total'];

                                        // success students (>70%)
                                        $successQ = "
        SELECT COUNT(DISTINCT qa.user_id) AS success
    FROM quiz_attempt qa

    JOIN students s 
        ON s.user_id = qa.user_id

    JOIN section_students ss 
        ON ss.student_id = s.student_id

    JOIN section_teachers st 
        ON st.section_id = ss.section_id

    JOIN teachers t 
        ON t.teacher_id = st.teacher_id
        WHERE t.user_id = $user_id
        AND st.grade_id = $grade_id
        AND qa.status = 'completed'
        AND CAST(REPLACE(qa.percentage,'%','') AS UNSIGNED) > 70
    ";
                                        $success = $conn->query($successQ)->fetch_assoc()['success'];

                                        $percent = $success;

                                        $successLabels[] = $grade_name;
                                        $successData[] = $percent;

                                        $legendData[] = [
                                            'name' => $grade_name,
                                            'color' => $color
                                        ];

                                        $i++;
                                    }

                                    ?>
                                    <div class="data-card">
                                        <h5>Student Success Rate</h5>
                                        <p>Percentage of students who got more than 70%</p>
                                        <div class="chart-legend">
                                            <?php foreach ($legendData as $l) { ?>
                                                <span class="color-box" style="background:<?= $l['color']; ?>"></span>
                                                <?= $l['name']; ?>
                                            <?php } ?>
                                        </div>

                                        <canvas id="successChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                    // SQL: Section-wise average quiz scores for the teacher
//                                     $sectionsql = "
// SELECT 
//     st.grade_id,
//     st.section_id,
//     CONCAT( g.grade_name, ' - ', s.section_name, ' - ',s.gender ) AS section_label,
                                    
                                    //     COALESCE(
//         ROUND(
//             AVG(
//                 CASE 
//                     WHEN qa.status = 'completed'
//                     THEN CAST(REPLACE(qa.percentage, '%', '') AS DECIMAL(5,2))
//                 END
//             ),
//         2),
//     0) AS avg_score
                                    
                                    // FROM section_teachers st
// JOIN teachers t 
//     ON t.teacher_id = st.teacher_id
                                    
                                    // -- Section ke quizzes
// LEFT JOIN quiz_applicable_for qaf 
//     ON qaf.grade_id = st.grade_id 
//    AND qaf.section_id = st.section_id
// JOIN sections s
//     ON s.section_id = st.section_id
// JOIN grades g
//     ON g.grade_id = st.grade_id
// LEFT JOIN quiz q 
//     ON q.quiz_id = qaf.quiz_id
                                    
                                    // -- quiz attempts (LEFT JOIN so missing attempts still show section)
// LEFT JOIN quiz_attempt qa 
//     ON qa.quiz_id = q.quiz_id
                                    
                                    // WHERE t.user_id = ?   -- teacher user_id
                                    
                                    // GROUP BY st.grade_id, st.section_id
// ORDER BY st.grade_id, st.section_id;
                                    
                                    // ";
                                    $sectionsql = " SELECT 
    st.grade_id,
    st.section_id,
    g.grade_name,
    sct.section_name,

    stu.student_id,
    stu.user_id,
    stu.student_name,
    CONCAT( g.grade_name, ' - ', sct.section_name, ' - ',sct.gender ) AS section_label,

    ROUND(
        AVG(
            CAST(REPLACE(qa.percentage, '%', '') AS DECIMAL(5,2))
        ),
    2) AS avg_score

FROM section_teachers st

JOIN teachers t 
    ON t.teacher_id = st.teacher_id

JOIN grades g 
    ON g.grade_id = st.grade_id

JOIN sections sct 
    ON sct.section_id = st.section_id

-- section → students
JOIN section_students ss 
    ON ss.section_id = st.section_id

JOIN students stu 
    ON stu.student_id = ss.student_id

-- grade + section → quizs
JOIN quiz_applicable_for qaf 
    ON qaf.grade_id = st.grade_id
   AND qaf.section_id = st.section_id

JOIN quiz q 
    ON q.quiz_id = qaf.quiz_id

-- student → quiz attempts
LEFT JOIN quiz_attempt qa 
    ON qa.quiz_id = q.quiz_id
   AND qa.user_id = stu.user_id
   AND qa.status = 'completed'

WHERE t.user_id = ?

GROUP BY 
    st.grade_id,
    st.section_id

ORDER BY 
    st.grade_id,
    st.section_id";

                                    $sectionLabels = [];
                                    $sectionData = [];

                                    $stmt = $conn->prepare($sectionsql);
                                    $stmt->bind_param("i", $user_id); // $user_id = logged-in teacher
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    while ($row = $res->fetch_assoc()) {
                                        $sectionLabels[] = $row['section_label'];
                                        $sectionData[] = (float) $row['avg_score'];
                                    }
                                    ?>
                                    <div class="data-card">
                                        <h5>Teacher's Grade Average</h5>
                                        <p class="text-center">
                                            <span class="color-box" style="background:#06b6d4"></span> Grades Averages
                                        </p>
                                        <canvas id="gradeChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="data-card">
                                <?php $courseLabels = [];
                                $courseData = [];
                                $courseLegend = [];

                                $colors = ['#8b5cf6', '#f97316', '#ef4444', '#10b981', '#06b6d4'];
                                $i = 0;

                                $sql = "
    SELECT 
    b.course_id,
    b.course_title,
    b.course_cover_page,
    ROUND(AVG(CAST(REPLACE(qa.percentage, '%','') AS DECIMAL(5,2))), 2) AS avg_score
FROM users u
JOIN teachers t ON t.user_id = u.user_id
JOIN section_teachers st ON st.teacher_id = t.teacher_id
JOIN section_courses sb ON sb.section_id = st.section_id
JOIN courses b ON sb.course_id = b.course_id
JOIN quiz q ON q.course_id = b.course_id
JOIN quiz_attempt qa 
    ON qa.quiz_id = q.quiz_id 
   AND qa.status = 'completed'
WHERE u.user_id = ?
GROUP BY b.course_id, b.course_title, b.course_cover_page
ORDER BY b.course_title;

";
                                // $sql = "SELECT 
//     b.course_id,
//     b.course_title,
//     qa.percentage
// FROM section_teachers st
// JOIN teachers t ON t.teacher_id = st.teacher_id
// JOIN section_students ss ON ss.section_id = st.section_id
// JOIN students std ON std.student_id = ss.student_id
// JOIN quiz_attempt qa 
//     ON qa.user_id = std.user_id 
//    AND qa.status = 'completed'
// JOIN quiz q ON q.quiz_id = qa.quiz_id
// JOIN courses b ON b.course_id = q.course_id
// WHERE t.user_id = ?";
                                

                                $stmt = $conn->prepare($sql);


                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $res = $stmt->get_result();

                                $courseLabels = [];
                                $courseData = [];
                                $courseLegend = [];

                                $colors = ['#8b5cf6', '#f97316', '#ef4444', '#10b981', '#06b6d4'];
                                $i = 0;

                                while ($row = $res->fetch_assoc()) {
                                    $courseLabels[] = $row['course_title'];
                                    $courseData[] = (int) $row['avg_score'];

                                    $courseLegend[] = [
                                        'name' => $row['course_title'],
                                        'color' => $colors[$i % count($colors)]
                                    ];
                                    $i++;
                                }

                                ?>
                                <h5>Grade Average Per course</h5>
                                <div class="chart-legend text-center px-4 py-2">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                        <?php foreach ($courseLegend as $b) { ?>
                                            <div>
                                                <span class="color-box"
                                                    style="background:<?= $b['color']; ?>; margin-left:8px;"></span>
                                                <?= $b['name']; ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <!-- <div class="chart-legend text-center px-4 py-2">
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
                                </div> -->
                                <canvas id="courseChart"></canvas>
                            </div>

                            <!-- <div class="data-card">
                                <h5>School's Grade Average Per Gender</h5>
                                <div class="gender-legend text-center">
                                    <span class="color-box" style="background-color: #2f1ee6ff;"></span> Boy
                                    <span class="color-box" style="background-color: #e8ade4ff;"></span> Girl
                                </div>
                                <canvas id="genderChart"></canvas>
                            </div> -->

                        </div>

                        <div class="right-content" style="flex: 1;">
                            <?php

                            $stmt = $conn->prepare(" SELECT   g.grade_id, g.grade_name, g.academic_year, GROUP_CONCAT(DISTINCT s.section_name ORDER BY s.section_name SEPARATOR ', ') AS sections FROM section_teachers st INNER JOIN grades g ON g.grade_id = st.grade_id INNER JOIN sections s ON s.section_id = st.section_id INNER JOIN teachers t ON t.teacher_id = st.teacher_id  WHERE t.user_id = ? GROUP BY g.grade_id ORDER BY g.grade_name");
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $grades = $stmt->get_result();
                            ?>


                            <div class="choose-grade-card">
                                <label class="form-label ">Choose Grade</label>

                                <select class="form-control" onchange="location = this.value;">
                                    <option value="?grade_id=all" <?= (!isset($_GET['grade_id']) || $_GET['grade_id'] == 'all') ? 'selected' : '' ?>>
                                        All Grades
                                    </option>

                                    <?php while ($g = $grades->fetch_assoc()) { ?>
                                        <option value="?grade_id=<?= $g['grade_id']; ?>" <?= (isset($_GET['grade_id']) && $_GET['grade_id'] == $g['grade_id']) ? 'selected' : '' ?>>
                                            <?= $g['grade_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>




                            <!-- <h5>Additional Info</h5>
                            <ul>
                                <li>📊 Total Teachers: 12</li>
                                <li>🎯 Active Courses: 18</li>
                                <li>⏰ Upcoming quizzes: 4</li>
                                <li>📝 New Assignments: 3</li>
                            </ul> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        const successLabels = <?= json_encode($successLabels); ?>;
        const successData = <?= json_encode($successData); ?>;
        const successColors = <?= json_encode(array_column($legendData, 'color')); ?>;

        // Chart 1
        new Chart(document.getElementById('successChart'), {
            type: 'bar',
            data: {
                labels: successLabels,
                datasets: [{
                    label: 'Success',
                    data: successData,
                    backgroundColor: successColors,
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // new Chart(document.getElementById('successChart'), {
        //     type: 'bar',
        //     data: {
        //         labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4'],
        //         datasets: [{
        //             data: [80, 65, 72, 90],
        //             backgroundColor: ['#10b981', '#8b5cf6', '#f97316', '#ef4444'],
        //             borderRadius: 6
        //         }]
        //     },
        //     options: {
        //         plugins: { legend: { display: false } },
        //         scales: { y: { beginAtZero: true } }
        //     }
        // });

        // Chart 2
        const gradeLabels = <?= json_encode($sectionLabels); ?>;
        const gradeData = <?= json_encode($sectionData); ?>;

        new Chart(document.getElementById('gradeChart'), {
            type: 'line',
            data: {
                labels: gradeLabels,
                datasets: [{
                    label: 'Average quiz %',
                    data: gradeData,
                    borderColor: '#06b6d4',
                    backgroundColor: '#06b6d4',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                plugins: { legend: { display: true } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
        // new Chart(document.getElementById('gradeChart'), {
        //     type: 'line',
        //     data: {
        //         labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4'],
        //         datasets: [{
        //             data: [2.5, 3.1, 3.5, 4.0],
        //             borderColor: '#06b6d4',
        //             backgroundColor: '#06b6d4',
        //             fill: false,
        //             tension: 0.3
        //         }]
        //     },
        //     options: {
        //         plugins: { legend: { display: false } },
        //         scales: { y: { beginAtZero: true } }
        //     }
        // });

        // Chart 3

        const courseLabels = <?= json_encode($courseLabels); ?>;
        const courseData = <?= json_encode($courseData); ?>;
        const courseColors = <?= json_encode(array_column($courseLegend, 'color')); ?>;

        new Chart(document.getElementById('courseChart'), {
            type: 'bar',
            data: {
                labels: courseLabels,
                datasets: [{
                    label: 'Average Score (%)',
                    data: courseData,
                    backgroundColor: courseColors,
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });



        // new Chart(document.getElementById('courseChart'), {
        //     type: 'bar',
        //     data: {
        //         labels: ['Kids 1a', 'Kids 1b', 'Kids 2a', 'Kids 2b', 'Kids 3a'],
        //         datasets: [{
        //             label: 'Average Score',
        //             data: [70, 80, 75, 85, 90],
        //             backgroundColor: ['#8b5cf6', '#f97316', '#ef4444', '#10b981', '#06b6d4'],
        //             borderRadius: 8
        //         }]
        //     },
        //     options: {
        //         plugins: { legend: { display: false } },
        //         scales: { y: { beginAtZero: true } }
        //     }
        // });


    </script>








</body>

</html>