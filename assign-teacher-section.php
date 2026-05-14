<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'config.php';
$school_id = $_SESSION['LoggedInSchoolId'] ?? 0;
// GET Grade & Section
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$grade = isset($_GET['grade']) ? intval($_GET['grade']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign Teacher to Section</title>

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

                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>

                

                    <?php
                    // Fetch grade + section info
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
                            Assigned Teachers
                        </h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage-section.php?grade=<?php echo $grade; ?>" class="btn btn-primary">Back To
                                Section</a>
                            <?php if (userHasPermission(ADD_TEACHER_TO_SECTION)) { ?>
                                <button class="btn btn-primary" id="addTeacherBtn">
                                    <i class="fas fa-plus-circle"></i> Add Teacher
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (userHasPermission(SECTION_TEACHER_SEARCH)) { ?>
                        <!-- Search Box -->
                        <div class="filter-box mb-4">
                            <form method="GET">
                                <input type="hidden" name="grade" value="<?= $grade ?>">
                                <input type="hidden" name="section" value="<?= $section ?>">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group" style="gap:25px;">
                                            <span class="fw-semibold">Search Teacher:</span>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Enter teacher name..."
                                                value="<?= htmlspecialchars($search) ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>

                                        <a href="assign-teacher-section.php?section=<?= $section ?>&grade=<?= $grade ?>"
                                            class="btn btn-secondary">
                                            <i class="fas fa-times-circle"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <?php
                    // Fetch Assigned Teachers
                    $sql = "
                    SELECT st.section_teacher_id, t.teacher_id, t.teacher_name
                    FROM section_teachers st
                    JOIN teachers t ON st.teacher_id = t.teacher_id
                    WHERE st.section_id = $section
                ";

                    if ($search !== "") {
                        $s = $conn->real_escape_string($search);
                        $sql .= " AND t.teacher_name LIKE '%$s%'";
                    }

                    $sql .= " ORDER BY t.teacher_name ASC";

                    $result = $conn->query($sql);
                    ?>

                    <?php if (userHasPermission(SECTION_TEACHER_LIST_VIEW)) { ?>
                        <!-- Assigned Teachers List -->
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <div class="grade-box mb-3">
                                    <div class="grade-row d-flex justify-content-between">
                                        <div><strong><?= $row['teacher_name'] ?></strong></div>

                                        <div class="action-icons">

                                            <!-- EDIT -->
                                            <!-- <i class="fas fa-edit text-primary" title="Edit Assigned Teacher"
                                            style="cursor:pointer;" onclick="editTeacher(
                                           '<?= $row['section_teacher_id'] ?>',
                                           '<?= $row['teacher_id'] ?>'
                                       )"></i> -->

                                            <!-- VIEW -->
                                            <!-- <i class="fas fa-eye text-primary" style="cursor:pointer;" title="View Teacher"
                                            onclick="viewTeacher('<?= $row['teacher_name'] ?>')"></i> -->

                                            <!-- DELETE -->
                                            <?php if (userHasPermission(REMOVE_TEACHER_FROM_SECTION)) { ?>
                                                <i class="fas fa-trash text-danger" style="cursor:pointer;"
                                                    onclick="openDeleteModal('section_teacher_delete.php?id=<?= $row['section_teacher_id'] ?>&section=<?= $section ?>&grade=<?= $grade ?>', 'Are you sure you want to remove this teacher?')"
                                                    title="Remove Teacher from Section">
                                                </i>
                                            <?php } ?>


                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">No teachers assigned.</p>
                        <?php endif; ?>
                    <?php } ?>
               

                <!-- ADD / EDIT MODAL -->
                <div class="modal fade" id="assignTeacherModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h3 id="modalTitle">Add Teacher</h3>
                                <button class="closeicon" data-bs-dismiss="modal">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                            <form method="POST" action="section_teacher_save.php" id="assignForm">
                                <div class="modal-body">
                                    <input type="hidden" name="section_teacher_id" id="section_teacher_id">
                                    <input type="hidden" name="section_id" value="<?= $section ?>">
                                    <input type="hidden" name="grade_id" value="<?= $grade ?>">

                                    <label>Select Teacher</label>
                                    <select class="form-control" name="teacher_id" id="teacher_id">
                                        <option value="">Select</option>
                                        <?php
                                       
                                        $teacherdata = $conn->prepare("
                                            SELECT t.teacher_id, t.teacher_name
                                            FROM teachers t
                                            INNER JOIN users u ON t.user_id = u.user_id
                                            WHERE u.school_id = ?
                                            ORDER BY t.teacher_name ASC
                                        ");

                                        $teacherdata->bind_param("i", $school_id);
                                        $teacherdata->execute();
                                        $tresult = $teacherdata->get_result();

                                        while ($t = $tresult->fetch_assoc()): ?>

                                            <option value="<?= $t['teacher_id'] ?>"><?= $t['teacher_name'] ?></option>
                                        <?php endwhile; ?>

                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <a href="assign-teacher-section.php?section=<?= $section ?>&grade=<?= $grade ?>"
                                        class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                                    <button type="button" class="btn btn-primary" id="saveBtn"
                                        onclick="onSaveAssignTeacher()">Save</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- VIEW MODAL -->
                <div class="modal fade" id="viewTeacherModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3>View Teacher</h3>
                                <button class="closeicon" data-bs-dismiss="modal">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label>Teacher Name:</label>
                                <input type="text" id="viewTeacherName" class="form-control" readonly>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        let teacherModal = new bootstrap.Modal(document.getElementById('assignTeacherModal'));

        // ADD
        document.getElementById('addTeacherBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Teacher";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('section_teacher_id').value = "";
            document.getElementById('teacher_id').value = "";

            teacherModal.show();
        });

        function onSaveAssignTeacher() {
            const teacher = document.getElementById("teacher_id").value.trim();

            if (teacher === "") {
                showMessage("Please select a teacher", "error");
                return;
            }

            document.getElementById("assignForm").submit();
        }


        // // EDIT
        // function editTeacher(sectionTeacherId, teacherId) {
        //     document.getElementById('modalTitle').innerText = "Edit Assigned Teacher";
        //     document.getElementById('saveBtn').innerText = "Update";

        //     document.getElementById('section_teacher_id').value = sectionTeacherId;
        //     document.getElementById('teacher_id').value = teacherId;

        //     teacherModal.show();
        // }

        // // VIEW
        // function viewTeacher(name) {
        //     document.getElementById('viewTeacherName').value = name;
        //     new bootstrap.Modal(document.getElementById('viewTeacherModal')).show();
        // }
    </script>

    <!-- SHOW SESSION MESSAGE -->
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