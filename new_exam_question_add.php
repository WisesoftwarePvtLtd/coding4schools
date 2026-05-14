<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['toggle_qid'])) {

    $qid = intval($_POST['toggle_qid']);
    $quiz = intval($_POST['toggle_quiz']);
    $book_id = intval($_POST['toggle_book']);
    $lesson_id = intval($_POST['toggle_lesson']);

    $isOn = isset($_POST['toggle_status']) ? 1 : 0;

    if ($isOn) {
        $check = $conn->query("SELECT 1 FROM quiz_questions WHERE quiz_id=$quiz AND question_id=$qid");
        if ($check->num_rows == 0) {

            if ($conn->query("INSERT INTO quiz_questions (quiz_id, question_id) VALUES ($quiz, $qid)")) {
                $_SESSION['msg'] = "Question added to quiz successfully!";
                 $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
            } else {
                $_SESSION['msg'] = "Failed to add question!";
                $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
            }
        }

    } else {

        if ($conn->query("DELETE FROM quiz_questions WHERE quiz_id=$quiz AND question_id=$qid")) {
            $_SESSION['msg'] = "Question removed from quiz!";
             $_SESSION['transaction_status'] = TRANSACTION_STATUS_SUCCESS;
        } else {
            $_SESSION['msg'] = "Failed to delete question!";
            $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
        }
    }

    header("Location: new_exam_question_add.php?quiz_id=$quiz&book_id=$book_id&lesson_id=$lesson_id");
    exit;
}
?>


<?php
include 'header.php';

$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$book_id = isset($_GET['book_id']) ? intval($_GET['book_id']) : 0;

// DO NOT USE intval() for lesson_id
// $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
// $lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : "0";
$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : "";

$quiz = "SELECT * FROM quiz WHERE quiz_id = $quiz_id";
$quizresult = $conn->query($quiz);
$quizdata = $quizresult->fetch_assoc();
// Fetch lessons of selected book
$lessons = [];
if ($book_id > 0) {
    $sql = "SELECT * FROM lessons WHERE book_id = $book_id ORDER BY lesson_title ASC";
    $lessons = $conn->query($sql);
}

// Fetch questions
$questions = [];
$sqlQ = "";

// Book ID check (important)
if ($book_id > 0) {

    // If ALL lessons selected
    if ($lesson_id === "0") {
        // echo "hii";
        $sqlQ = "SELECT * FROM questions 
                 WHERE book_id = $book_id 
                 ORDER BY lesson_id ASC, question_id ASC";



    }
    // If specific lesson selected
    elseif (intval($lesson_id) > 0) {

        $lesson_id = intval($lesson_id);
        // print_r($lesson_id);
        $sqlQ = "SELECT * FROM questions 
                 WHERE lesson_id = $lesson_id 
                 ORDER BY question_id ASC";
    }
}

// Run query only if SQL created
if (!empty($sqlQ)) {
    $questions = $conn->query($sqlQ);
}


function isQuizSelected($conn, $quiz_id, $qid)
{
    $sql = "SELECT 1 FROM quiz_questions 
            WHERE quiz_id=$quiz_id AND question_id=$qid 
            LIMIT 1";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Lesson</title>
    <style>
.toggle-slider:before {
    font-size: 0px !important;
}
</style>
</head>

<body>
    <div class="container-fluid" style="padding:30px;">
        <div class="layout-row">

            <div class="sidebar"><?php include 'menus.php'; ?></div>

            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
                <div class="container-fluid p-4">
                    <!-- HEADER -->
                    <div class="d-flex justify-content-between mb-3">
                        <h3 class="text-center fw-bolder"><?php echo $quizdata['quiz_title'];?> - Manage Quiz Questions</h3>
                        <a href="manage_generate_quiz.php" class="btn btn-primary">
                             Back To Manage Quiz
                        </a>
                    </div>
                  

                    <!-- BOOK STATIC + LESSON DROPDOWN -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <div class="row g-3">

                                <div class="col-md-12">
                                    <div class="input-group" style="gap:20px;">
                                        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                                        <input type="hidden" name="book_id" value="<?= $book_id ?>">
                                        <span class="fw-semibold mt-2">Filter</span>
                                        <select name="lesson_id"
                                            class="form-select w-90 me-2 bg-white border rounded shadow-sm"
                                            onchange="this.form.submit()" style="height:45px;margin:0;">
                                            <option value="" <?= ($lesson_id === "") ? 'selected' : '' ?>>Choose Lesson
                                            </option>
                                            <!-- ADD THIS -->
                                            <option value="0" <?= ($lesson_id == "0") ? 'selected' : '' ?>>All Lessons
                                            </option>
                                            <?php if ($lessons && $lessons->num_rows > 0): ?>
                                                <?php while ($lessonSelect = $lessons->fetch_assoc()): ?>
                                                    <option value="<?= $lessonSelect['lesson_id'] ?>"
                                                        <?= ($lesson_id == $lessonSelect['lesson_id']) ? 'selected' : '' ?>>
                                                        <?= $lessonSelect['lesson_title'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>

                                        </select>
                                    </div>
                                </div>



                            </div>
                        </form>
                    </div>



                    <!-- SHOW QUESTIONS -->
                    <div id="question-cards-container">

                        <?php
                        if ($book_id > 0 && $lesson_id >= 0) {


                            $result = $conn->query($sqlQ);

                            if ($result && $result->num_rows > 0) {

                                $count = 1;
                                // $lesson_id = $row['lesson_id'];
                        

                                while ($row = $result->fetch_assoc()) {

                                    // per-question variables
                                    $qid = intval($row['question_id']);
                                    $qtext = $row['question_text'];
                                    $qtype = $row['question_type'];
                                    $qimage = $row['main_image'] ?? '';

                                    // fetch type-specific data
                                    $options = [];
                                    $match_items = [];
                                    $order_items = [];
                                    $line_pairs = [];
                                    $drag_options = [];

                                    if ($qtype == "image-select" || $qtype == "click-select-image") {
                                        $res = $conn->query("SELECT * FROM options WHERE question_id=$qid ORDER BY option_key ASC");
                                        while ($r = $res->fetch_assoc())
                                            $options[] = $r;
                                    }

                                    if ($qtype == "drag-match-text-to-image") {
                                        $res = $conn->query("SELECT * FROM match_items WHERE question_id=$qid ORDER BY item_key ASC");
                                        while ($r = $res->fetch_assoc())
                                            $match_items[] = $r;
                                    }

                                    if ($qtype == "order") {
                                        $res = $conn->query("SELECT * FROM order_items WHERE question_id=$qid ORDER BY item_order ASC");
                                        while ($r = $res->fetch_assoc())
                                            $order_items[] = $r;
                                    }

                                    if ($qtype == "match-line") {
                                        $res = $conn->query("SELECT * FROM match_line_pairs WHERE question_id=$qid ORDER BY id ASC");
                                        while ($r = $res->fetch_assoc())
                                            $line_pairs[] = $r;
                                    }

                                    if ($qtype == "image-drag-drop-to-name") {
                                        $res = $conn->query("SELECT * FROM drag_options WHERE question_id=$qid ORDER BY drag_id ASC");
                                        while ($r = $res->fetch_assoc())
                                            $drag_options[] = $r;
                                    }
                                    ?>

                                    <!-- NEW CARD FOR EACH QUESTION -->
                                    <div class="card shadow mb-4">
                                        <div class="card-body">

                                            <!-- Top Row -->
                                            <div class="d-flex align-items-center justify-content-between mb-3">

                                                <span class="badge rounded-pill bg-primary px-3 py-2 fw-normal text-white">
                                                    <?= $count ?>
                                                </span>

                                                <p class="mb-0 w-100 px-3"><?= nl2br(htmlspecialchars($qtext)) ?></p>

                                                <form method="POST" style="display:inline;">

                                                    <input type="hidden" name="toggle_qid" value="<?= $qid ?>">
                                                    <input type="hidden" name="toggle_quiz" value="<?= $quiz_id ?>">
                                                    <input type="hidden" name="toggle_book" value="<?= $book_id ?>">
                                                    <input type="hidden" name="toggle_lesson" value="<?= $lesson_id ?>">

                                                    <label class="toggle-switch">
                                                        <input type="checkbox" name="toggle_status" value="1"
                                                            onchange="this.form.submit();" <?= isQuizSelected($conn, $quiz_id, $qid) ? 'checked' : '' ?>>
                                                        <span class="toggle-slider"></span>
                                                    </label>
                                                </form>



                                            </div>

                                            <!-- Question Image -->
                                            <?php if (!empty($qimage)): ?>
                                                <img src="uploads/<?= htmlspecialchars($qimage) ?>" style="width:166px; height:166px;"
                                                    class="my-2">
                                            <?php endif; ?>

                                            <hr>

                                            <!-- TYPE BASED RENDERING -->

                                            <?php if ($qtype == "image-select" || $qtype == "click-select-image"): ?>
                                                <div class="d-flex flex-wrap mb-5" style="gap:23px;">
                                                    <?php foreach ($options as $opt): ?>
                                                        <div
                                                            style="text-align:center; border:1px solid #eee; padding:10px; width:166px; height:166px;">
                                                            <img src="<?= htmlspecialchars($opt['image']) ?>"
                                                                style="width:100%; height:100%; object-fit:cover;">
                                                            <div class="mt-2"><strong><?= $opt['option_key'] ?></strong></div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; ?>


                                            <?php if ($qtype == "drag-match-text-to-image"): ?>
                                                <?php foreach ($match_items as $m): ?>
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <strong><?= $m['item_key'] ?>.</strong>
                                                        <img src="<?= $m['image'] ?>" style="width:166px; height:166px;">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>


                                            <?php if ($qtype == "order"): ?>
                                                <ol>
                                                    <?php foreach ($order_items as $o): ?>
                                                        <li><?= htmlspecialchars($o['item_text']) ?></li>
                                                    <?php endforeach; ?>
                                                </ol>
                                            <?php endif; ?>


                                            <?php if ($qtype == "match-line"): ?>
                                                <?php foreach ($line_pairs as $p): ?>
                                                    <div><strong><?= $p['left_label'] ?></strong> — <?= $p['right_label'] ?></div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>


                                            <?php if ($qtype == "image-drag-drop-to-name"): ?>
                                                <?php foreach ($drag_options as $d): ?>
                                                    <div class="d-flex align-items-center gap-3 mb-2">
                                                        <strong><?= $d['drag_label'] ?></strong>
                                                    </div>
                                                <?php endforeach; ?>
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
                            echo "<p class='text-center text-danger'>Please select Lesson.</p>";
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
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