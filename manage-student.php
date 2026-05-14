<?php
session_start();
include 'header.php';
include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Student </title>
    <script>
        function onGradeSelect(grade_id, selected_section = "") {

            fetch("sections_get.php?grade=" + grade_id + "&section=" + selected_section)
                .then(res => res.text())
                .then(data => {
                    document.getElementById("sectionSelect").innerHTML = data;

                    // Section Count check
                    let count = document.getElementById("sectionCount")?.value ?? 0;

                    if (count == 0) {
                        // No section assigned → make NOT required
                        document.querySelector("select[name='section']").required = false;
                    } else {
                        // Section exists → Make required
                        document.querySelector("select[name='section']").required = true;
                    }
                });
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
                        <h3 class="fw-bolder">Manage Students</h3>
                        <div style="display:flex; gap:10px;">
                            <?php if (userHasPermission(STUDENT_IMPORT)) { ?>
                                <button class="btn btn-primary" id="importExcelBtn">
                                    <i class="fas fa-file-excel"></i> Import Excel
                                </button>
                            <?php } ?>
                            <!-- Import Excel Modal -->
                            <div class="modal fade" id="importExcelModal" tabindex="-1" >
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#1da1f2; color:white;">
                                            <h3 class="student-modal-title">Import Student Excel File</h3>
                                            <button type="button" data-bs-dismiss="modal" class="closeicon">
                                                <i class="fas fa-times fs-4"></i>
                                        </div>
                                        <form id="excelForm" method="POST" action="import-student.php"
                                            enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <label class="form-label fw-bold">Upload Excel File (.csv)</label>
                                                <input type="file" class="form-control" name="excel_file"
                                                    accept=".xls,.xlsx" required>
                                                <p class="mt-2 text-muted" style="font-size:14px;">
                                                    Please upload an Excel file containing student data.
                                                </p>
                                                <a href="system/system_file/Sample.csv">
                                                    Download Sample file
                                                </a>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php if (userHasPermission(STUDENT_ADD)) { ?>
                                <a href="add_student.php" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Add Student
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Filter Box -->
                    <?php if (userHasPermission(STUDENT_SEARCH)) { ?>
                        <div class="filter-box mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search</span>
                                        <input type="text" class="form-control" placeholder="Enter student name...">
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary" onclick="searchStudents()">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (userHasPermission(STUDENT_VIEW)) { ?>
                        <!-- Student Rows -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered common-table">
                                <thead>
                                    <tr>
                                        <th>Grade - Section</th>
                                        <th>Username</th>
                                        <th>Student Number</th>
                                        <th>Student Name</th>
                                        <th style="width:120px;">Actions</th>
                                    </tr>
                                </thead>
                                <!-- id="studentList" -->
                                <tbody id="studentList">

                                </tbody>
                            </table>

                        </div>
                    <?php } ?>
                
                <!-- Modal -->
                <div class="modal fade text-dark" id="studentModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h3 class="student-modal-title">Add Student</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="userType" value="<?php echo STUDENT; ?>">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control mb-3"
                                    placeholder="Enter username" required>
                                <label class="form-label">Student Number <span class="text-danger">*</span></label>
                                <input type="text" name="student_number" class="form-control mb-3"
                                    placeholder="Enter student number" required>
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter password" id="studentPassword" required>
                                    <span class="input-group-text bg-primary" style="cursor:pointer;"
                                        onclick="togglePassword('studentPassword', this)">
                                        <i class="fas fa-eye-slash text-white"></i>
                                    </span>
                                </div>
                                <label class="form-label">Student Name <span class="text-danger">*</span></label>
                                <input type="text" name="student_name" class="form-control mb-3"
                                    placeholder="Enter student name" required>
                                <label class="form-label">Father Name <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" class="form-control mb-3"
                                    placeholder="Enter father name" required>
                                <label class="form-label">Family Name <span class="text-danger">*</span></label>
                                <input type="text" name="family_name" class="form-control mb-3"
                                    placeholder="Enter family name" required>
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-control mb-3" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <label class="form-label">Grade <span class="text-danger">*</span></label>
                                <select name="grade" id="gradeSelect" class="form-control mb-3"
                                    onchange="onGradeSelect(this.value)" required>
                                    <option value="">Select Grade</option>
                                    <?php
                                    $grades = $conn->query("SELECT grade_id, grade_name FROM grades ORDER BY grade_name ASC");
                                    while ($g = $grades->fetch_assoc()) {
                                        echo "<option value='{$g['grade_id']}'>{$g['grade_name']}</option>";
                                    }
                                    ?>
                                </select>
                                <label class="form-label">Section <span class="text-danger">*</span></label>
                                <select name="section" id="sectionSelect" class="form-control mb-3"
                                    onchange="onSectionSelect($grades['grade_id'])" required>
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button id="saveStudentBtn" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    Are you sure you want to delete this student?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const studentModal = new bootstrap.Modal(document.getElementById('studentModal'));
        const importExcelModal = new bootstrap.Modal(document.getElementById('importExcelModal'));

        let selectedID = "";
        // Load Students
        function loadStudents(search = "") {
            $("#studentList").load("students_fetch.php?search=" + encodeURIComponent(search));
        }
        loadStudents();
        // View Student
        function viewStudent(id, username, student_number, student_name, father_name, family_name, gender, grade, section, password) {
            selectedID = id;
            $("#studentModal input[name='username']").val(username).prop("readonly", true);
            $("#studentModal input[name='student_number']").val(student_number).prop("readonly", true);
            $("#studentModal input[name='password']").val(password).prop("readonly", true);
            $("#studentModal input[name='student_name']").val(student_name).prop("readonly", true);
            $("#studentModal input[name='father_name']").val(father_name).prop("readonly", true);
            $("#studentModal input[name='family_name']").val(family_name).prop("readonly", true);
            $("#studentModal select[name='gender']").val(gender).prop("disabled", true);
            // Grade Set
            $("#studentModal select[name='grade']").val(grade).prop("disabled", true);
            // Load Sections & Select Correct Section
            onGradeSelect(grade, section);
            setTimeout(() => {
                $("#studentModal select[name='section']").prop("disabled", true);
            }, 300);

            $(".student-modal-title").text("View Student");
            $("#saveStudentBtn").hide();
            studentModal.show();
        }
        let studentID = null;
        let deleteUrl = null;
        let deleteSuccessMsg = "";
        // Delete Student
        function deleteStudent(id) {
            openDeletePopup(id, "student_delete.php", "Student Deleted Successfully!");
        }
        // Search
        function searchStudents() {
            const s = document.querySelector(".filter-box input").value.trim();
            loadStudents(s);
        }

        // Clear
        $(".btn-secondary:contains('Clear')").on("click", function() {
            $(".filter-box input").val("");
            loadStudents();
        });
        // Import Excel Modal
        document.getElementById('importExcelBtn').addEventListener('click', () => {
            importExcelModal.show();
        });
        // DELETE (Confirmation + Success Msg)
        function openDeletePopup(id, apiUrl, msg) {
            studentID = id;
            deleteUrl = apiUrl;
            deleteSuccessMsg = msg;
            let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        function confirmDelete() {
            ajaxPost(deleteUrl, {
                student_id: studentID
            }, function(res) {
                if (res.trim() === "success") {
                    showMessage(deleteSuccessMsg, "success");
                    loadStudents();
                } else {
                    showMessage("Error deleting! " + res, "error");
                }
            });
            bootstrap.Modal.getInstance(
                document.getElementById('deleteConfirmModal')
            ).hide();
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