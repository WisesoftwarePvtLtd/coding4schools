<?php
session_start();
include 'header.php';
include 'config.php';

$lesson_id = intval($_GET['lesson_id'] ?? 0);
if ($lesson_id <= 0) die("Invalid lesson");

// LESSON NAME + COURSE ID
$lesson = $conn->query("
    SELECT lesson_title, course_id 
    FROM lessons 
    WHERE lesson_id = $lesson_id
")->fetch_assoc();

$lesson_name = $lesson['lesson_title'] ?? '';
$course_id   = $lesson['course_id'] ?? 0;

$userType = $_SESSION['LoggedInUserType'] ?? '';

// SEARCH
$search = trim($_GET['search'] ?? '');
$like = "%$search%";

// FETCH problemS
$stmt = $conn->prepare("
    SELECT problem_id, problem_name
    FROM problem
    WHERE lesson_id = ?
    AND problem_name LIKE ?
    ORDER BY problem_id DESC
");
$stmt->bind_param("is", $lesson_id, $like);
$stmt->execute();
$problems = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage problems</title>
</head>

<body>

<div class="container-fluid" style="padding:30px;">
    <div class="layout-row">

     
        <!-- MAIN AREA -->
        <div class="main-area text-dark">
            <div id="globalMsg" class="global-msg"></div>

            <div class="container-fluid p-4">


                <!-- problem LIST (same card layout as grades) -->
                
                    <?php if ($problems->num_rows == 0) { ?>
                        <div class="col-12 text-center text-muted">
                            No problems found
                        </div>
                    <?php } ?>

                    <?php while ($row = $problems->fetch_assoc()) { ?>
                     <div class="col-md-12 mb-3">
            <div class="grade-box">
                <div class="grade-row">

                        

                                    <h5 class="fw-bold mb-3">
                                        <?= htmlspecialchars($row['problem_name']) ?>
                                    </h5>

                                 

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










