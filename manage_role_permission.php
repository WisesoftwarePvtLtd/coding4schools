<?php
session_start();
include 'header.php';
include 'config.php';
$role_id = isset($_GET['role_id']) ? intval($_GET['role_id']) : 0;

$role_name = "";
if ($role_id > 0) {
    $q = $conn->query("SELECT role_name FROM roles WHERE role_id = $role_id LIMIT 1");
    if ($q && $q->num_rows > 0) {
        $role_name = $q->fetch_assoc()['role_name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign Permissions to Roles </title>
   
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

                <div id="globalMsg" class="global-msg"></div>

                <div class="container-fluid p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bolder"><?php echo $role_name ?> - Assign Permissions</h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage_roles.php" class="btn btn-primary">Back To
                                Manage Roles</a>
                            <button class="btn btn-primary" id="addPermissionBtn">
                                <i class="fas fa-plus-circle"></i> Assign Permission
                            </button>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search:</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter role or permission..."
                                            value="<?php echo $_GET['search'] ?? ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <a href="manage_role_permission.php?role_id=$roleId" class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    $search = $_GET['search'] ?? '';

                    $sql = "
                    SELECT 
                        rp.role_permission_id,
                        r.role_name,
                        p.permission,
                        rp.role_id
                    FROM role_permissions rp
                    JOIN roles r ON rp.role_id = r.role_id
                    JOIN permissions p ON rp.permission_id = p.permission_id
                    WHERE rp.role_id = $role_id
                ";

                    if ($search != "") {
                        $search = $conn->real_escape_string($search);
                        $sql .= " AND (r.role_name LIKE '%$search%' OR p.permission LIKE '%$search%')";
                    }

                    $sql .= " ORDER BY r.role_name ASC";

                    $result = $conn->query($sql);

                    if (!$result) {
                        die("SQL Error: " . $conn->error . "<br> Query: " . $sql);
                    }
                    ?>

                    <!-- Display List -->
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">

                                    <div>
                                        <?php echo $row['permission']; ?>
                                    </div>

                                    <div class="action-icons">
                                        <!-- DELETE -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" onclick="openDeleteModal(
                                       'role_permission_delete.php?id=<?php echo $row['role_permission_id']; ?>&roleId=<?= $role_id ?>',
                                       'Are you sure you want to remove this permission from role?'
                                       )"></i>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No permission mappings found.</p>
                    <?php endif; ?>

                </div>

                <!-- Add / Edit Modal -->
                <div class="modal fade" id="permissionModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="role_permission_save.php">

                                <div class="modal-header bg-primary text-white">
                                    <h3 id="modalTitle">Assign Permission</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <input type="hidden" name="rolePermissionId" id="rolePermissionId">
                                    <input type="hidden" name="roleId" id="roleId" value="<?= $role_id ?>">
                                    <label>Permission</label>
                                    <select class="form-control" name="permissionId" id="permissionId" required>
                                        <option value="">Select Permission</option>
                                        <?php
                                        $perms = $conn->query("SELECT * FROM permissions ORDER BY permission");
                                        while ($p = $perms->fetch_assoc()):
                                            ?>
                                            <option value="<?= $p['permission_id'] ?>"><?= $p['permission'] ?></option>
                                        <?php endwhile; ?>
                                    </select>

                                </div>

                                <div class="modal-footer">
                                    <a href="manage_role_permission.php?role_id=<?=$role_id?>"class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                                    <button class="btn btn-primary" id="saveBtn">Save</button>
                                </div>

                            </form>

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
            document.getElementById('modalTitle').innerText = "Assign Permission to Role";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('rolePermissionId').value = "";
            // document.getElementById('roleId').value = "";
            document.getElementById('permissionId').value = "";

            modalPermission.show();
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