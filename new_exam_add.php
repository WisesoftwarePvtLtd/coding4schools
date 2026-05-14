<?php
session_start();
include "standard_constants.php";
include "config.php";

// -------------------
// GET SELECTED GRADE
// -------------------
$selected_grade = "";
$sections = [];

if (isset($_POST['grade'])) {
    $selected_grade = $_POST['grade'];

    // Fetch sections for selected grade
    $sql = "SELECT section_id, section_name ,gender
            FROM sections 
            WHERE grade_id = '$selected_grade' 
            ORDER BY section_name ASC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
}

// Fetch books
$sqlBooks = "SELECT book_id, book_title FROM books ORDER BY book_title ASC";
$resultBooks = $conn->query($sqlBooks);
?>

<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New Exam</title>
</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark mt-4">

                <div class="text-right">
                    <a href="manage_generate_quiz.php" class="btn btn-primary" > Back To Manage Quiz</a>
                </div>

                <div class="card shadow" style="margin-top:20px;border-radius:10px;">

                    <!-- Header -->
                    <div class="card-header" style="background-color:#fd5f00; color:white; border-radius:10px 10px 0 0;">
                        <h4 class="m-0">Add New Exam</h4>
                    </div>

                    <div class="card-body">

                        <!-- FORM START -->
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row align-items-end">

                                <!-- EXAM NAME -->
                                <div class="col-md-12 col-12 mb-3">
                                    <label class="form-label visually-hidden">Exam Name</label>
                                    <input type="text" name="new_exam" class="form-control"
                                        placeholder="Enter new exam" required>
                                </div>

                                <!-- BOOK -->
                                <div class="col-md-12 col-12 mb-3">
                                    <label class="form-label visually-hidden">Choose the book</label>
                                    <select class="form-control" name="book_grade" required>
                                        <option value="" disabled selected>Choose the book</option>
                                        <?php
                                        if ($resultBooks->num_rows > 0) {
                                            while ($row = $resultBooks->fetch_assoc()) {
                                                echo '<option value="' . $row['book_id'] . '">' . htmlspecialchars($row['book_title']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="">No books available</option>';
                                        }
                                        ?>
                                    </select>

                                    <br>

                                    <!-- GRADE -->
                                    <label>Select Grade</label>
                                    <select name="grade" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Grade</option>

                                        <?php
                                        $grades = $conn->query("SELECT grade_id, grade_name FROM grades ORDER BY grade_name ASC");
                                        while ($g = $grades->fetch_assoc()) {
                                            $sel = ($selected_grade == $g['grade_id']) ? "selected" : "";
                                            echo "<option value='{$g['grade_id']}' $sel>{$g['grade_name']}</option>";
                                        }
                                        ?>
                                    </select>

                                    <br>

                                    <!-- SECTION TOGGLES -->
                                    <?php if ($selected_grade != "") { ?>
                                        <label>Select Sections</label><br>

                                        <?php foreach ($sections as $sec) { ?>
                                            <div class="toggle-wrap" style="margin-bottom:8px; display:flex; align-items:center; gap:10px;">
                                                <label class="toggle-switch">
                                                    <input type="checkbox" name="section[]" value="<?= $sec['section_id'] ?>">
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <label><?= htmlspecialchars($sec['section_name'] . ' - ' . $sec['gender']) ?></label>
                                            </div>
                                        <?php } ?>

                                    <?php } ?>

                                </div>

                                <!-- SUBMIT BUTTON -->
                                <div class="col-md-3 col-12 mb-3">
                                    <button class="btn btn-primary" type="submit" formaction="new_exam_save.php">
                                        <i class="fas fa-save"></i> Generate Exam
                                    </button>
                                </div>

                            </div>
                        </form>
                        <!-- FORM END -->

                    </div>

                </div>

            </div>
        </div>
    </div>

<style>
.toggle-slider:before {
    font-size: 0px !important;
}
</style>
</body>

</html>
