<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';

// GET Grade & Section
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$grade = isset($_GET['grade']) ? intval($_GET['grade']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign course to Section</title>

</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN -->
            <div class="main-area text-dark">

                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>

                

                    <?php
                    // Fetch Grade + Section Info
                    $info = $conn->query("
                    SELECT g.grade_name, s.section_name, s.gender
                    FROM sections s
                    JOIN grades g ON s.grade_id = g.grade_id
                    WHERE s.section_id = $section
                ")->fetch_assoc();
                    ?>

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">
                            <?= $info['grade_name'] ?> -
                            <?= $info['section_name'] ?> -
                            <?= $info['gender'] ?> |
                            Assigned courses
                        </h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage-section.php?grade=<?php echo $grade; ?>" class="btn btn-primary">Back To
                                Section</a>

                            <?php if (userHasPermission(ADD_COURSE_TO_SECTION)) { ?>
                                <button class="btn btn-primary" id="addcourseBtn">
                                    <i class="fas fa-plus-circle"></i> Add Course
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (userHasPermission(SECTION_COURSE_SEARCH)) { ?>

                        <!-- SEARCH BOX -->
                        <div class="filter-box mb-4">
                            <form method="GET">
                                <input type="hidden" name="grade" value="<?= $grade ?>">
                                <input type="hidden" name="section" value="<?= $section ?>">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group" style="gap:25px;">
                                            <span class="fw-semibold">Search Course:</span>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Enter course name..." value="<?= htmlspecialchars($search) ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>

                                        <a href="assign-course-section.php?section=<?= $section ?>&grade=<?= $grade ?>"
                                            class="btn btn-secondary">
                                            <i class="fas fa-times-circle"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <?php
                    // FETCH ASSIGNED courseS
                    $sql = "
                    SELECT sb.section_course_id, b.course_id, b.course_title
                    FROM section_courses sb
                    JOIN courses b ON sb.course_id = b.course_id
                    WHERE sb.section_id = $section
                        ";

                    if ($search !== "") {
                        $s = $conn->real_escape_string($search);
                        $sql .= " AND b.course_title LIKE '%$s%'";
                    }

                    $sql .= " ORDER BY b.course_title ASC";

                    $result = $conn->query($sql);
                    ?>

                    <?php if (userHasPermission(SECTION_COURSE_LIST_VIEW)) { ?>

                        <!-- ASSIGNED courseS LIST -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="grade-box mb-3">
                                    <div class="grade-row d-flex justify-content-between">

                                        <div><strong><?= $row['course_title'] ?></strong></div>

                                        <div class="action-icons">



                                            <!-- DELETE -->
                                            <?php if (userHasPermission(REMOVE_COURSE_FROM_SECTION)) { ?>
                                                <i class="fas fa-trash text-danger" style="cursor:pointer;" onclick="openDeleteModal(
                                           'section_course_delete.php?id=<?= $row['section_course_id'] ?>&section=<?= $section ?>&grade=<?= $grade ?>',
                                           'Are you sure you want to remove this course?'
                                       )" title="Remove Course from Section"></i>
                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No Course Found</p>
                        <?php endif; ?>
                    <?php } ?>
                

                <!-- ADD / EDIT MODAL -->
                <div class="modal fade" id="assigncourseModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h3 id="modalTitle">Add Course</h3>
                                <button class="closeicon" data-bs-dismiss="modal">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                            <form method="POST" action="section_course_save.php" id="assigncourseForm">

                                <div class="modal-body">

                                    <input type="hidden" name="section_course_id" id="section_course_id">
                                    <input type="hidden" name="section_id" value="<?= $section ?>">
                                    <input type="hidden" name="grade_id" value="<?= $grade ?>">

                                    <label>Select Course</label>
                                    <select class="form-control" name="course_id" id="course_id">
                                        <option value="">Select</option>

                                        <?php
                                        $courses = $conn->query("SELECT course_id, course_title FROM courses ORDER BY course_title ASC");
                                        while ($b = $courses->fetch_assoc()):
                                            ?>
                                            <option value="<?= $b['course_id'] ?>"><?= $b['course_title'] ?></option>
                                        <?php endwhile; ?>

                                    </select>

                                </div>

                                <div class="modal-footer">
                                    <a href="assign-course-section.php?section=<?= $section ?>&grade=<?= $grade ?>"
                                        class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                                    <button type="button" class="btn btn-primary" id="saveBtn"
                                        onclick="onSaveAssigncourse()">Save</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- VIEW MODAL -->
                <div class="modal fade" id="viewcourseModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3>Course Details</h3>
                                <button class="closeicon" data-bs-dismiss="modal">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label>Course Name:</label>
                                <input type="text" id="viewcourseName" class="form-control" readonly>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div> <!-- end main -->

        </div>
    </div>

    <script>
        let courseModal = new bootstrap.Modal(document.getElementById('assigncourseModal'));

        // ADD
        document.getElementById('addcourseBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Course";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('section_course_id').value = "";
            document.getElementById('course_id').value = "";

            courseModal.show();
        });

        function onSaveAssigncourse() {
            const course = document.getElementById("course_id").value.trim();

            // Validation
            if (course === "") {
                showMessage("Please select a course", "error");
                return;
            }

            // Submit the form
            document.getElementById("assigncourseForm").submit();
        }



        // // EDIT
        // function editcourse(sectioncourseId, courseId) {
        //     document.getElementById('modalTitle').innerText = "Edit Assigned course";
        //     document.getElementById('saveBtn').innerText = "Update";

        //     document.getElementById('section_course_id').value = sectioncourseId;
        //     document.getElementById('course_id').value = courseId;

        //     courseModal.show();
        // }

        // // VIEW
        // function viewcourse(name) {
        //     document.getElementById('viewcourseName').value = name;
        //     new bootstrap.Modal(document.getElementById('viewcourseModal')).show();
        // }
    </script>

    <!-- SESSION MESSAGE -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let msg = "<?= $_SESSION['msg'] ?? '' ?>";
            let type = "<?= $_SESSION['transaction_status'] ?? 'success' ?>";

            if (msg.trim() !== "") {
                showMessage(msg, type);
            }
        });
    </script>

    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>

</body>

</html>