<?php
session_start();
require_once 'config.php';
require_once 'standard_constants.php';

// defined('TRANSACTION_STATUS_SUCCESS') || define('TRANSACTION_STATUS_SUCCESS', 'success');


$user_id = $_SESSION['LoggedInUserId'];
$course_id = intval($_GET['course_id']);
$lesson_id = intval($_GET['lesson_id']);
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';
$userType = $_SESSION['LoggedInUserType'] ?? '';


$quiz_id = 0;


// SAVE / UPDATE QUIZ
if (isset($_POST['save_quiz'])) {

    $quiz_name = trim($_POST['quiz_name']);
    $quiz_id = intval($_POST['quiz_id'] ?? 0);
    if ($userType == SITEADMIN) {
        // Site admin manually set kare (agar form me diya ho)
        $school_id = intval($_POST['school_id'] ?? 0);
    } else {
        // School admin → always apna school
        $school_id = intval($userSchoolId);
    }


    if ($quiz_name !== '') {

        // ✅ UPDATE MODE
        if ($quiz_id > 0) {
            $stmt = $conn->prepare("
                UPDATE quiz 
                SET quiz_title = ? 
                WHERE quiz_id = ?
            ");
            $stmt->bind_param("si", $quiz_name, $quiz_id);
            $stmt->execute();
            $_SESSION['msg'] = "quiz updated successfully!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;

        }
        // ✅ INSERT MODE
        else {
            $stmt = $conn->prepare("
                INSERT INTO quiz (quiz_title, course_id, lesson_id, user_id, school_id)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("siiii", $quiz_name, $course_id, $lesson_id, $user_id, $school_id);
            $stmt->execute();

            $quiz_id = $stmt->insert_id;
            $_SESSION['msg'] = "quiz updated successfully!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        }

        header("Location: quiz_add.php?course_id=$course_id&lesson_id=$lesson_id&quiz_id=$quiz_id");
        exit;
    }
}


// IF QUIZ EXISTS
$quiz_title = "";
if (isset($_GET['quiz_id'])) {
    $quiz_id = intval($_GET['quiz_id']);

    // FETCH QUIZ NAME
    $stmt = $conn->prepare("SELECT quiz_title FROM quiz WHERE quiz_id = ?");
    $stmt->bind_param("i", $quiz_id);
    $stmt->execute();
    $stmt->bind_result($quiz_title);
    $stmt->fetch();
    $stmt->close();
}

?>

<?php
include 'header.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : "";
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
?>
<?php
function isQuizQuestion($conn, $quiz_id, $qid)
{
    $res = $conn->query("
        SELECT 1 FROM quiz_questions
        WHERE quiz_id=$quiz_id AND question_id=$qid
        LIMIT 1
    ");
    return ($res && $res->num_rows > 0);
}

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

                

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-1">
                        <h3 class="fw-bolder"><?= $courseName ?> - <?= $lessonName ?></h3>
                        <div class="d-flex align-items-center ">
                            <a href="manage_quiz.php?lesson_id=<?php echo $lesson_id; ?>&course_id=<?php echo $course_id; ?>"
                                class="btn btn-primary">
                                Back To Manage Quiz</a>
                            <?php if (userHasPermission(ANSWER_HIDE)) { ?>
                                <div class="d-flex justify-content-end text-right m-3">
                                    <button class="btn btn-primary" id="globalAnswerBtn" onclick="toggleAllAnswers()">
                                        Show All Answers
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="card shadow mb-4">

                        <!-- Header Same as Popup -->
                        <div class="card-header"
                            style="background-color:#1da1f2; color:white; border-radius:10px 10px 0 0;">
                            <h4 class="m-0">Add Quiz</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" class="row mb-4">
                                <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                                <div class="col-md-10">
                                    <input type="text" name="quiz_name" class="form-control"
                                        placeholder="Enter Quiz Name" value="<?= htmlspecialchars($quiz_title) ?>"
                                        required>
                                </div>

                                <div class="col-md-2 text-end">
                                    <button type="submit" name="save_quiz" class="btn btn-primary">
                                        <?= $quiz_id > 0 ? 'Update Quiz' : 'Save Quiz' ?>
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if ($quiz_id > 0): ?>
                        <?php
                        $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
                        $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

                        // include question_type and image in select
                        $query = "SELECT course_id, lesson_id, question_id, question_text, question_type,correct_answer, main_image,school_id FROM questions WHERE 1=1";

                        if ($course_id > 0) {
                            $query .= " AND course_id = $course_id";
                        }
                        if ($lesson_id > 0) {
                            $query .= " AND lesson_id = $lesson_id";
                        }

                        // ✅ IMPORTANT LINE
                    
                        $query .= " AND (school_id = 0 OR school_id = $userSchoolId)";


                        $query .= " ORDER BY question_id DESC";

                        $result = $conn->query($query);
                        ?>

                        <?php
                        if ($course_id > 0 && $lesson_id > 0) {
                            if ($result && $result->num_rows > 0) {
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {

                                    // per-question variables
                                    $qid = intval($row['question_id']);
                                    $qtext = $row['question_text'];
                                    $qtype = $row['question_type'];
                                    $qimage = isset($row['main_image']) ? $row['main_image'] : '';

                                    // fetch type-specific data
                                    // fetch options for this question
                                    $optStmt = $conn->prepare("
    SELECT *
    FROM options 
    WHERE question_id = ?
");
                                    $optStmt->bind_param("i", $qid);
                                    $optStmt->execute();
                                    $optResult = $optStmt->get_result();



                                    ?>

                                    <!-- NEW CARD FOR EACH QUESTION -->
                                    <div class="card-quiz shadow mb-4">
                                        <div class="card-body">

                                            <div class="d-flex  justify-content-between mb-3 mt-2" style="gap:23px;">

                                                <p class="mb-0 flex-grow-1 ms-3"><span
                                                        class=" bg-primary text-white font-weight-bold">&nbsp;<?= $count ?>&nbsp;</span>
                                                    &nbsp;<?= nl2br(htmlspecialchars($qtext)) ?></p>

                                                <form method="POST" action="quiz_question_toggle.php">
                                                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                                    <input type="hidden" name="question_id" value="<?= $qid ?>">
                                                   


                                                    <label class="toggle-switch">
                                                        <input type="checkbox" name="status" onchange="this.form.submit()"
                                                            <?= isQuizQuestion($conn, $quiz_id, $qid) ? 'checked' : '' ?>>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>


                                            </div>



                                            <?php if (!empty($qimage)): ?>
                                                <img src="<?= htmlspecialchars($qimage) ?>" style="max-width:100%; width "
                                                    class="my-2">
                                            <?php endif; ?>



                                            <?php if ($optResult && $optResult->num_rows > 0): ?>
                                                <div class="mt-3">




                                                    <ul class="list-group answer-box" data-visible="0">
                                                        <?php
                                                        $index = 0;
                                                        while ($opt = $optResult->fetch_assoc()):

                                                            $alphabet = chr(65 + $index); // A, B, C, D
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
                                                            $index++;
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

                    
                <?php else: ?>
                    <!-- <div class="alert alert-warning text-center">
                        Please enter and save Quiz Name to load questions.
                    </div> -->
                <?php endif; ?>



            </div> <!-- Main Area -->

        </div>
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