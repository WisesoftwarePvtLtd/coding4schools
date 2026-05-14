<?php
session_start();
include 'header.php';
include 'config.php';
$userType = $_SESSION['LoggedInUserType'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Editors</title>
</head>

<body>

    <div class="container-fluid" style="padding:30px;">
        <div class="layout-row d-flex">
            <!-- SIDEBAR -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-area flex-fill text-dark" style="padding-left:25px;">
 <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0">Manage Editors</h3>
                    <button class="btn btn-primary" onclick="openAddEditor()">
                        <i class="fas fa-plus-circle"></i> Add Editor
                    </button>
                </div>

                <!-- FILTER -->
                <div class="filter-box">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="fw-semibold">Search Editor</label>
                            <input type="text" id="searchEditor" class="form-control"
                                placeholder="Enter editor name...">
                        </div>

                        <div class="col-md-4">
                            <label class="fw-semibold">Editor URL</label>
                            <input type="text" id="searchUrl" class="form-control" placeholder="Enter editor URL">
                        </div>
                        <div class="col-md-3 d-flex align-items-end" style="gap:10px;">
                            <button class="btn btn-primary" onclick="searchEditors()">
                                <i class="fas fa-search"></i> Search
                            </button>

                            <button class="btn btn-secondary" onclick="clearSearch()">
                                <i class="fas fa-times-circle"></i> Clear
                            </button>
                        </div>
                    </div>
                </div>

                <!-- LIST -->
                <div id="editorList"></div>

            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="editorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 id="modalTitle" class="mb-0">Add Editor</h5>
                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                        <i class="fas fa-times fs-4"></i>
                    </button>

                </div>

                <div class="modal-body">
                    <input type="hidden" id="editorId">

                    <label class="fw-semibold">Editor Name *</label>
                    <input type="text" id="editorName" class="form-control mb-2" placeholder="Enter editor name...">

                    <label class="fw-semibold">Editor URL *</label>
                    <input type="url" id="editorUrl" class="form-control" placeholder="Enter editor URL">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="saveEditor()">Save</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="mb-0">Confirm Delete</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    Are you sure you want to delete this editor?
                </div>

                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>

            </div>
        </div>
    </div>

    <div id="actionMsg" class="alert" style="display:none; position:fixed; top:20px; right:20px; z-index:9999;"></div>
    <div id="globalMsg" class="global-msg" style="display:none;"></div>

    <script>
    

        /* ===============================
           LOAD EDITORS
        ================================ */
        function loadEditors(name = "", url = "") {
            $("#editorList").html("<div class='text-muted'>Loading...</div>");
            $("#editorList").load("editors_fetch.php", { name, url });
        }

        loadEditors();

        /* ===============================
           SEARCH / CLEAR
        ================================ */
        function searchEditors() {
            loadEditors(
                $("#searchEditor").val(),
                $("#searchUrl").val()
            );
        }

        function clearSearch() {
            $("#searchEditor,#searchUrl").val("");
            loadEditors();
        }

        /* ===============================
           MODAL OPEN
        ================================ */
        function openAddEditor() {
            $("#editorId").val("");
            $("#editorName").val("").prop("readonly", false);
            $("#editorUrl").val("").prop("readonly", false);

            $("#modalTitle").text("Add Editor");

            $("#editorModal .btn-success").show();

            new bootstrap.Modal(document.getElementById("editorModal")).show();
        }



        function editEditor(id, name, url) {
            $("#editorId").val(id);
            $("#editorName").val(name).prop("readonly", false);
            $("#editorUrl").val(url).prop("readonly", false);

            $("#modalTitle").text("Edit Editor");

            // Show Save button again
            $("#editorModal .btn-success").show();

            new bootstrap.Modal(document.getElementById("editorModal")).show();
        }

        function viewEditor(id, name, url) {
            $("#editorId").val(id);
            $("#editorName").val(name).prop("readonly", true);
            $("#editorUrl").val(url).prop("readonly", true);

            $("#modalTitle").text("View Editor");

            // Hide Save button
            $("#editorModal .btn-success").hide();

            new bootstrap.Modal(document.getElementById("editorModal")).show();
        }


        function saveEditor() {
            let id = $("#editorId").val();
            let name = $("#editorName").val().trim();
            let url = $("#editorUrl").val().trim();

            if (!name || !url) {
                showMessage("All fields are required", "error");
                return;
            }

            let api = id === "" ? "editor_insert.php" : "editor_update.php";

            $.post(api, { id, name, url }, function (res) {
                res = res.trim();

                if (res === "success") {
                    showMessage(
                        id === "" ? "Editor added successfully!" : "Editor updated successfully!",
                        "success"
                    );

                    bootstrap.Modal.getInstance(
                        document.getElementById("editorModal")
                    ).hide();

                    loadEditors();

                } else if (res === "duplicate") {
                    showMessage("Editor with this name already exists!", "error");
                } else {
                    showMessage("Something went wrong!", "error");
                }
            });
        }




        /* ===============================
           DELETE (CONFIRM FLOW)
        ================================ */
        let deleteID = null;

        function deleteEditor(id) {
            deleteID = id;
            new bootstrap.Modal("#deleteConfirmModal").show();
        }

        function confirmDelete() {
            $.post("editor_delete.php", { id: deleteID }, function (res) {
                if (res.trim() === "success") {
                    showMessage("Editor deleted successfully!", "success");
                    loadEditors();
                } else {
                    showMessage(res, "error");
                }
            });

            bootstrap.Modal.getInstance(
                document.getElementById("deleteConfirmModal")
            ).hide();
        }

        /* ===============================
           ENTER KEY SEARCH
        ================================ */
        $(".filter-box input").on("keypress", function (e) {
            if (e.key === "Enter") searchEditors();
        });
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