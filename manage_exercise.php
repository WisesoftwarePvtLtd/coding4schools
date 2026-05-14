<?php
session_start();
include 'header.php';
include 'config.php';
$user_id = $_SESSION['LoggedInUserId'] ?? 0;

$lesson_id = intval($_GET['lesson_id'] ?? 0);
if ($lesson_id <= 0)
    die("Invalid lesson");

// LESSON NAME + COURSE ID
$lesson = $conn->query("
    SELECT lesson_title, course_id 
    FROM lessons 
    WHERE lesson_id = $lesson_id
")->fetch_assoc();

$lesson_name = $lesson['lesson_title'] ?? '';
$course_id = $lesson['course_id'] ?? 0;

$userType = $_SESSION['LoggedInUserType'] ?? '';

// SEARCH
$search = trim($_GET['search'] ?? '');
$like = "%$search%";

// FETCH EXERCISES
$stmt = $conn->prepare("
    SELECT exercise_id, exercise_name, user_id, exercise_sort_order
    FROM exercises
    WHERE lesson_id = ?
    AND exercise_name LIKE ?
    ORDER BY exercise_sort_order ASC
");
$stmt->bind_param("is", $lesson_id, $like);
$stmt->execute();
$exercises = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Exercises</title>
</head>

<body>

    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN AREA -->
            <div class="main-area text-dark">
                <div id="globalMsg" class="global-msg"></div>

                

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">
                            <?= htmlspecialchars($lesson_name) ?> – Manage Exercises
                        </h3>

                        <div class="d-flex gap-3">
                            <button class="btn btn-primary"
                                onclick="window.location.href='manage-lesson.php?course_id=<?= $course_id ?>'">
                                <i class="fas fa-arrow-left"></i> Back
                            </button>

                            &nbsp;&nbsp;&nbsp;

                            <button class="btn btn-primary"
                                onclick="window.location.href='add_exercise.php?lesson_id=<?= $lesson_id ?>'">
                                <i class="fas fa-plus-circle"></i> Add Exercise
                            </button>
                        </div>
                    </div>

                    <!-- FILTER BOX (same as grades) -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Exercise</span>
                                        <input type="text" name="search" class="form-control"
                                            value="<?= htmlspecialchars($search) ?>"
                                            placeholder="Enter exercise name...">
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="manage_exercise.php?lesson_id=<?= $lesson_id ?>" class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- EXERCISE LIST (same card layout as grades) -->

                    <?php if ($exercises->num_rows == 0) { ?>
                        <div class="col-12 text-center text-muted">
                            No exercises found
                        </div>
                    <?php } ?>

                    <?php while ($row = $exercises->fetch_assoc()) { ?>
                        <div class=" mb-3">
                            <div class="grade-box">
                                <div class="grade-row">



                                    <div>
                                        <strong>
                                            Exercise <?= htmlspecialchars($row['exercise_sort_order']) ?>
                                        </strong>
                                    </div>

                                    <div class="action-icons">

                                        <!-- <a href="manage_instruction.php?exercise_id=<?= $row['exercise_id'] ?>">
                                            <i class="fas fa-list text-primary"  data-bs-toggle="tooltip" title="Manage Instruction"></i> 
                                        </a> -->
                                        <?php if (
                                            $userType == SUPERADMIN || $userType == SITEADMIN ||
                                            ($userType == TEACHER && $user_id == $row['user_id'])
                                        ) { ?>
                                            <a
                                                href="edit_exercise.php?exercise_id=<?= $row['exercise_id'] ?>&lesson_id=<?= $lesson_id ?>">
                                                <i class="fas fa-edit text-primary" data-bs-toggle="tooltip"
                                                    title="Edit Exercise"></i>
                                            </a>
                                            <a>
                                                <i class="fas fa-trash text-danger"
                                                    onclick="deleteExercise(<?= $row['exercise_id'] ?>)"
                                                    data-bs-toggle="tooltip" title="Delete Exercise"></i>
                                            </a>

                                        <?php } ?>

                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>


               
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="mb-0">Confirm Delete</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure you want to delete this exercise?
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        let deleteId = null;

        function deleteExercise(id) {
            deleteId = id;
            new bootstrap.Modal(
                document.getElementById("deleteConfirmModal")
            ).show();
        }

        function confirmDelete() {
            $.post("delete_exercise.php", { id: deleteId }, function (res) {
                if (res.trim() === "success") {
                    location.reload();
                } else {
                    alert(res);
                }
            });
        }
    </script>
    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>

</body>

</html>