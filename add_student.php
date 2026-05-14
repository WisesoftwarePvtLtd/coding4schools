<?php
session_start();
include 'header.php';
include 'config.php';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
</head>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">
            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>
            <!-- MAIN -->
            <div class="main-area">
                <div id="globalMsg" class="global-msg"></div>
                <div class="card shadow">
                    <!-- HEADER -->
                    <div class="card-header" style="background:#1da1f2; color:white;">
                        <h4 class="m-0"> Add Student</h4>
                    </div>

                    <div class="card-body">
                        <form action="student_insert.php" method="POST" enctype="multipart/form-data" onsubmit="return validateStudentForm();">
                            <input type="hidden" name="userType" value="<?php echo STUDENT; ?>">
                            <input type="hidden" id="userSchoolId" name="userSchoolId" value="<?= $userSchoolId ?>">

                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control mb-3"
                                placeholder="Enter username" maxlength="50"  
                                        onkeydown="restrictSpace(event)" onpaste="restrictSpace(event)" required>
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
                                $grades = $conn->query("SELECT grade_id, grade_name,school_id FROM grades WHERE  school_id = $userSchoolId ORDER BY grade_name ASC");
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
                            <a href="manage-student.php" class="btn btn-secondary px-4">Cancel</a>
                            <button id="saveStudentBtn" class="btn btn-primary">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function validateStudentForm() {
            let inputs = document.querySelectorAll(
                "input[type='text'], input[type='password']"
            );
            let selects = document.querySelectorAll("select");

            for (let input of inputs) {
                if (input.value.trim() === "") {
                    showMessage("Please fill all required fields properly", "error");
                    input.focus();
                    return false; // ❌ stop submit
                }
                input.value = input.value.trim();
            }

            for (let select of selects) {
                if (select.value.trim() === "") {
                    showMessage("Please select all required fields", "error");
                    select.focus();
                    return false; // ❌ stop submit
                }
            }

            return true; // ✅ allow submit
        }
    </script>
    <script>
        function onGradeSelect(grade_id) {
            fetch("sections_get.php?grade=" + grade_id)
                .then(res => res.text())
                .then(html => {
                    document.getElementById("sectionSelect").innerHTML = html;
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