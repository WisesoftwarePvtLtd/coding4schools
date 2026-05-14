<?php
session_start();
include 'config.php';
require_once 'standard_constants.php';


// defined('TRANSACTION_STATUS_SUCCESS') || define('TRANSACTION_STATUS_SUCCESS', 'success');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['toggle_qid'])) {

    $qid = intval($_POST['toggle_qid']);
    $course = intval($_POST['toggle_course']);
    $lesson = intval($_POST['toggle_lesson']);

    // ON हुआ है → checkbox value = 1
    $isOn = isset($_POST['toggle_status']) ? 1 : 0;

    if ($isOn) {
        // INSERT
        $check = $conn->query("
            INSERT INTO lesson_practices (course_id, lesson_id, question_id)
            VALUES ($course, $lesson, $qid)
        ");
        if ($check) {
            $_SESSION['msg'] = "Question added to practice successfully!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        } else {
            $_SESSION['msg'] = "Failed to add question!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        }
    } else {
        // DELETE
        $delete = $conn->query("
            DELETE FROM lesson_practices 
            WHERE course_id=$course AND lesson_id=$lesson AND question_id=$qid
        ");
        if ($delete) {
            $_SESSION['msg'] = "Question removed from practice successfully!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        } else {
            $_SESSION['msg'] = "Failed to remove question!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        }
    }

    // Avoid form resubmission on refresh
    header("Location: manage_practices.php?course_id=$course&lesson_id=$lesson");
    exit;
}
function isPractice($conn, $course_id, $lesson_id, $qid)
{
    $sql = "SELECT lesson_practice_id FROM lesson_practices 
            WHERE course_id=$course_id AND lesson_id=$lesson_id AND question_id=$qid LIMIT 1";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}
?>
<?php

include 'header.php';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 20;
$lessonName = "";

$stmt = $conn->prepare("SELECT lesson_title FROM lessons WHERE lesson_id = ?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$stmt->bind_result($lessonName);
$stmt->fetch();
$stmt->close();
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 3;
$courseName = "";

$stmt = $conn->prepare("SELECT course_title FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($courseName);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Lesson </title>
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





                <div class="d-flex justify-content-between align-items-center mb-3 mt-0">
                    <h3 class="fw-bolder"><?= $lessonName ?> - <?= $courseName ?> - Manage Practices</h3>

                    <div class="d-flex align-items-center ">


                        <a href="manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= urlencode($courseName) ?>"
                            class="btn btn-primary">
                            Back To Lesson</a>
                        <?php if (userHasPermission(ANSWER_HIDE)) { ?>
                            <div class="d-flex justify-content-end text-right m-3">
                                <button class="btn btn-primary" id="globalAnswerBtn" onclick="toggleAllAnswers()">
                                    Show All Answers
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <?php
                $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
                $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

                // include question_type and image in select
                // $query = " SELECT question_id, question_text, main_image FROM questions WHERE course_id = ? AND lesson_id = ? ORDER BY question_id DESC";
                // $query .= " AND (school_id = 0 || school_id = $userSchoolId)";
                
                // $stmt = $conn->prepare($query);
                // $stmt->bind_param("ii", $course_id, $lesson_id);
                // $stmt->execute();
                // $result = $stmt->get_result();
                
                $query = "
    SELECT question_id, question_text, main_image 
    FROM questions 
    WHERE course_id = ? 
    AND lesson_id = ?
    AND (school_id = 0 OR school_id = ?)
    ORDER BY question_id DESC
";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("iii", $course_id, $lesson_id, $userSchoolId);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <?php
                if ($course_id > 0 && $lesson_id > 0) {
                    if ($result && $result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {

                            // per-question variables
                            $qid = intval($row['question_id']);
                            $qtext = $row['question_text'];

                            $qimage = isset($row['main_image']) ? $row['main_image'] : '';

                            // fetch options for this question
                            $optStmt = $conn->prepare(" SELECT * FROM options WHERE question_id = ?");
                            $optStmt->bind_param("i", $qid);
                            $optStmt->execute();
                            $optResult = $optStmt->get_result();

                            ?>


                            <!-- NEW CARD FOR EACH QUESTION -->
                            <div class="card-practices shadow mb-4">
                                <div class="card-body">

                                    <div class="d-flex  justify-content-between mb-3 mt-2" style="gap:23px;">


                                        <p class="mb-0 flex-grow-1 ms-3">
                                            <span class=" bg-primary text-white font-weight-bold">&nbsp;<?= $count ?>&nbsp;</span>
                                            &nbsp;<?= nl2br(htmlspecialchars($qtext)) ?>
                                        </p>

                                        <form method="POST">
                                            <input type="hidden" name="toggle_qid" value="<?= $qid ?>">
                                            <input type="hidden" name="toggle_course" value="<?= $course_id ?>">
                                            <input type="hidden" name="toggle_lesson" value="<?= $lesson_id ?>">

                                            <label class="toggle-switch">
                                                <input type="checkbox" name="toggle_status" value="1" onchange="this.form.submit();"
                                                    <?= isPractice($conn, $course_id, $lesson_id, $qid) ? 'checked' : '' ?>>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </form>
                                    </div>

                                    <?php if (!empty($qimage)): ?>
                                        <img src="<?= htmlspecialchars($qimage) ?>" style="max-width:100%; "
                                            class="mt-2">
                                    <?php endif; ?>
                                    <?php if ($optResult && $optResult->num_rows > 0): ?>
                                        <div class="mt-3">
                                            <ul class="list-group answer-box" data-visible="0">
                                                <?php
                                                $index = 0; // Counter start
                                                while ($opt = $optResult->fetch_assoc()):

                                                    $alphabet = chr(65 + $index); // 65 = 'A'
                                                    ?>

                                                    <li
                                                        class="list-group-item option-item <?= $opt['is_correct'] ? 'correct-option' : '' ?>">
                                                        <strong><?= $alphabet ?>.</strong>
                                                        <?= htmlspecialchars($opt['label']) ?>
                                                        <?php if (!empty($opt['image'])): ?>
                                                            <img src="<?= htmlspecialchars($opt['image']) ?>"
                                                                style="max-width:100%; border-radius:10px;" class="mt-2">
                                                        <?php endif; ?>

                                                    </li>
                                                    <?php
                                                    $index++; // Increase counter
                                                endwhile;
                                                ?>

                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            <?php
                            $count++;
                        }
                    } else {
                        echo "<p class='text-center text-danger'>No questions found.</p>";
                    }
                } else {
                    echo "<p class='text-center text-danger'>Please select course and Lesson, then click Search.</p>";
                }
                ?>

            </div>



        </div> <!-- Main Area -->

    </div>



    <script>
        /**
         * Shows or hides the question cards based on the selected lesson.
         */
        function showQuestions() {
            const selectElement = document.getElementById('lessonSelect');
            const questionsContainer = document.getElementById('question-cards-container');

            if (selectElement.value !== "") {
                questionsContainer.style.display = 'block';
            } else {
                questionsContainer.style.display = 'none';
            }
        }

        // Function to load lessons
        function loadLessons() {
            let courseId = document.getElementById("courseSelect").value;

            if (courseId === "") {
                document.getElementById("lessonSelect").innerHTML = '<option value="">Select Lesson</option>';
                return;
            }

            fetch("lesson_get.php?course_id=" + courseId)
                .then(response => response.json())
                .then(data => {
                    let lessonSelect = document.getElementById("lessonSelect");
                    lessonSelect.innerHTML = '<option value="">Select Lesson</option>';

                    data.forEach(item => {
                        lessonSelect.innerHTML += `<option value="${item.lesson_id}">${item.lesson_title}</option>`;
                    });
                })
                .catch(error => console.error("Error loading lessons:", error));
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
    <style>
        .toggle-slider:before {
            font-size: 0px !important;
        }

        .correct-bg {
            background-color: #9ff0a0 !important;
            color: #000 !important;
        }
    </style>

    <script>


        let allVisible = false;

        function toggleAllAnswers() {

            const btn = document.getElementById("globalAnswerBtn");
            const allBoxes = document.querySelectorAll(".answer-box");

            if (allVisible) {
                // 🔴 HIDE ALL
                btn.innerText = "Show All Answers";

                allBoxes.forEach(box => {
                    box.querySelectorAll(".correct-option").forEach(el => {
                        el.classList.remove("correct-bg");
                    });

                    box.querySelectorAll(".correct-badge").forEach(el => {
                        el.classList.add("d-none");
                    });
                });

                allVisible = false;

            } else {
                // 🟢 SHOW ALL
                btn.innerText = "Hide All Answers";

                allBoxes.forEach(box => {
                    box.querySelectorAll(".correct-option").forEach(el => {
                        el.classList.add("correct-bg");
                    });

                    box.querySelectorAll(".correct-badge").forEach(el => {
                        el.classList.remove("d-none");
                    });
                });

                allVisible = true;
            }
        }

    </script>

</body>

</html>