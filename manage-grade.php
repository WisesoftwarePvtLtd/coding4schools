<?php include 'header.php';
$userType = $_SESSION['LoggedInUserType'] ?? '';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Books </title>

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
                        <h3 class="fw-bolder">
                            <?php if ($userType == TEACHER) {
                                echo "My Grades";

                            } else {
                                echo "Manage Grades";
                            } ?>
                        </h3>
                        <?php if (userHasPermission(GRADE_ADD)) { ?>
                            <button class="btn btn-primary" id="addGradeBtn"><i class="fas fa-plus-circle"></i> Add
                                Grade</button>
                        <?php } ?>
                    </div>

                    <!-- Filter Box -->
                    <?php if (userHasPermission(GRADE_SEARCH)) { ?>
                        <div class="filter-box mb-4 ">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Grade</span>
                                        <input type="text" class="form-control" placeholder="Enter grade name...">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Academic Year</span>
                                        <select id="filterYear" class="form-control">
                                            <option value="">Select Academic Year</option>
                                            <?php
                                            for ($year = 2025; $year <= 2027; $year++) {
                                                echo "<option value='$year'>$year</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i>
                                        Clear</button>
                                </div>
                            </div>

                        </div>
                    <?php } ?>

                    <!-- Grade List Display Here -->
                    <?php if (userHasPermission(GRADE_VIEW)) { ?>
                        <div class="row" id="gradeList"></div>
                    <?php } ?>

                

                <!-- Modal -->
                <div class="modal fade text-dark" id="gradeModal" tabindex="-1" >
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h3 class="grade-modal-title text-white">Add Grade</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" id="gradeId">
                                <input type="hidden" id="userSchoolId" name="userSchoolId" value="<?= $userSchoolId ?>">

                                <label class="form-label">Grade Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="gradeName" placeholder="Enter grade name"
                                    maxlength="50" oninput="removeSpecialChars(this)" required>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Grade Number <span class="text-danger">*</span></label>
                                <select name="grade_number_order" id="grade_number_order" class="form-control" required>
                                    <option value="">Select Grade Number</option>
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Academic Year <span class="text-danger">*</span></label>
                                <select class="form-control" id="academicYear" required>
                                    <option value="">Select Academic Year</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal" id="cancelbtn">Cancel</button>
                                <button class="btn btn-primary" id="saveGradeBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>


        let select = document.getElementById("academicYear");

        for (let year = 2025; year <= 2027; year++) {
            let option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        }


        const modal = new bootstrap.Modal(document.getElementById('gradeModal'));

        const addBtn = document.getElementById('addGradeBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => modal.show());
        }


        function loadGrades(search = "", year = "") {
            $("#gradeList").load("grades_fetch.php?search=" + encodeURIComponent(search) + "&year=" + year);
        }


        // Initial Load
        loadGrades();

        // Open Modal for Add
        document.getElementById('addGradeBtn').addEventListener('click', () => {
            $("#gradeId").val("");
            $("#gradeName").val("");
            $("#grade_number_order").val("");
            $("#academicYear").val("");

            $(".grade-modal-title").text("Add Grade");
            $("#gradeName").prop("readonly", false);
            $("#grade_number_order").prop("disabled", false);
            $("#academicYear").prop("disabled", false);
            $("#cancelbtn").text("Cancel");
            $("#saveGradeBtn").show(); // Show Save Button
            modal.show();
        });


        // Save (Add/Update)
        $("#saveGradeBtn").on("click", function () {
            let id = $("#gradeId").val();
            let name = $("#gradeName").val();
            let gradeNumber = $("#grade_number_order").val();

            let academicYear = $("#academicYear").val();
            let schoolId = $("#userSchoolId").val();

            if (name == "") {
                showMessage("Please enter grade name", "error");
                return;
            }
            if (gradeNumber == "") {
                showMessage("Please select grade number", "error");
                return;
            }
            if (academicYear == "") {
                showMessage("Please select academic year", "error");
                return;
            }

            if (id == "") {
                // ADD
                insertData("grade_insert.php", { name, gradeNumber, academicYear, schoolId }, "Grade Added Successfully!", "success", function () {
                    modal.hide();
                    loadGrades();
                });
            } else {
                // UPDATE
                updateData("grade_update.php", { id, name, gradeNumber, academicYear, schoolId }, "Grade Updated Successfully!", "success", function () {
                    modal.hide();
                    loadGrades();
                });
            }



        });

        // Edit
        function editGrade(id, name, gradeNumber, academicYear) {
            $("#gradeId").val(id);
            $("#gradeName").val(name);
            $("#grade_number_order").val(gradeNumber);
            $("#academicYear").val(academicYear);

            $(".grade-modal-title").text("Edit Grade");
            $("#gradeName").prop("readonly", false);
            $("#grade_number_order").prop("disabled", false);
            $("#academicYear").prop("disabled", false);
            $("#cancelbtn").text("Cancel");

            $("#saveGradeBtn").show(); // Show Save Button
            modal.show();
        }


        // Delete
        function deleteGrade(id) {
            openDeletePopup(id, "grade_delete.php", "Grade Deleted Successfully!");
        }


        // 🔍 SEARCH BUTTON
        $(".btn-primary:contains('Search')").on("click", function () {
            let s = $(".filter-box input").val();
            let year = $("#filterYear").val();
            loadGrades(s, year);
        });

        // 🔄 YEAR FILTER CHANGE
        $("#filterYear").on("change", function () {
            let s = $(".filter-box input").val();
            let year = $("#filterYear").val();
            loadGrades(s, year);
        });

        // ❌ CLEAR BUTTON
        $(".btn-secondary:contains('Clear')").on("click", function () {
            $(".filter-box input").val("");
            $("#filterYear").val("");

            loadGrades("", "");
        });



        function viewGrade(id, name, gradeNumber, academicYear) {
            $("#gradeId").val(id);
            $("#gradeName").val(name);
            $("#grade_number_order").val(gradeNumber);
            $("#academicYear").val(academicYear);

            $(".grade-modal-title").text("View Grade");
            $("#gradeName").prop("readonly", true);
            $("#grade_number_order").prop("disabled", true);
            $("#academicYear").prop("disabled", true);
            $("#cancelbtn").text("Close");

            $("#saveGradeBtn").hide(); // Hide Save Button


            modal.show();
        }


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
                    loadGrades();
                } else {
                    showMessage("Error deleting! " + res, "error");
                }

            });

            bootstrap.Modal.getInstance(
                document.getElementById('deleteConfirmModal')
            ).hide();
        }

        // SEARCH ON ENTER KEY PRESS
        $(".filter-box input").on("keypress", function (e) {
            if (e.key === "Enter") {
                let s = $(".filter-box input").val();
                let year = $("#filterYear").val();
                loadGrades(s, year);
            }
        });



    </script>
</body>

</html>