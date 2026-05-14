<?php
session_start();
include "standard_constants.php";
include "config.php";

// --------------------
// GET QUIZ ID
// --------------------
if (!isset($_GET['quiz_id'])) {
    $_SESSION['msg'] = "Invalid Request!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_generate_quiz.php");
    exit();
}

$quiz_id = intval($_GET['quiz_id']);

// --------------------
// FETCH QUIZ DATA
// --------------------
$sql = "SELECT q.quiz_title, q.book_id 
        FROM quiz q
        WHERE q.quiz_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();

if (!$quiz) {
    $_SESSION['msg'] = "Quiz not found!";
    $_SESSION['transaction_status'] = TRANSACTION_STATUS_ERROR;
    header("Location: manage_generate_quiz.php");
    exit();
}

// --------------------
// FETCH ASSIGNED GRADES + SECTIONS
// --------------------
$assigned_sections = [];
$assigned_grade = "";

$sql2 = "SELECT grade_id, section_id FROM quiz_applicable_for WHERE quiz_id = $quiz_id";
$assigned = $conn->query($sql2);
while ($a = $assigned->fetch_assoc()) {
    $assigned_grade = $a['grade_id'];
    $assigned_sections[] = $a['section_id'];
}

// --------------------
// IF GRADE SELECTED AGAIN AFTER SUBMIT
// --------------------
if (isset($_POST['grade'])) {
    $assigned_grade = $_POST['grade'];
}

// --------------------
// FETCH SECTIONS FOR SELECTED GRADE
// --------------------
$sections = [];
if (!empty($assigned_grade)) {
    $sqlS = "SELECT section_id, section_name, gender FROM sections WHERE grade_id='$assigned_grade' ORDER BY section_name ASC";
    $resultSec = $conn->query($sqlS);

    while ($sec = $resultSec->fetch_assoc()) {
        $sections[] = $sec;
    }
}

// --------------------
// FETCH BOOKS
// --------------------
$resultBooks = $conn->query("SELECT book_id, book_title FROM books ORDER BY book_title ASC");

// --------------------
// FETCH GRADES
// --------------------
$grades = $conn->query("SELECT grade_id, grade_name FROM grades ORDER BY grade_name ASC");

include "header.php";
?>
<style>
.toggle-slider:before {
    font-size: 0px !important;
}
</style>
<div class="container-fluid" style="padding: 30px;">
    <div class="layout-row">
        <div class="sidebar" id="sidebar">
            <?php include 'menus.php'; ?>
        </div>

        <div class="main-area text-dark">

            <a href="manage_generate_quiz.php" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="card shadow" style="margin-top:85px; border-radius:10px;">

                <div class="card-header" style="background-color:#fd5f00; color:white;">
                    <h4 class="m-0">Edit Exam</h4>
                </div>

                <div class="card-body">

                    <!-- FORM START -->
                    <form method="POST">

                        <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">

                        <!-- TITLE -->
                        <div class="mb-3">
                            <input type="text" name="quiz_title"
                                   value="<?= htmlspecialchars($quiz['quiz_title']) ?>"
                                   class="form-control" placeholder="Enter exam title" required>
                        </div>

                        <!-- BOOK -->
                        <div class="mb-3">
                            <label>Choose Book</label>
                            <select class="form-control" name="book_grade" required>
                                <option value="">Select book</option>
                                <?php while ($b = $resultBooks->fetch_assoc()) { ?>
                                    <option value="<?= $b['book_id'] ?>" 
                                        <?= ($quiz['book_id'] == $b['book_id']) ? "selected" : "" ?>>
                                        <?= htmlspecialchars($b['book_title']) ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- GRADE -->
                        <div class="mb-3">
                            <label>Select Grade</label>
                            <select name="grade" class="form-control" onchange="this.form.submit()" required>
                                <option value="">Select Grade</option>
                                <?php while ($g = $grades->fetch_assoc()) { ?>
                                    <option value="<?= $g['grade_id'] ?>"
                                        <?= ($assigned_grade == $g['grade_id']) ? "selected" : "" ?>>
                                        <?= $g['grade_name'] ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- SECTION TOGGLES -->
                        <?php if (!empty($assigned_grade)) { ?>
                            <label>Select Sections</label><br>

                            <?php foreach ($sections as $sec) { 
                                $checked = in_array($sec['section_id'], $assigned_sections) ? "checked" : "";
                            ?>
                                <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="section[]" 
                                               value="<?= $sec['section_id'] ?>" <?= $checked ?>>
                                        <span class="toggle-slider"></span>
                                    </label>

                                    <label><?= htmlspecialchars($sec['section_name'] . " - " . $sec['gender']) ?></label>
                                </div>
                            <?php } ?>

                            <br>
                        <?php } ?>

                        <!-- BUTTON -->
                        <button class="btn btn-primary" formaction="new_exam_update.php" type="submit" name="update_quiz">
                            <i class="fas fa-save"></i> Update Exam
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>
