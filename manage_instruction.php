<?php
session_start();
include 'header.php';
include 'config.php';

$userType = $_SESSION['LoggedInUserType'] ?? '';
$exercise_id = intval($_GET['exercise_id'] ?? 0);
if ($exercise_id <= 0) {
    die("Invalid Exercise");
}

/* FETCH EXERCISE NAME FROM DB */
$exercise_name = "";

$q = mysqli_query(
    $conn,
    "SELECT exercise_name FROM exercises WHERE exercise_id = $exercise_id"
);

if ($row = mysqli_fetch_assoc($q)) {
    $exercise_name = $row['exercise_name'];
}
$lesson_id = 0;

$q = mysqli_query(
    $conn,
    "SELECT lesson_id FROM exercises WHERE exercise_id = $exercise_id"
);

if ($row = mysqli_fetch_assoc($q)) {
    $lesson_id = $row['lesson_id'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Instructions</title>
</head>

<body>


    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row d-flex">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN -->
            <div class="main-area flex-fill" style="padding-left:25px;">

                <!-- HEADER (SAME) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3>
                        <?= htmlspecialchars($exercise_name) ?> – Manage Instructions
                    </h3>



                    <div class="d-flex gap-2">

                        <button class="btn btn-secondary" style="margin-right:12px;"
                            onclick="window.location.href='manage_exercise.php?lesson_id=<?= $lesson_id ?>'">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>



                        <!-- ADD INSTRUCTION BUTTON -->
                        <button class="btn btn-primary"
                            onclick="window.location.href='add_instruction.php?exercise_id=<?= $exercise_id ?>'">
                            <i class="fas fa-plus-circle"></i> Add Instruction
                        </button>
                    </div>
                </div>



                <!-- FILTER (SAME) -->
                <div class="filter-box">
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label class="fw-semibold">Search Instruction</label>
                            <input type="text" id="searchInstruction" class="form-control"
                                placeholder="Enter instruction text">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="searchInstructions()">
                                <i class="fas fa-search"></i> Search
                            </button>


                            <button class="btn btn-secondary" style="margin-left:10px;" onclick="clearSearch()">
                                <i class="fas fa-times-circle"></i> Clear
                            </button>




                        </div>
                    </div>
                </div>

                <!-- LIST (SAME STRUCTURE) -->
                <div id="instructionList"></div>

            </div>
        </div>
    </div>


    </div>
    <!-- GLOBAL MESSAGE -->
    <div id="globalMsg" class="global-msg" style="display:none;"></div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        /* LOAD */
        function loadInstructions(text = "") {
            $("#instructionList").load(
                "instruction_fetch.php", {
                text,
                exercise_id: <?= $exercise_id ?>
            }
            );
        }
        loadInstructions();

        /* SEARCH */
        function searchInstructions() {
            loadInstructions($("#searchInstruction").val());
        }

        function clearSearch() {
            $("#searchInstruction").val("");
            loadInstructions();
        }
        /* DELETE */
        let deleteId = null;

        function deleteInstruction(id) {
            deleteId = id;
            new bootstrap.Modal(
                document.getElementById("deleteConfirmModal")
            ).show();
        }

        function confirmDelete() {
            $.post("instruction_delete.php", {
                id: deleteId
            }, function (res) {
                if (res.trim() === "success") {
                    showMessage("Instruction deleted", "success");
                    loadInstructions();
                } else {
                    showMessage(res, "error");
                }
            });
            bootstrap.Modal.getInstance(
                document.getElementById("deleteConfirmModal")
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