<?php

include 'header.php';
include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Roles </title>
  
</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>

                <div class="container-fluid p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bolder">Manage Roles</h3>
                        <button class="btn btn-primary" id="addroleBtn"><i class="fas fa-plus-circle"></i> Add
                            Role</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4 ">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Roles</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter role name...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i>
                                        Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : "";

                    $roleQuery = "SELECT role_id, role_name FROM roles WHERE 1=1";

                    if ($search !== "") {
                        $roleQuery .= " AND role_name LIKE '%" . $conn->real_escape_string($search) . "%'";
                    }

                    $roleQuery .= " ORDER BY role_id ASC";

                    $roleResult = $conn->query($roleQuery);

                    ?>
                    <!-- role List Display Here -->
                    <?php if ($roleResult->num_rows > 0): ?>
                        <?php while ($row = $roleResult->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo htmlspecialchars($row['role_name']); ?></strong>
                                    </div>

                                    <div class="action-icons">
                                        <!-- Manage roles -->
                                        <a href="manage_role_menus.php?role_id=<?= $row['role_id'] ?>">
                                            <i class="fas fa-list text-primary" title="Manage Menus"></i>
                                        </a>

                                          <!-- Manage roles -->
                                        <a href="manage_role_permission.php?role_id=<?= $row['role_id'] ?>">
                                            <i class="fas fa-key text-primary" title="Manage permission"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="#"
                                            onclick="editrole('<?php echo $row['role_id']; ?>', '<?php echo htmlspecialchars($row['role_name']); ?>')">
                                            <i class="fas fa-edit text-primary" title="Edit role"></i>
                                        </a>


                                        <!-- View -->
                                        <i class="fas fa-eye text-primary" style="cursor:pointer;" title="View role"
                                            onclick="viewrole('<?php echo htmlspecialchars($row['role_name']); ?>')">
                                        </i>


                                        <!-- Delete -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete role"
                                            onclick="openDeleteModal('role_delete.php?id=<?php echo $row['role_id']; ?>', 'Are you sure you want to delete this Role?')">
                                        </i>

                                    </div>

                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No roles found.</p>
                    <?php endif; ?>



                </div>

                <!-- Modal -->
                <div class="modal fade text-dark" id="roleModal" tabindex="-1" >
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <form method="POST" action="role_save.php">

                                <div class="modal-header bg-primary">
                                    <h3 class="modal-title text-white" id="modalTitle">Add Role</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="roleId" id="roleId">
                                    <label class="form-label">role</label>
                                    <input type="text" class="form-control" name="roleName" id="roleName"
                                        placeholder="Enter role name" required>
                                </div>

                                <div class="modal-footer">
                                    <a href="manage_roles.php" class="btn btn-secondary" data-bs-dismiss="modal"
                                        id="cancelbtn">Cancel</a>
                                    <button class="btn btn-primary" type="submit" id="saveBtn">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- VIEW role MODAL -->
                <div class="modal fade" id="viewroleModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3 class="modal-title text-white" id="modalTitle">View Role</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" name="viewRoleName" id="viewRoleName" value=""
                                    readonly>
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
        const modalAssign = new bootstrap.Modal(document.getElementById('roleModal'));

        // ADD role (Reset form)
        document.getElementById('addroleBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Role";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('roleId').value = "";
            document.getElementById('roleName').value = "";

            modalAssign.show();
        });

        // EDIT role (Open same modal)
        function editrole(id, name) {
            document.getElementById('modalTitle').innerText = "Edit Role";
            document.getElementById('saveBtn').innerText = "Update";

            document.getElementById('roleId').value = id;
            document.getElementById('roleName').value = name;

            modalAssign.show();
        }


        function viewrole(roleName) {
            document.getElementById("viewRoleName").value = roleName;  // <-- Correct
            const myModal = new bootstrap.Modal(document.getElementById('viewroleModal'));
            myModal.show();
        }


        function confirmDelete(roleId) {
            document.getElementById("deleteroleId").value = roleId;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }


        document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
            let id = document.getElementById("deleteroleId").value;
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