<?php
session_start();
include 'header.php';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Teacher </title>
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
                        <h3 class="fw-bolder">Manage Teachers</h3>
                        <?php if (userHasPermission(TEACHER_ADD)) { ?>
                            <button class="btn btn-primary" id="addTeacherBtn"><i class="fas fa-plus-circle"></i> Add
                                Teacher</button>
                        <?php } ?>
                    </div>

                    <!-- Filter -->
                    <?php if (userHasPermission(TEACHER_SEARCH)) { ?>
                        <div class="filter-box mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Teacher</span>
                                        <input type="text" id="searchTeacher" class="form-control"
                                            placeholder="Enter teacher name...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search"></i>
                                        Search</button>
                                    <button class="btn btn-secondary" id="clearBtn"><i class="fas fa-times-circle"></i>
                                        Clear</button>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (userHasPermission(TEACHER_VIEW)) { ?>
                        <!-- Teacher List -->
                        <div id="teacherList"></div>
                    <?php } ?>
                

                <!-- Add/Edit Modal -->
                <div class="modal fade text-dark" id="teacherModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3 class="teacher-modal-title text-white">Add Teacher</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="teacherId">
                                <input type="hidden" id="userSchoolId" name="userSchoolId" value="<?= $userSchoolId ?>">

                                 <div class="mb-3">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" id="username" class="form-control" placeholder="Enter username" maxlength="50"  
                                        onkeydown="restrictSpace(event)" onpaste="restrictSpace(event)">
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" id="userType" name="userType" value=<?php echo TEACHER; ?>>

                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" id="firstName" class="form-control"
                                        placeholder="Enter first name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Family Name <span class="text-danger">*</span></label>
                                    <input type="text" id="familyName" class="form-control"
                                        placeholder="Enter family name">
                                </div>

                               
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-control mb-3">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>

                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter password" id="teacherPassword">
                                    <span class="input-group-text bg-primary" style="cursor:pointer;"
                                        onclick="togglePassword('teacherPassword', this)">
                                        <i class="fas fa-eye-slash text-white"></i>
                                    </span>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    id="cancelTeacherBtn">Cancel</button>
                                <button class="btn btn-primary" id="saveTeacherBtn">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const teacherModal = new bootstrap.Modal(document.getElementById('teacherModal'));
        // document.getElementById('addTeacherBtn').addEventListener('click', () => teacherModal.show());
        const addBtnTeacher = document.getElementById('addTeacherBtn');
        if (addBtnTeacher) {
            addBtnTeacher.addEventListener('click', () => teacherModal.show());
        }
        let selectedID = "";

        // Load Teachers
        function loadTeachers(search = "") {
            $("#teacherList").load("teachers_fetch.php?search=" + encodeURIComponent(search));
        }

        // Initial Load
        loadTeachers();

        // Open Modal for Add
        document.getElementById('addTeacherBtn').addEventListener('click', () => {
            selectedID = "";
            $("#teacherId, #firstName, #familyName, #username, #teacherPassword, #gender")
                .val("")
                .prop("disabled", false)
                .prop("readonly", false);
            $("#userType").val("<?php echo TEACHER;?>");
            $(".teacher-modal-title").text("Add Teacher");
            $("#saveTeacherBtn").show();
            teacherModal.show();
        });

        // Save (Add/Update) using common function
        $("#saveTeacherBtn").on("click", function () {
            let id = selectedID;
            let first = $("#firstName").val().trim();
            let family = $("#familyName").val().trim();
            let username = $("#username").val().trim();
            let password = $("#teacherPassword").val().trim();
            let gender = $("#gender").val().trim();
            let userType = $("#userType").val().trim();
            let schoolId = $("#userSchoolId").val();


            if (!first) return showMessage("Enter First Name", "error");
            if (!family) return showMessage("Enter Family Name", "error");
            if (!username) return showMessage("Enter Username", "error");
            if (!gender) return showMessage("Enter Gender", "error");
            if (selectedID === "" && !password) return showMessage("Enter Password", "error");


            const data = { id, first, family, username, password, gender, userType, schoolId};
            const url = (selectedID === "") ? "teacher_insert.php" : "teacher_update.php";
            const successMsg = (selectedID === "") ? "Teacher Added Successfully!" : "Teacher Updated Successfully!";
            // Using your common ajax function
            ajaxPost(url, data, function (res) {
                res = res.trim();
                if (res === "duplicate") {
                    showMessage("Username already exists!", "error");
                    return;
                }
                if (res.trim() === "success") {
                    showMessage(successMsg, "success");
                    teacherModal.hide();
                    loadTeachers();
                } else {
                    showMessage("Error: " + res, "error");
                }
            });
        });

        // Edit Teacher
        function editTeacher(id, fullname, username, decryptedPassword, gender) {

            let parts = fullname.trim().split(" ");
            let first = parts[0];
            let family = parts.slice(1).join(" ");

            selectedID = id;

            $("#teacherId").val(id);
            $("#firstName").val(first).prop("readonly", false);
            $("#familyName").val(family).prop("readonly", false);
            $("#username").val(username).prop("readonly", false);
            $("#gender").val(gender).prop("disabled", false);;
            $("#teacherPassword").val(decryptedPassword).prop("readonly", false);

            $(".teacher-modal-title").text("Edit Teacher");
            $("#saveTeacherBtn").show();
            teacherModal.show();
        }


        // View Teacher
        function viewTeacher(id, fullname, username, decryptedPassword, gender) {
            let parts = fullname.trim().split(" ");
            let first = parts[0];
            let family = parts.slice(1).join(" ");

            selectedID = id;

            $("#teacherId").val(id);
            $("#firstName").val(first).prop("readonly", true);
            $("#familyName").val(family).prop("readonly", true);
            $("#username").val(username).prop("readonly", true);
            $("#gender").val(gender).prop("disabled", true);
            $("#teacherPassword").val(decryptedPassword).prop("readonly", true);

            $(".teacher-modal-title").text("View Teacher");
            $("#saveTeacherBtn").hide();
            teacherModal.show();
        }

        // Delete Teacher using common function
        function deleteTeacher(id) {
            openDeletePopup(id, "teacher_delete.php", "Teacher Deleted Successfully!");
        }

        // Search
        $("#searchBtn").on("click", function () {
            loadTeachers($("#searchTeacher").val().trim());
        });

        // Clear
        $("#clearBtn").on("click", function () {
            $("#searchTeacher").val("");
            loadTeachers();
        });

        // DELETE (Confirmation + Success Msg)

        let deleteID = null;
        let deleteUrl = null;
        let deleteSuccessMsg = "";

        // Open Delete Modal
        function openDeletePopup(id, apiUrl, msg) {
            deleteID = id;
            deleteUrl = apiUrl;
            deleteSuccessMsg = msg;

            let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }
        function confirmDelete() {

            ajaxPost(deleteUrl, { id: deleteID }, function (res) {

                if (res.trim() === "success") {
                    showMessage(deleteSuccessMsg, "success");
                    loadTeachers();
                } else {
                    showMessage("Error deleting! " + res, "error");
                }

            });

            bootstrap.Modal.getInstance(
                document.getElementById('deleteConfirmModal')
            ).hide();
        }
    </script>


</body>

</html>