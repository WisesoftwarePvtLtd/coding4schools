<?php
session_start();
include 'header.php';
include 'config.php';



$userType = $_SESSION['LoggedInUserType'] ?? '';

// SEARCH
// SEARCH
$search = trim($_GET['search'] ?? '');
$like = "%$search%";

// FETCH SCHOOLS
$stmt = $conn->prepare("
    SELECT *
    FROM schools
    WHERE school_name LIKE ?
    ORDER BY school_id DESC
");

$stmt->bind_param("s", $like);
$stmt->execute();
$schools = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage schools</title>
</head>

<body>

    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN AREA -->
            <div class="main-area text-dark">
                <div id="globalMsg" class="global-msg"></div>

             

                    <!-- HEADER -->
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">
                            Manage schools
                        </h3>

                        <div class="d-flex gap-3">
                            
                            <button class="btn btn-primary"
                                onclick="window.location.href='school_add.php'">
                                <i class="fas fa-plus-circle"></i> Add school
                            </button>
                        </div>
                    </div>

                    <!-- FILTER BOX (same as grades) -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search School</span>
                                        <input type="text" name="search" class="form-control"
                                            value="<?= htmlspecialchars($search) ?>" placeholder="Enter school name...">
                                    </div>
                                </div>

                                <div class="col-md-3 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    <a href="manage_school.php?lesson_id=<?= $lesson_id ?>" class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- school LIST (same card layout as grades) -->

                    <?php if ($schools->num_rows == 0) { ?>
                        <div class="col-12 text-center text-muted">
                            No schools found
                        </div>
                    <?php } ?>

                    <?php while ($row = $schools->fetch_assoc()) { ?>
                        <div class=" mb-3">
                            <div class="grade-box">
                                <div class="grade-row">



                                    <h5 class="fw-bold mb-3">
                                        <?= htmlspecialchars($row['school_name']) ?>
                                    </h5>

                                    <div class="action-icons">

                                        <a
                                            href="school_edit.php?school_id=<?= $row['school_id'] ?>">
                                            <i class="fas fa-edit text-primary" data-bs-toggle="tooltip"
                                                title="Edit school"></i>
                                        </a>
                                        <!-- Delete -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete school"
                                            onclick="openDeleteModal('school_delete.php?school_id=<?php echo $row['school_id']; ?>', 'Are you sure you want to delete this school?')">
                                        </i>



                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>


                
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="mb-0">Confirm Delete</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    Are you sure you want to delete this school?
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" onclick="confirmDelete()">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        let deleteId = null;

        function deleteschool(id) {
            deleteId = id;
            new bootstrap.Modal(
                document.getElementById("deleteConfirmModal")
            ).show();
        }

        function confirmDelete() {
            $.post("delete_school.php", { id: deleteId }, function (res) {
                if (res.trim() === "success") {
                    location.reload();
                } else {
                    alert(res);
                }
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