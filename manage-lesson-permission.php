<?php
session_start();
// include 'header.php';
include 'config.php';
$userType = $_SESSION['LoggedInUserType'] ?? '';
$loggedUserId = $_SESSION['LoggedInUserId'] ?? 0;
$grade_id = intval($_GET['grade_id'] ?? 0);
$section_id = intval($_GET['section_id'] ?? 0);
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

/* =========================
   AJAX SAVE HANDLER
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    /* UNLOCK LESSON */
    if ($_POST['action'] === 'lesson') {

        $lesson_id = intval($_POST['id']);
        $value = intval($_POST['value']);

        if ($value == 1) {
            // UNLOCK → INSERT / UPDATE
            $conn->query("
            INSERT INTO unlock_lessons (grade_id, section_id, lesson_id, is_unlocked)
            VALUES ($grade_id, $section_id, $lesson_id, 1)
            ON DUPLICATE KEY UPDATE is_unlocked = 1
        ");
            echo json_encode([
                'status' => 'success',
                'msg' => 'Lesson unlocked successfully'
            ]);
        } else {
            // LOCK → DELETE
            $conn->query("
            DELETE FROM unlock_lessons
            WHERE grade_id = $grade_id
              AND section_id = $section_id
              AND lesson_id = $lesson_id
        ");
            echo json_encode([
                'status' => 'success',
                'msg' => 'Lesson locked successfully'
            ]);
        }

        exit;
    }





    /* UNLOCK quiz */
    if ($_POST['action'] === 'quiz') {

        $quiz_id = intval($_POST['id']);
        $value = intval($_POST['value']);

        if ($value == 1) {
            $conn->query("
            INSERT INTO unlock_quiz (quiz_id, school_id, is_unlocked)
            VALUES ($quiz_id, $userSchoolId, 1)
            ON DUPLICATE KEY UPDATE is_unlocked = 1
        ");
        } else {
            $conn->query("
            DELETE FROM unlock_quiz
            WHERE quiz_id = $quiz_id 
        ");
        }

        echo json_encode([
            'status' => 'success',
            'msg' => $value ? 'Quiz unlocked successfully' : 'Quiz locked successfully'
        ]);
        exit;
    }


    /* UNLOCK quiz GRADE */
    // if ($_POST['action'] === 'quizgrade') {

    //     $quiz_id = intval($_POST['id']);
    //     $value = intval($_POST['value']);

    //     if ($value == 1) {
    //         $conn->query("
    //         INSERT INTO unlock_quiz (quiz_id, school_id, unlockquizgrade)
    //         VALUES ($quiz_id, $userSchoolId, 1)
    //         ON DUPLICATE KEY UPDATE unlockquizgrade = 1
    //     ");
    //     } else {
    //         $conn->query("
    //         UPDATE unlock_quiz
    //         SET unlockquizgrade = 0
    //         WHERE quiz_id = $quiz_id 
    //     ");
    //     }

    //     echo json_encode([
    //         'status' => 'success',
    //         'msg' => $value ? 'Quiz Grade unlocked successfully' : 'Quiz Grade locked successfully'
    //     ]);
    //     exit;
    // }
    if ($_POST['action'] === 'quizgrade') {

    $quiz_id = intval($_POST['id']);
    $value = intval($_POST['value']); // ✅ important

    $check = $conn->query("
        SELECT 1 FROM unlock_quiz 
        WHERE quiz_id = $quiz_id AND school_id = $userSchoolId
    ");

    if ($check->num_rows > 0) {
        // UPDATE
        $conn->query("
            UPDATE unlock_quiz 
            SET unlockquizgrade = $value
            WHERE quiz_id = $quiz_id AND school_id = $userSchoolId
        ");
    } else {
        // INSERT
        $conn->query("
            INSERT INTO unlock_quiz (quiz_id, school_id, unlockquizgrade)
            VALUES ($quiz_id, $userSchoolId, $value)
        ");
    }

    echo json_encode([
        'status' => 'success',
        'msg' => $value ? 'Quiz Grade unlocked successfully' : 'Quiz Grade locked successfully'
    ]);
    exit;
}


}
?>
<?php
include 'header.php';
// include 'config.php';

/* =====================
   GET SELECTED VALUES
===================== */
$grade_id = isset($_GET['grade_id']) ? intval($_GET['grade_id']) : 0;
$section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lesson Permission</title>
</head>

<body>

    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
                

                    <h3 class="fw-bolder mb-4 mt-3">Lesson Permissions</h3>
                    <div class="mb-4">
                        <!-- =====================
                        GRADE + SECTION DROPDOWN
                        ===================== -->
                        <select class="form-control w-50 mb-4" onchange="reloadPage(this.value)">
                            <option value="">Select Grade - Section</option>
                            <?php
                            // $gs = $conn->query("  SELECT g.grade_id, s.section_id, CONCAT(g.grade_name,' - ',s.section_name,' - ',s.gender) AS label FROM sections s JOIN grades g ON g.grade_id = s.grade_id ");
                            
                            if ($userType == TEACHER) {

                                // ✅ Teacher → sirf assigned grade-section
                                $stmt = $conn->prepare("
                                        SELECT DISTINCT 
                                            g.grade_id,
                                            s.section_id,
                                            CONCAT(g.grade_name,' - ',s.section_name,' - ',s.gender) AS label
                                        FROM section_teachers st
                                        INNER JOIN sections s ON s.section_id = st.section_id
                                        INNER JOIN grades g ON g.grade_id = st.grade_id
                                        INNER JOIN teachers t ON t.teacher_id = st.teacher_id
                                        WHERE t.user_id = ? and g.school_id = ?
                                    ");
                                $stmt->bind_param("ii", $loggedUserId,$userSchoolId);
                                $stmt->execute();
                                $gs = $stmt->get_result();

                            } else {

                                // ✅ Admin / Super Admin → all grade-section
                                $gs = $conn->query("
                                        SELECT 
                                            g.grade_id,
                                            s.section_id,
                                            CONCAT(g.grade_name,' - ',s.section_name,' - ',s.gender) AS label
                                        FROM sections s
                                        JOIN grades g ON g.grade_id = s.grade_id
                                        WHERE  g.school_id = $userSchoolId
                                    ");
                            }


                            while ($row = $gs->fetch_assoc()):
                                $selected = ($grade_id == $row['grade_id'] && $section_id == $row['section_id']) ? 'selected' : '';
                                ?>
                                <option value="<?= $row['grade_id'] . '_' . $row['section_id'] ?>" <?= $selected ?>>
                                    <?= $row['label'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>

                        <?php if ($grade_id && $section_id): ?>
                        </div>

                        <div class="permission-card mb-4 shadow-sm bg-white rounded-3" id="cardLessons">

                            <!-- Header Toggle -->
                            <div class="d-flex align-items-center mb-3" style="gap:100px;">
                                <h5 class="fw-bold">Unlock Lessons</h5>

                                <div class="toggle-wrap">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="checkAllLessons">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span>All Lesson</span>
                                </div>
                            </div>

                            <!-- Lesson List -->
                            <div class="row g-3">
                                <?php
                                $lastcourse = '';

                                // $lessonQ = $conn->query("SELECT l.lesson_id, l.lesson_title FROM section_courses sb JOIN lessons l ON l.course_id = sb.course_id WHERE sb.section_id = $section_id ORDER BY l.lesson_sort_order");
                                $lessonQ = $conn->query(" SELECT l.lesson_id, l.lesson_title, b.course_title 
                                FROM section_courses sb 
                                JOIN lessons l ON l.course_id = sb.course_id 
                                JOIN courses b ON b.course_id = sb.course_id 
                                WHERE sb.section_id = $section_id 
                                ORDER BY sb.course_id, l.lesson_sort_order");

                                if ($lessonQ->num_rows == 0) {
                                    echo "<div class='text-center ' style='margin-left:39%;'>No lesson found </div>";
                                }
                                while ($l = $lessonQ->fetch_assoc()):
                                    if ($lastcourse !== $l['course_title']):
                                        $lastcourse = $l['course_title'];
                                        ?>
                                        <div class="col-12">
                                            <h5 class="mt-4 text-primary fw-bolder"><?= htmlspecialchars($lastcourse) ?></h5>
                                        </div>
                                        <?php
                                    endif;

                                    $check = $conn->query(" SELECT is_unlocked FROM unlock_lessons WHERE grade_id = $grade_id  AND section_id = $section_id  AND lesson_id = {$l['lesson_id']}")->fetch_assoc();

                                    $checked = ($check && $check['is_unlocked'] == 1) ? 'checked' : '';
                                    ?>
                                    <div class="col-md-4 d-flex align-items-center p-3">

                                        <div class="toggle-wrap">
                                            <label class="mr-3 toggle-switch">
                                                <input type="checkbox" class="lesson-toggle" value="<?= $l['lesson_id'] ?>"
                                                    <?= $checked ?> onchange="saveToggle(this,'lesson')">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <span><?= htmlspecialchars($l['lesson_title']) ?></span>

                                    </div>
                                <?php endwhile; ?>
                            </div>

                        </div>
                     
                        
                        
                        <div class="permission-card mb-4 shadow-sm bg-white rounded-3" id="cardquiz">

                            <!-- Header Toggle -->
                            <div class="d-flex align-items-center mb-3" style="gap:100px;">
                                <h5 class="fw-bold">Unlock quiz</h5>

                                <div class="toggle-wrap">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="checkAllquizzes">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span>All quiz</span>
                                </div>
                            </div>

                            <!-- quiz List -->
                            <div class="row g-3">
                                <?php
                                $quizQ = $conn->query(" SELECT q.quiz_id, q.quiz_title,q.course_id FROM quiz q JOIN section_courses qaf ON q.course_id = qaf.course_id WHERE qaf.grade_id = $grade_id AND qaf.section_id = $section_id AND q.status = 'dispatch'  AND (q.school_id = 0 || q.school_id = $userSchoolId)");
                                if ($quizQ->num_rows == 0) {
                                    echo "<div class='text-center' style='margin-left:39%;'>No quiz found </div>";
                                }
                                while ($q = $quizQ->fetch_assoc()):
                                    $check = $conn->query(" SELECT is_unlocked  FROM unlock_quiz WHERE quiz_id = {$q['quiz_id']} AND school_id = $userSchoolId");

                                    $checked = '';
                                    if ($check && $row = $check->fetch_assoc()) {
                                        $checked = ($row['is_unlocked'] == 1) ? 'checked' : '';
                                    }
                                    ?>
                                    <div class="col-md-4 d-flex align-items-center p-3">

                                        <div class="toggle-wrap">
                                            <label class="mr-3 toggle-switch">
                                                <input type="checkbox" class="quiz-toggle" value="<?= $q['quiz_id'] ?>"
                                                    <?= $checked ?> onchange="saveToggle(this,'quiz')">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <span><?= htmlspecialchars($q['quiz_title']) ?></span>

                                    </div>
                                <?php endwhile; ?>
                            </div>

                        </div>
                        <div class="permission-card mb-4 shadow-sm bg-white rounded-3" id="cardquizGrades">

                            <!-- Header Toggle -->
                            <div class="d-flex align-items-center mb-3" style="gap:100px;">
                                <h5 class="fw-bold">Show quiz Grade</h5>

                                <div class="toggle-wrap">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="checkAllquizGrades">
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span>All quiz Grade</span>
                                </div>
                            </div>

                            <!-- quiz Grade List -->
                            <div class="row g-3">
                                <?php
                                $quizGradeQ = $conn->query(" SELECT q.quiz_id, q.quiz_title,q.course_id FROM quiz q JOIN section_courses qaf ON q.course_id = qaf.course_id WHERE qaf.grade_id = $grade_id AND qaf.section_id = $section_id AND q.status = 'dispatch'  AND (q.school_id = 0 || q.school_id = $userSchoolId)");
                                if ($quizGradeQ->num_rows == 0) {
                                    echo "<div class='text-center' style='margin-left:39%;'>No quiz grade found </div>";
                                }
                                while ($qg = $quizGradeQ->fetch_assoc()):
                                    $check = $conn->query("SELECT unlockquizgrade FROM unlock_quiz WHERE quiz_id={$qg['quiz_id']} AND school_id = $userSchoolId")->fetch_assoc();
                                    $checked = ($check && $check['unlockquizgrade']) ? 'checked' : '';
                                    ?>
                                    <div class="col-md-4 d-flex align-items-center p-3">

                                        <div class="toggle-wrap">
                                            <label class="mr-3 toggle-switch">
                                                <input type="checkbox" class="quiz-grade-toggle" value="<?= $qg['quiz_id'] ?>"
                                                    <?= $checked ?> onchange="saveToggle(this,'quizgrade')">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>

                                        <span><?= htmlspecialchars($qg['quiz_title']) ?></span>

                                    </div>
                                <?php endwhile; ?>
                            </div>

                        </div>
                    <?php endif; ?>
             
            </div>
        </div>
    </div>

    <script>
        function reloadPage(val) {
            if (!val) return;
            let [g, s] = val.split('_');
            location.href = "?grade_id=" + g + "&section_id=" + s;
        }

        function saveToggle(el, type) {

            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: type,
                    id: el.value,
                    value: el.checked ? 1 : 0
                })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        showMessage(data.msg, 'success'); // ✅ GLOBAL TOAST
                    }
                })
                .catch(() => {
                    showMessage('Something went wrong', 'danger');
                });
        }



        /* CHECK ALL */
        document.getElementById('checkAllLessons')?.addEventListener('change', e => {
            document.querySelectorAll('.lesson-toggle').forEach(x => {
                x.checked = e.target.checked;
                saveToggle(x, 'lesson');
            });
        });

        document.getElementById('checkAllquizzes')?.addEventListener('change', e => {
            document.querySelectorAll('.quiz-toggle').forEach(x => {
                x.checked = e.target.checked;
                saveToggle(x, 'quiz');
            });
        });

        document.getElementById('checkAllquizGrades')?.addEventListener('change', e => {
            document.querySelectorAll('.quiz-grade-toggle').forEach(x => {
                x.checked = e.target.checked;
                saveToggle(x, 'quizgrade');
            });
        });

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



