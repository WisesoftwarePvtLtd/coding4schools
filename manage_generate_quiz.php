<?php
session_start();
include 'header.php';
include 'config.php';
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Exams | Chinese for Schools</title>
    <style>
        /* ===============================
   THEME VARIABLES
================================ */
        :root {
            --primary: #1da1f2;
            --primary-hover: #0d8ddb;
            --bg-light: #f5f7fb;
            --text-dark: #1f2937;
            --border-light: #e5e7eb;
        }

        /* ===============================
   PAGE BASE
================================ */
        body {
            background: var(--bg-light);
            color: var(--text-dark);
            font-family: "Segoe UI", sans-serif;
        }

        /* ===============================
   HEADINGS
================================ */
        h3,
        h4,
        h5 {
            color: var(--text-dark);
            font-weight: 700;
        }

        /* ===============================
   BUTTONS (ALL)
================================ */
        .btn {
            border-radius: 6px;
            font-weight: 600;
        }

        .btn-primary,
        .btn-success {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #fff !important;
        }

        .btn-primary:hover,
        .btn-success:hover {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
        }

        .btn-secondary {
            background-color: #6b7280 !important;
            border-color: #6b7280 !important;
            color: #fff !important;
        }

        .btn-secondary:hover {
            background-color: #4b5563 !important;
            border-color: #4b5563 !important;
        }

        /* ===============================
   FILTER BOX
================================ */
        .filter-box {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid var(--border-light);
        }

        /* ===============================
   INPUTS & SELECT
================================ */
        .form-control {
            border-radius: 6px;
            border: 1px solid var(--border-light);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(29, 161, 242, 0.25);
        }

        /* ===============================
   GRADE CARD (FETCH PAGE)
================================ */
        .grade-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 16px;
            transition: transform 0.2s ease;
        }

        .grade-card:hover {
            transform: translateY(-4px);
        }

        /* ===============================
   CARD ACTION ICONS
================================ */
        .card-actions a {
            background: #fff;
            color: var(--primary);
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        .card-actions a.delete {
            color: #ef4444;
        }

        /* ===============================
   MODAL
================================ */
        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .modal-header {
            background-color: var(--primary) !important;
            color: #fff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .closeicon {
            background: none;
            border: none;
            color: #fff;
        }

        /* ===============================
   MODAL FOOTER
================================ */
        .modal-footer {
            border-top: 1px solid var(--border-light);
        }

        /* ===============================
   GLOBAL MESSAGE
================================ */
        .global-msg {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        /* ===============================
   ICON COLORS
================================ */
        .fa-plus-circle,
        .fa-search {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>


                <div class="d-flex justify-content-between mb-3">
                    <h3 class="fw-bolder">Manage Quiz</h3>
                    <a href="new_exam_add.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Quiz
                    </a>
                </div>
                <div class="filter-box mb-4">
                    <form method="GET">
                        <div class="row g-3">

                            <div class="col-md-4">
                                <div class="input-group" style="gap:20px;">
                                    <span class="fw-semibold">Search Quiz</span>
                                    <input type="text" name="search" class="form-control" value="<?php echo $search; ?>"
                                        placeholder="Enter quiz title...">
                                </div>
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                &nbsp;
                                <a href="manage_generate_quiz.php" class="btn btn-secondary">
                                    <i class="fas fa-times-circle"></i> Clear
                                </a>
                            </div>

                        </div>
                    </form>
                </div>



                <?php


                $sql = "SELECT   q.quiz_id, q.quiz_title, q.status,  b.book_title, b.book_id, g.grade_name, s.section_name, s.gender,qaf.quiz_date FROM quiz q  LEFT JOIN books b ON q.book_id = b.book_id   LEFT JOIN quiz_applicable_for qaf ON q.quiz_id = qaf.quiz_id  LEFT JOIN grades g ON qaf.grade_id = g.grade_id LEFT JOIN sections s ON qaf.section_id = s.section_id  WHERE 1=1";

                if ($search !== "") {
                    $safe = $conn->real_escape_string($search);
                    $sql .= " AND q.quiz_title LIKE '%$safe%'";
                }

                $sql .= " ORDER BY q.quiz_id";

                $result = $conn->query($sql);

                $result = $conn->query($sql);

                // ARRAY FOR GROUPING
                $quizzes = [];

                while ($row = $result->fetch_assoc()) {

                    $qid = $row['quiz_id'];

                    if (!isset($quizzes[$qid])) {
                        // QUIZ MAIN DETAILS ONCE
                        $quizzes[$qid] = [
                            'quiz_id' => $row['quiz_id'],
                            'quiz_title' => $row['quiz_title'],
                            'book_title' => $row['book_title'],
                            'book_id' => $row['book_id'],
                            'status' => $row['status'],
                            'grade_name' => $row['grade_name'],
                            'quiz_date' => $row['quiz_date'],
                            'sections' => []
                        ];
                    }

                    // ADD MULTIPLE SECTIONS
                    $quizzes[$qid]['sections'][] = $row['section_name'] . ' - ' . $row['gender'];
                }
                ?>

                <?php
                foreach ($quizzes as $row):
                    ?>

                    <div class="grade-box mb-3">

                        <div class="grade-row d-flex justify-content-between">
                            <a
                                href="new_exam_question_add.php?quiz_id=<?= $row['quiz_id']; ?>&book_id=<?= $row['book_id'] ?>">
                                <div>
                                    <strong class="text-dark">
                                        <?= htmlspecialchars($row['quiz_title']); ?> |
                                        <?= htmlspecialchars($row['book_title']); ?>
                                    </strong>
                                </div>

                                <div class="exam-card-details">
                                    Grade: <?= htmlspecialchars($row['grade_name']); ?> |
                                    Section: <?= implode(", ", $row['sections']); ?><br>
                                    Date: <?= htmlspecialchars($row['quiz_date']); ?>
                                </div>
                            </a>
                            <div class="action-icons">

                                <!-- Add Question -->
                                <i class="fas fa-plus-circle text-primary" title="Add Question"
                                    onclick="window.location.href='new_exam_question_add.php?quiz_id=<?= $row['quiz_id']; ?>&book_id=<?= $row['book_id'] ?>'">
                                </i>

                                <!-- Edit -->
                                <i class="fas fa-edit text-primary" title="Edit"
                                    onclick="window.location.href='new_exam_edit.php?quiz_id=<?= $row['quiz_id']; ?>'">
                                </i>

                                <!-- Download -->
                                <i class="fas fa-download text-primary" title="Download"></i>

                                <!-- Delete -->
                                <i class="fas fa-trash text-danger" title="Delete"
                                    onclick="openDeleteModal('new_exam_delete.php?id=<?= $row['quiz_id']; ?>')">
                                </i>

                                <?php
                                $quiz_id = $row['quiz_id'];

                                // Check if questions assigned
                                $check = $conn->query("SELECT COUNT(*) AS cnt FROM quiz_questions WHERE quiz_id=$quiz_id");
                                $hasQuestions = $check->fetch_assoc()['cnt'] > 0;

                                if ($hasQuestions):
                                    $disabled = ($row['status'] == 'dispatch') ? 'disabled style="pointer-events:none;opacity:0.5;"' : '';
                                    ?>
                                    <a class="btn btn-primary" href="dispatch_quiz.php?quiz_id=<?= $quiz_id ?>" <?= $disabled ?>>
                                        Dispatch Quiz
                                    </a>
                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>


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