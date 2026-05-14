<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php
include "config.php";
$userType = $_SESSION['LoggedInUserType'] ?? '';
$grade = filter_input(INPUT_GET, 'grade', FILTER_VALIDATE_INT);

if ($grade === false || $grade === null) {
    die("Invalid Grade ID");
}

$stmt = mysqli_prepare($conn, "SELECT grade_name,grade_id FROM grades WHERE grade_id = ?");
mysqli_stmt_bind_param($stmt, "i", $grade);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $grade_name, $id);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$grade_name) {
    $grade_name = "Unknown Grade";
}
$grade_id = $id;
?>

<head>
    <meta charset="UTF-8">
    <title>Manage Section </title>

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

                
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder ">
                          <?php if ($userType == TEACHER) {
                                echo $grade_name . ' - My Sections';
                                
                            }else{
                               echo $grade_name . ' - Manage Sections';
                            } ?>   
                        
                       </h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage-grade.php" class="btn btn-primary">Back To Grade</a>
                            <?php if (userHasPermission(SECTION_ADD)) { ?>
                                <button class="btn btn-primary" id="addSectionBtn"><i class="fas fa-plus-circle"></i> Add
                                    Section</button>
                            <?php } ?>
                        </div>
                    </div>

                    <!-- Filter Box -->
                    <?php if (userHasPermission(SECTION_SEARCH)) { ?>
                        <div class="filter-box mb-4 " >
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Section</span>
                                        <input type="text" id="searchSection" class="form-control"
                                            placeholder="Enter section name...">
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

                    <!-- Section List -->
                    <?php if (userHasPermission(SECTION_VIEW)) { ?>
                        <div id="sectionList"></div>
                    <?php } ?>
                

                <!-- Add/Edit Modal -->
                <div class="modal fade text-dark" id="sectionModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary">
                                <h3 class="section-modal-title text-white">Add Section</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Section Name <span class="text-danger">*</span></label>
                                    <input type="text" id="sectionName" class="form-control"
                                        placeholder="Enter section name" maxlength="50"  oninput="removeSpecialChars(this)" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                    <div class="d-flex" style="gap:45px;">

                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="gender" id="genderGirl"
                                                value="girl" required>
                                            <label class="form-check-label" for="genderGirl"
                                                style="display:flex;align-items:center;gap:10px;"><i
                                                    class="fas fa-female"
                                                    style="font-size:28px;color:#E91E63;"></i>
                                                <span>Girl</span></label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="genderBoy"
                                                value="boy" required>
                                            <label class="form-check-label" for="genderBoy"
                                                style="display:flex;align-items:center;gap:10px;"><i class="fas fa-male"
                                                    style="font-size: 28px; color:#2196f3;"></i> <span>
                                                    boy</span></label>
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal"
                                    id="cancelSectionBtn">Cancel</button>
                                <button class="btn btn-primary" id="saveSectionBtn">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>


        const sectionModal = new bootstrap.Modal(document.getElementById('sectionModal'));
        // document.getElementById('addSectionBtn').addEventListener('click', () => modal.show());
        const addSecBtn = document.getElementById('addSectionBtn');
        if (addSecBtn) {
            addSecBtn.addEventListener('click', () => modal.show());
        }

        let selectedSectionID = "";
        let gradeID = "<?php echo $grade_id; ?>";


        // LOAD LIST
        function loadSections(search = "") {
            $("#sectionList").load("sections_fetch.php?grade=" + gradeID + "&search=" + encodeURIComponent(search));
        }



        loadSections();

        // ADD OPEN
        const addBtn = document.getElementById('addSectionBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                selectedSectionID = "";
                $("#sectionName").val("");
                $("input[name=gender]").prop("checked", false);

                $(".section-modal-title").text("Add Section");
                $("#cancelSectionBtn").text("Cancel");
                $("#saveSectionBtn").show();

                sectionModal.show();
            });
        }

        // SAVE
        $("#saveSectionBtn").on("click", function () {
            let sectionName = $("#sectionName").val();
            let gender = $("input[name=gender]:checked").val();

            if (sectionName === "") return showMessage("Enter Section Name", "error");
            if (!gender) return showMessage("Select Gender", "error");


            let url = selectedSectionID === "" ? "section_insert.php" : "section_update.php";
            let successMsg = selectedSectionID === "" ? "Section Added Successfully!" : "Section Updated Successfully!";

            ajaxPost(url, {
                id: selectedSectionID,
                grade_id: gradeID,
                name: sectionName,
                gender: gender
            }, function (res) {
                res = res.trim();

                if (res === "duplicate") {
                    showMessage("Section already exists!", "error");
                    return;
                }
                if (res.trim() === "success") {
                    showMessage(successMsg, "success");
                    sectionModal.hide();
                    loadSections();
                } else {
                    showMessage("Error: " + res, "error");
                }
            });
        });

        window.editSection = function (id, name, gender) {
            selectedSectionID = id;

            $("#sectionName").val(name);
            $("input[name=gender][value='" + gender + "']").prop("checked", true);

            $(".section-modal-title").text("Edit Section");
            $("#saveSectionBtn").show();
            $("#cancelSectionBtn").text("Cancel");

            sectionModal.show();
        };

        window.viewSection = function (id, name, gender) {
            selectedSectionID = id;

            $("#sectionName").val(name);
            $("input[name=gender][value='" + gender + "']").prop("checked", true);

            $(".section-modal-title").text("View Section");
            $("#saveSectionBtn").hide();
            $("#cancelSectionBtn").text("Close");

            sectionModal.show();
        };


        // DELETE — FINAL WORKING
        window.deleteSection = function (id) {
            openDeletePopup(id, "section_delete.php", "Section Deleted Successfully!");
        };


        // SEARCH
        $("#searchBtn").on("click", function () {
            let s = $("#searchSection").val().trim();
            loadSections(s);
        });

        // CLEAR
        $("#clearBtn").on("click", function () {
            $("#searchSection").val("");
            loadSections();
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
                    loadSections();
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