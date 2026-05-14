<?php

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
    <title>Assign Menus to Roles </title>
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

                <div id="globalMsg" class="global-msg"></div>

                

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder"><?php echo $role_name ?> - Assign Menus</h3>
                        <div class="d-flex align-items-center " style="gap: 9px;">
                            <a href="manage_roles.php" class="btn btn-primary">Back To
                                Manage Roles</a>
                            <button class="btn btn-primary" id="addMappingBtn">
                                <i class="fas fa-plus-circle"></i> Assign Menu
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
                                            placeholder="Enter role or menu name..."
                                            value="<?php echo $_GET['search'] ?? ''; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <a href="manage_role_menus.php" class="btn btn-secondary">
                                        <i class="fas fa-times-circle"></i> Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    $search = $_GET['search'] ?? '';

                    // Correct query with JOIN
                    $sql = " SELECT   rm.role_menu_id, r.role_name, m.menu, rm.role_id FROM role_menus rm JOIN roles r ON rm.role_id = r.role_id JOIN menus m ON rm.menu_id = m.menu_id WHERE rm.role_id = $role_id";

                    if ($search != "") {
                        $search = $conn->real_escape_string($search);
                        $sql .= " AND m.menu LIKE '%$search%'";
                    }

                    $sql .= " ORDER BY m.menu ASC";


                    $result = $conn->query($sql);
                    ?>

                    <!-- Display List -->
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">

                                    <div>

                                        <?php echo $row['menu']; ?>
                                    </div>

                                    <div class="action-icons">
                                        <!-- Delete -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" onclick="openDeleteModal(
                                           'role_menu_delete.php?id=<?php echo $row['role_menu_id']; ?>&roleId=<?= $role_id ?>',
                                           'Are you sure you want to remove this menu from this role?'
                                           )"></i>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No mappings found.</p>
                    <?php endif; ?>

                

                <!-- ADD / EDIT Modal -->
                <div class="modal fade" id="mappingModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <form method="POST" action="role_menu_save.php">

                                <div class="modal-header bg-primary text-white">
                                    <h3 id="modalTitle">Assign Menu</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">

                                    <input type="hidden" name="roleMenuId" id="roleMenuId">
                                    <input type="hidden" name="roleId" id="roleId" value="<?= $role_id ?>">
                                    <label>Menu</label>
                                    <select class="form-control" name="menuId" id="menuId" required>
                                        <option value="">Select Menu</option>
                                        <?php
                                        $menus = $conn->query("SELECT * FROM menus ORDER BY menu");
                                        while ($m = $menus->fetch_assoc()):
                                            ?>
                                            <option value="<?= $m['menu_id'] ?>"><?= $m['menu'] ?></option>
                                        <?php endwhile; ?>
                                    </select>

                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
        const modalMapping = new bootstrap.Modal(document.getElementById('mappingModal'));

        // ADD MAPPING
        document.getElementById('addMappingBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Assign Menu to Role";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('roleMenuId').value = "";
            document.getElementById('menuId').value = ""; // reset menu

            modalMapping.show();
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