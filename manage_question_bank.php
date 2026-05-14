<?php
session_start();
include 'header.php';
include 'config.php';
$user_id = $_SESSION['LoggedInUserId'] ?? 0;
// print_r($_SESSION['UserPermissions']);
$userType = $_SESSION['LoggedInUserType'] ?? '';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

if ($userSchoolId == "") {
    $userSchoolId = 0;
}

?>
<?php
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$page = max($page, 1);

// $offset = ($page - 1) * $limit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Question Bank</title>
    <style>
        .pagination {
    flex-wrap: wrap;
}
        </style>
    <script>

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



                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h3 class="fw-bolder">Manage Question Bank</h3>
                    <?php if (userHasPermission(QUESTION_ADD)) { ?>

                        <a href="question_add.php" class="btn btn-primary" id="addQuestionBtn">
                            <i class="fas fa-plus-circle"></i> Add Question
                        </a>
                    <?php } ?>

                </div>

                <!-- Filter Box -->
                <!-- Filter Box -->
                <div class="filter-box mb-4 ">
                    <form method="GET">
                        <div class="row g-3">

                            <!-- Search Text -->
                            <div class="col-md-3">
                                <!-- <div class="input-group" style="gap:25px;"> -->
                                <label class="fw-semibold">Search Question</label>
                                <input type="text" name="search" class="form-control" placeholder="Enter keyword..."
                                    value="">
                                <!-- </div> -->
                            </div>

                            <!-- course DROPDOWN -->
                            <div class="col-md-3">
                                <label class="fw-semibold">Select course</label>
                                <select name="course_id" class="form-control" id="courseSelect" onchange="loadLessons()"
                                    required>
                                    <option value="">Select course</option>
                                    <?php
                                    $courseQuery = $conn->query("SELECT course_id, course_title FROM courses ORDER BY course_title ASC");
                                    while ($bk = $courseQuery->fetch_assoc()) {
                                        echo "<option value='{$bk['course_id']}'>{$bk['course_title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- LESSON DROPDOWN -->
                            <div class="col-md-3">
                                <label class="fw-semibold">Select Lesson</label>
                                <select name="lesson_id" class="form-control" id="lessonSelect" required>
                                    <option value="">Select Lesson</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="col-md-2 d-flex align-items-end" style="gap:10px;">
                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i> Search
                                </button>

                                <a href="manage_question_bank.php" class="btn btn-secondary">
                                    <i class="fas fa-times-circle"></i> Clear
                                </a>
                            </div>

                        </div>
                    </form>
                </div>

                <?php

                // APPLY FILTERS
                $search = isset($_GET['search']) ? trim($_GET['search']) : "";
                $course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
                $lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;


                $offset = ($page - 1) * $limit;
                $where = "";

                // SITE ADMIN
                if ($userType == SITEADMIN) {

                    // 👉 sirf selected school + global
                    $where .= " AND (q.school_id = 0 OR q.school_id = $userSchoolId)";

                } else {

                    // 👉 school user: apna school + global
                    $where .= " AND (q.school_id = 0 OR q.school_id = $userSchoolId)";
                }



                $countQuery = " SELECT COUNT(*) as total FROM questions q WHERE 1=1" . $where;

                if ($search !== "") {
                    $countQuery .= " AND question_text LIKE '%" . $conn->real_escape_string($search) . "%'";
                }
                if ($course_id > 0) {
                    $countQuery .= " AND course_id = $course_id";
                }
                if ($lesson_id > 0) {
                    $countQuery .= " AND lesson_id = $lesson_id";
                }

                $totalResult = $conn->query($countQuery)->fetch_assoc();
                $totalRows = $totalResult['total'];
                $totalPages = ceil($totalRows / $limit);




                $query = "SELECT q.question_id, q.question_text, q.user_id FROM questions q WHERE 1=1 " . $where;

                // Search filter
                if ($search !== "") {
                    $query .= " AND question_text LIKE '%" . $conn->real_escape_string($search) . "%'";
                }

                // course filter (apply only when course > 0)
                if ($course_id > 0) {
                    $query .= " AND course_id = $course_id";
                }

                // Lesson filter (apply only when lesson > 0)
                if ($lesson_id > 0) {
                    $query .= " AND lesson_id = $lesson_id";
                }

                $query .= " ORDER BY question_id DESC LIMIT $limit OFFSET $offset";

                $result = $conn->query($query);

                ?>



                <!-- Question List -->
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <div class="grade-box mb-3">
                            <div class="grade-row d-flex justify-content-between align-items-center">

                                <div style="max-width: 93%;">
                                    <strong><?php echo htmlspecialchars($row['question_text']); ?></strong><br>

                                </div>

                                <div class="action-icons">
                                    <?php
                                    if ($userType == SITEADMIN || $user_id == $row['user_id']) {
                                        ?>
                                        <?php if (userHasPermission(QUESTION_EDIT)) { ?>

                                            <!-- Edit -->
                                            <i class="fas fa-edit text-primary" style="cursor:pointer;"
                                                onclick="window.location='question_edit.php?id=<?php echo $row['question_id']; ?>'"
                                                title="Edit Question">
                                            </i>
                                            <?php
                                        } ?>

                                        <?php if (userHasPermission(QUESTION_DELETE)) {
                                            ?>
                                            <!-- Delete -->
                                            <i class="fas fa-trash text-danger" style="cursor:pointer;"
                                                onclick="openDeleteModal('question_delete.php?id=<?php echo $row['question_id']; ?>', 'Are you sure you want to delete this question?')"
                                                title="Delete Question">
                                            </i>
                                            <?php
                                        } ?>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                    <?php endwhile; ?>
                    <?php if ($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination justify-content-center mt-4">

                                <!-- Previous -->
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">
                                            Prev
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <!-- <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?> -->

                                <?php
$start = max(1, $page - 2);
$end = min($totalPages, $page + 2);

if ($start > 1) {
    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
    if ($start > 2) {
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
}

for ($i = $start; $i <= $end; $i++):
?>
    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
            <?= $i ?>
        </a>
    </li>
<?php endfor;

if ($end < $totalPages) {
    if ($end < $totalPages - 1) {
        echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $totalPages])) . '">' . $totalPages . '</a></li>';
}
?>

                                <!-- Next -->
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">
                                            Next
                                        </a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </nav>

                    <?php endif; ?>


                <?php else: ?>
                    <div class='text-center'>No questions found.</div>
                <?php endif; ?>
                <form method="GET" class="d-flex align-items-center mt-3 justify-content-center">

                    <!-- preserve filters -->
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                    <input type="hidden" name="course_id" value="<?= $course_id ?>">
                    <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                    <input type="hidden" name="page" value="1"> <!-- reset page -->

                    <label class="fw-semibold me-2">Records per page:</label>
                    <select name="limit" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $limit == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $limit == 100 ? 'selected' : '' ?>>100</option>
                    </select>

                </form>





            </div> <!-- Main Area -->

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