<?php

include 'header.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Permissions </title>
 
</head>

<body>

    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">

                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>

                

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">Manage Permissions</h3>
                        <button class="btn btn-primary" id="addPermissionBtn">
                            <i class="fas fa-plus-circle"></i> Add Permission
                        </button>
                    </div>

                    <!-- Search Box -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Permissions</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter permission name...">
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <a href="manage_permissions.php" class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : "";

                    $query = "SELECT permission_id, permission FROM permissions WHERE 1=1";

                    if ($search !== "") {
                        $query .= " AND permission LIKE '%" . $conn->real_escape_string($search) . "%'";
                    }

                    $query .= " ORDER BY permission_id ASC";

                    $result = $conn->query($query);
                    ?>

                    <!-- Permission List -->
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo htmlspecialchars($row['permission']); ?></strong>
                                    </div>

                                    <div class="action-icons">

                                        <!-- Edit -->
                                        <i class="fas fa-edit text-primary" style="cursor:pointer;" title="Edit permission" onclick="editPermission('<?php echo $row['permission_id']; ?>',
                                                               '<?php echo htmlspecialchars($row['permission']); ?>')">
                                        </i>

                                        <!-- View -->
                                        <i class="fas fa-eye text-primary" style="cursor:pointer;" title="View permission"
                                            onclick="viewPermission('<?php echo htmlspecialchars($row['permission']); ?>')">
                                        </i>

                                        <!-- Delete -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete permission" onclick="openDeleteModal('permission_delete.php?id=<?php echo $row['permission_id']; ?>',
                                       'Are you sure you want to delete this permission?')">
                                        </i>

                                    </div>

                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No permissions found.</p>
                    <?php endif; ?>

                

                <!-- ADD / EDIT PERMISSION MODAL -->
                <div class="modal fade text-dark" id="permissionModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="permission_save.php">

                                <div class="modal-header bg-primary text-white">
                                    <h3 class="modal-title" id="modalTitle">Add Permission</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="permissionId" id="permissionId">

                                    <label class="form-label">Permission</label>
                                    <input type="text" class="form-control" name="permissionName" id="permissionName"
                                        placeholder="Enter permission name" required>
                                </div>

                                <div class="modal-footer">
                                    <a href="manage_permission.php" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                                    <button class="btn btn-primary" id="saveBtn" type="submit">Save</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

                <!-- VIEW PERMISSION MODAL -->
                <div class="modal fade text-dark" id="viewPermissionModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3 class="modal-title">View Permission</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label class="form-label">Permission</label>
                                <input type="text" class="form-control" id="viewPermissionName" readonly>
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
        const modalPermission = new bootstrap.Modal(document.getElementById('permissionModal'));

        // ADD
        document.getElementById('addPermissionBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Permission";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('permissionId').value = "";
            document.getElementById('permissionName').value = "";

            modalPermission.show();
        });

        // EDIT
        function editPermission(id, name) {
            document.getElementById('modalTitle').innerText = "Edit Permission";
            document.getElementById('saveBtn').innerText = "Update";

            document.getElementById('permissionId').value = id;
            document.getElementById('permissionName').value = name;

            modalPermission.show();
        }

        // VIEW
        function viewPermission(name) {
            document.getElementById('viewPermissionName').value = name;

            const modal = new bootstrap.Modal(document.getElementById('viewPermissionModal'));
            modal.show();
        }

        // DELETE CONFIRM
        function confirmDelete(menuId) {
            document.getElementById("deleteMenuId").value = menuId;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }


        document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
            let id = document.getElementById("deleteMenuId").value;
            window.location.href = "role_delete.php?id=" + id;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let msg = "<?php echo $_SESSION['msg'] ?? ''; ?>";
            let type = "<?php echo $_SESSION['transaction_status'] ?? 'success'; ?>";

            if (msg.trim() !== "") {
                showMessage(msg, type);
            }
        });
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>


</body>

</html>