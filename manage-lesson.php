<?php
session_start();
include 'header.php';
include 'config.php';
//include 'standard_constants.php';
// print_r($_SESSION['UserPermissions']);
$userType = $_SESSION['LoggedInUserType'] ?? '';
$course_id = isset($_GET['course_id']) ? (int) $_GET['course_id'] : 0;
$course_name = isset($_GET['course_name']) ? htmlspecialchars($_GET['course_name']) : '';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';


if ($course_id <= 0) {
    die("<div style='padding:20px;color:red;'>Invalid or missing course</div>");
}



$student_grade_id = 0;
$student_section_id = 0;

if ($userType == STUDENT) {

    // 🔹 Logged-in USER ID
    $user_id = $_SESSION['LoggedInUserId'] ?? 0;

    if ($user_id > 0) {

        // 🔹 USER → STUDENT → SECTION
        $stuQ = $conn->query("
            SELECT 
                ss.grade_id,
                ss.section_id
            FROM students s
            INNER JOIN section_students ss 
                ON ss.student_id = s.student_id
            WHERE s.user_id = $user_id
            LIMIT 1
        ");

        if ($stuQ && $stuQ->num_rows > 0) {
            $stu = $stuQ->fetch_assoc();

            $student_grade_id = (int) $stu['grade_id'];
            $student_section_id = (int) $stu['section_id'];
        }
    }
}

// $courseQ = $conn->prepare("
//     SELECT 
//         c.course_title,
//         c.course_cover_page,

//         GROUP_CONCAT(DISTINCT g.grade_name ORDER BY g.grade_name SEPARATOR ', ') AS grade_names,

//         (
//             SELECT COUNT(*) 
//             FROM section_students s 
//             WHERE s.grade_id = sc.grade_id
//         ) AS total_students,

//         (
//             SELECT COUNT(*) 
//             FROM lessons l 
//             WHERE l.course_id = c.course_id
//         ) AS total_lessons

//     FROM section_courses sc
//     JOIN courses c 
//         ON c.course_id = sc.course_id
//     LEFT JOIN grades g 
//         ON g.grade_id = sc.grade_id

//     WHERE c.course_id = ? AND g.school_id = ?
//     GROUP BY c.course_id
// ");
// $courseQ->bind_param("ii", $course_id ,$userSchoolId);
// $courseQ->execute();
// $course = $courseQ->get_result()->fetch_assoc();
// $courseQ->close();
$courseQ = $conn->prepare("
    SELECT 
        c.course_title,
        c.course_cover_page,

        GROUP_CONCAT(DISTINCT g.grade_name ORDER BY g.grade_name SEPARATOR ', ') AS grade_names,

        (
            SELECT COUNT(*) 
            FROM section_students s 
            WHERE s.grade_id = sc.grade_id
        ) AS total_students,

        (
            SELECT COUNT(*) 
            FROM lessons l 
            WHERE l.course_id = c.course_id
        ) AS total_lessons

    FROM courses c
    LEFT JOIN section_courses sc 
        ON sc.course_id = c.course_id
    LEFT JOIN grades g 
        ON g.grade_id = sc.grade_id 
        AND g.school_id = ?   -- ✅ yaha shift kiya

    WHERE c.course_id = ?
    GROUP BY c.course_id
");

$courseQ->bind_param("ii", $userSchoolId, $course_id);
$courseQ->execute();
$course = $courseQ->get_result()->fetch_assoc();
$courseQ->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo $course_name ? $course_name . ' - Manage Lesson' : 'Manage Lesson'; ?></title>
    <style>
        .lesson-order {
            position: absolute;
            top: 43%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            width: 19%;
            height: 17%;
            font-weight: bold;
            color: #fff;
            background: rgba(0, 0, 0, 0.6);
            padding: 6px 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lesson-order span {
            line-height: 1;
        }

        .locked-card {
            pointer-events: none;
            opacity: 0.6;
        }

        .lock-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #fff;
            border-radius: 10px;
        }

        /* Wrapper gives bottom gap */
        .course-summary-wrapper {
            width: 100%;
            margin-bottom: 30px;
            /* 👈 Niche ka GAP */
        }

        /* Card full width */
        .course-summary-card {
            width: 100%;
            border-radius: 12px;
        }

        /* Image styling */
        .course-thumb {

            height: 80px;

            border-radius: 8px;
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">
            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h3 class="fw-bolder me-4">
                        <?php if ($userType == STUDENT) {
                            echo $course_name ? $course_name . ' - Lessons' : 'Lessons';

                        } else {
                            echo $course_name ? $course_name . ' - Manage Lessons' : 'Manage Lessons';
                        } ?>
                    </h3>

                    <div class="d-flex align-items-center " style="gap: 9px;">

                        <a href="manage_course.php" class="btn btn-primary">Back To
                            Course</a>
                        <?php if (userHasPermission(LESSON_ADD)) { ?>
                            <button class="btn btn-primary" id="addLessonBtn">
                                <i class="fas fa-plus-circle"></i> Add Lesson
                            </button>
                        <?php } ?>

                        <?php if (userHasPermission(COURSE_SYLLABUS_ADD)) { ?>
                            <button class="btn btn-primary" id="addsyllabusBtn">
                                <i class="fas fa-plus-circle"></i> Add Syllabus
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- Filter Box -->
                <?php if (userHasPermission(LESSON_SEARCH)) { ?>
                    <div class="filter-box mb-4 ">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                    <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Lessons</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter lesson name...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                <?php } ?>
                <?php if ($userType != STUDENT) {
                    if ($course) { ?>
                        <div class="course-summary-wrapper" style="background-color: #1da1f2;border-radius: 5px;color: white;">
                            <div class="course-summary-card p-2 shadow-sm">
                                <div class="d-flex align-items-center gap-3">

                                    <!-- COURSE IMAGE -->
                                    <img src="<?= !empty($course['course_cover_page'])
                                        ? 'uploads/courses/course-' . $course_id . '/' . $course['course_cover_page']
                                        : 'images/systemimages/placeholder-image.png'; ?>" class="course-thumb ml-3"
                                        width="7%0" height="80" alt="Course Cover">

                                    <!-- COURSE INFO -->
                                    <div class="flex-grow-1 ml-3">
                                        <h4 class="fw-bold mb-1">
                                            <?= htmlspecialchars($course['course_title']); ?>
                                        </h4>

                                        <div class="d-flex flex-wrap gap-4 mt-2" style="gap:9%">
                                            <span><strong><i class="fas fa-users"></i> Students:</strong>
                                                <?= (int) $course['total_students']; ?></span>
                                            <span><strong><i class="fas fa-book"></i> Lessons:</strong>
                                                <?= (int) $course['total_lessons']; ?></span>
                                            <span><strong><i class="fas fa-star"></i> Grade:</strong>
                                                <?= htmlspecialchars($course['grade_names'] ?? 'N/A'); ?></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php }
                } ?>


                <!-- Lesson List -->
                <?php if (userHasPermission(LESSON_VIEW)) { ?>
                    <?php
                    include "config.php";

                    $search = isset($_GET['search']) ? trim($_GET['search']) : "";

                    if ($search !== "") {
                        // SEARCH QUERY
                        $search_safe = $conn->real_escape_string($search);
                        if ($userType == STUDENT) {

                            $sql = "
                                SELECT l.lesson_id, l.lesson_title, l.lesson_type, l.lesson_sort_order,l.lesson_file_path, l.lesson_plan_path,l.lesson_summary_path
                                FROM lessons l
                                INNER JOIN unlock_lessons ul 
                                    ON ul.lesson_id = l.lesson_id
                                WHERE l.course_id = $course_id
                                  AND ul.grade_id = $student_grade_id
                                  AND ul.section_id = $student_section_id
                                  AND ul.is_unlocked = 1
                                  AND l.lesson_title LIKE '%$search_safe%'
                                ORDER BY l.lesson_sort_order ASC
                            ";
                        } else {

                            $sql = "
        SELECT lesson_id, lesson_title, lesson_type,lesson_sort_order,lesson_file_path, lesson_plan_path,lesson_summary_path
        FROM lessons
        WHERE course_id = $course_id
          AND lesson_title LIKE '%$search_safe%'
        ORDER BY lesson_sort_order ASC
    ";
                        }


                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {

                            echo '<div class="row">';
                            // $count = 1;
                            while ($l = $res->fetch_assoc()) {

                                $lesson_name_lower = strtolower($l['lesson_title']);
                                $target_url = "";

                                // ---- SPECIAL PAGES ----
                                if ($lesson_name_lower == 'cultural activity') {
                                    $target_url = 'cultural-activity.php';
                                } elseif ($lesson_name_lower == 'syllabus') {
                                    $target_url = 'syllabus.php';
                                } else {
                                    $target_url = 'lesson.php';
                                }

                                // final URL with lesson ID
                                $final_url = $target_url . '?lesson_id=' . $l['lesson_id'];
                                ?>

                                <div class="col-md-3 mb-4">

                                    <div class="book-card" style="height:340px; width:279px;">
                                        <!-- ACTION ICONS (TOP RIGHT) -->
                                        <?php if (userHasPermission(LESSON_EDIT)) { ?>
                                            <?php if ($l['lesson_type'] === "lesson") { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='lesson_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } elseif ($l['lesson_type'] === "culturalActivity") { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='cultural_activity_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } else { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='syllabus_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if (userHasPermission(LESSON_MANAGE_QUIZ)) { ?>
                                            <!-- 📄 QUIZ ICON (ONLY IF lesson) -->
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                              
                                                    <i class="fas fa-question quiz-icon text-primary"
                                                        onclick="event.stopPropagation();window.location.href='manage_quiz.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>'"
                                                        title="Manage Quiz">
                                                    </i>
                                                <?php }
                                            
                                        } ?>

                                        <?php if (userHasPermission(LESSON_MANAGE_PRACTICES)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                                <i class="fas fa-file-alt pencil-icon text-primary"
                                                    onclick="event.stopPropagation(); window.location.href='manage_practices.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>'"
                                                    title="Manage Practices"></i>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (userHasPermission(LESSON_MANAGE_EXERCISE)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                              
                                                    <i class="fas fa-tasks exercise-icon text-primary" title="Manage Exercise"
                                                        onclick="event.stopPropagation(); window.location.href='manage_exercise.php?lesson_id=<?= $l['lesson_id']; ?>&lesson_name=<?= urlencode($l['lesson_title']); ?>'">
                                                    </i>
                                                <?php }
                                             ?>
                                        <?php } ?>
                                        <?php if (userHasPermission(LESSON_MANAGE_PROBLEMS)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                                <i class="fas fa-exclamation-circle problem-icon text-primary" title="Manage Problem"
                                                    onclick="event.stopPropagation(); window.location.href='manage_problem.php?lesson_id=<?= $l['lesson_id']; ?>&lesson_name=<?= urlencode($l['lesson_title']); ?>'">
                                                </i>
                                            <?php } ?>
                                        <?php } ?>




                                        <?php if (userHasPermission(LESSON_DELETE)) { ?>
                                            <i class="fas fa-trash delete-icon text-danger"
                                                onclick="event.stopPropagation(); deleteLesson(<?= $l['lesson_id']; ?>)" title="Delete"></i>
                                        <?php } ?>

                                        <!-- CENTER ICON -->
                                        <div class="lesson-icon">
                                            <i class="fas fa-book-open" style="font-size:56px"></i>
                                        </div>
                                        <!-- <div class="lesson-order">
                                                <span><?php echo $count; ?></span>
                                            </div> -->

                                        <!-- <?php $count++; ?> -->



                                        <div class="book-title" onclick="window.location.href = '<?php echo $final_url; ?>';">
                                            <?php if ($l['lesson_type'] === "lesson") { ?>
                                                <span style="padding:2px 8px; font-size:14px; margin-right:6px; display:inline-block;">
                                                    <?php echo "lesson " . $l['lesson_sort_order']; ?>
                                                </span><br>
                                            <?php } ?>
                                            <?php echo htmlspecialchars($l['lesson_title']); ?>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }

                            echo '</div>';
                        } else {
                            echo "<div class='text-center text-muted mt-4'>No lessons found.</div>";
                        }
                    } else {
                        // ---- DATABASE SE LESSON FETCH ----
                
                        if ($userType == STUDENT) {

                            // 👉 Student ko sirf unlocked lessons
                            $sql = "
                                SELECT l.lesson_id, l.lesson_title, l.lesson_type, l.lesson_sort_order,l.lesson_file_path, l.lesson_plan_path,l.lesson_summary_path
                                FROM lessons l
                                INNER JOIN unlock_lessons ul 
                                    ON ul.lesson_id = l.lesson_id
                                WHERE l.course_id = $course_id
                                  AND ul.grade_id = $student_grade_id
                                  AND ul.section_id = $student_section_id
                                  AND ul.is_unlocked = 1
                                ORDER BY l.lesson_sort_order ASC
                            ";
                        } else {

                            // 👉 Admin / Teacher ko sab lessons
                            $sql = "
        SELECT lesson_id, lesson_title, lesson_type, lesson_sort_order,lesson_file_path, lesson_plan_path,lesson_summary_path
        FROM lessons
        WHERE course_id = $course_id
        ORDER BY lesson_sort_order ASC
    ";
                        }

                        // $res = $conn->query($sql);
                
                        $res = $conn->query($sql);

                        if ($res->num_rows > 0) {

                            echo '<div class="row">';
                            // $count = 1;
                            while ($l = $res->fetch_assoc()) {

                                $lesson_name_lower = strtolower($l['lesson_type']);
                                $target_url = "";

                                // ---- SPECIAL PAGES ----
                                if ($lesson_name_lower == 'syllabus') {
                                    $target_url = 'syllabus.php';
                                } else {
                                    $target_url = 'lesson.php';
                                }

                                // final URL with lesson ID
                                $final_url = $target_url . '?lesson_id=' . $l['lesson_id'];
                                ?>

                                <div class="col-md-3 mb-4">
                                    <div class="book-card" style="height:340px; width:279px;">

                                        <!-- ACTION ICONS (TOP RIGHT) -->
                                        <?php if (userHasPermission(LESSON_EDIT)) { ?>
                                            <?php if ($l['lesson_type'] === "lesson") { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='lesson_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } elseif ($l['lesson_type'] === "culturalActivity") { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='cultural_activity_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } else { ?>
                                                <i class="fas fa-edit edit-icon text-primary"
                                                    onclick="event.stopPropagation();
                   window.location.href='syllabus_edit.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>&course_name=<?= $course_name; ?>'"
                                                    title="Edit"></i>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php if (userHasPermission(LESSON_MANAGE_QUIZ)) { ?>
                                            <!-- 📄 QUIZ ICON (ONLY IF lesson) -->
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                              
                                                    <i class="fas fa-question quiz-icon text-primary"
                                                        onclick="event.stopPropagation();window.location.href='manage_quiz.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>'"
                                                        title="Manage Quiz">
                                                    </i>
                                                <?php }
                                            
                                        } ?>

                                        <?php if (userHasPermission(LESSON_MANAGE_PRACTICES)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                                <i class="fas fa-file-alt pencil-icon text-primary"
                                                    onclick="event.stopPropagation(); window.location.href='manage_practices.php?lesson_id=<?= $l['lesson_id']; ?>&course_id=<?= $course_id; ?>'"
                                                    title="Manage Practices"></i>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if (userHasPermission(LESSON_MANAGE_EXERCISE)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                              
                                                    <i class="fas fa-tasks exercise-icon text-primary" title="Manage Exercise"
                                                        onclick="event.stopPropagation(); window.location.href='manage_exercise.php?lesson_id=<?= $l['lesson_id']; ?>&lesson_name=<?= urlencode($l['lesson_title']); ?>'">
                                                    </i>
                                                <?php 
                                            } ?>
                                        <?php } ?>
                                        <?php if (userHasPermission(LESSON_MANAGE_PROBLEMS)) { ?>
                                            <?php if ($l['lesson_type'] === 'lesson') { ?>
                                                <i class="fas fa-exclamation-circle problem-icon text-primary" title="Manage Problem"
                                                    onclick="event.stopPropagation(); window.location.href='manage_problem.php?lesson_id=<?= $l['lesson_id']; ?>&lesson_name=<?= urlencode($l['lesson_title']); ?>'">
                                                </i>
                                            <?php } ?>
                                        <?php } ?>


                                        <?php if (userHasPermission(LESSON_DELETE)) { ?>
                                            <i class="fas fa-trash delete-icon text-danger"
                                                onclick="event.stopPropagation(); deleteLesson(<?= $l['lesson_id']; ?>)" title="Delete"></i>
                                        <?php } ?>

                                        <!-- CENTER ICON -->
                                        <div class="lesson-icon">
                                            <i class="fas fa-book-open" style="font-size:56px"></i>
                                        </div>
                                        <!-- <div class="lesson-order">
                                                <span><?php echo $count; ?></span>
                                            </div> -->

                                        <!-- <?php $count++; ?> -->

                                        <!-- TITLE (UNCHANGED) -->
                                        <div class="book-title" onclick="window.location.href='<?php echo $final_url; ?>';">
                                            <?php if ($l['lesson_type'] === "lesson") { ?>
                                                <span style="padding:2px 8px; font-size:14px; margin-right:6px; display:inline-block;">
                                                    <?php echo "lesson " . $l['lesson_sort_order']; ?>
                                                </span><br>
                                            <?php } ?>
                                            <?php echo htmlspecialchars($l['lesson_title']); ?>
                                        </div>

                                    </div>
                                </div>

                                <?php
                            }

                            echo '</div>';
                        } else {
                            echo "<div class='text-center text-muted mt-4'>No lessons found.</div>";
                        }
                    }
                    ?>

                    <input type="hidden" id="courseIdJS" value="<?php echo $course_id; ?>">
                    <input type="hidden" id="courseNameJS" value="<?php echo $course_name; ?>">
                </div>
            <?php } ?>


        </div>
    </div>

    <!-- Redirect to Add Lesson -->
    <script>
        document.getElementById('addLessonBtn').addEventListener('click', () => {
            const courseId = "<?php echo $course_id; ?>";
            const courseName = "<?php echo urlencode($course_name); ?>";
            window.location.href = "lesson_add.php?course_id=" + courseId + "&course_name=" + courseName;
        });

        document.getElementById('addsyllabusBtn').addEventListener('click', () => {
            const courseId = "<?php echo $course_id; ?>";
            const courseName = "<?php echo urlencode($course_name); ?>";
            window.location.href = "syllabus_add.php?course_id=" + courseId + "&course_name=" + courseName;
        });


        function deleteLesson(id) {
            openDeletePopup(id, "lesson_delete.php", "Grade Deleted Successfully!");
        }

        // DELETE (Confirmation + Success Msg)

        let deleteID = null;
        let deleteUrl = null;
        let deleteSuccessMsg = "";

        // Open Delete Modal
        function openDeletePopup(id, apiUrl, msg) {
            deleteID = id;
            deleteUrl = apiUrl;
            deleteSuccessMsg = msg;

            let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        function confirmDelete() {

            ajaxPost(deleteUrl, {
                id: deleteID
            }, function (res) {

                if (res.trim() === "success") {
                    showMessage(deleteSuccessMsg, "success");
                    let courseId = document.getElementById("courseIdJS").value;
                    let courseName = encodeURIComponent(document.getElementById("courseNameJS").value);

                    window.location.href =
                        "manage-lesson.php?course_id=" + courseId + "&course_name=" + courseName;

                } else {
                    showMessage("Error deleting! " + res, "error");
                }

            });

            bootstrap.Modal.getInstance(
                document.getElementById('deleteConfirmModal')
            ).hide();
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let msg = "<?php echo $_SESSION['msg'] ?? ''; ?>";
            let type = "<?php echo $_SESSION['transaction_status'] ?? 'success'; ?>";

            if (msg.trim() !== "") {
                showMessage(msg, type);
            }
        });
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>
</body>

</html>