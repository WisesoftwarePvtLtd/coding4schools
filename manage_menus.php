<?php

include 'header.php';
include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My menus </title>
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
                        <h3 class="fw-bolder">Manage Menus</h3>
                        <button class="btn btn-primary" id="addmenuBtn"><i class="fas fa-plus-circle"></i> Add
                            Menu</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4 ">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Menus</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter menu name...">
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

                    $menuQuery = "SELECT menu_id, menu, menu_order,menu_icon, menu_link_path FROM menus WHERE 1=1";

                    if ($search !== "") {
                        $menuQuery .= " AND menu LIKE '%" . $conn->real_escape_string($search) . "%'";
                    }

                    $menuQuery .= " ORDER BY menu_order ASC";

                    $menuResult = $conn->query($menuQuery);

                    ?>
                    <!-- menu List Display Here -->
                    <?php if ($menuResult->num_rows > 0): ?>
                        <?php while ($row = $menuResult->fetch_assoc()): ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">
                                    <div>
                                        <strong class="text-primary"><i class="<?= $row['menu_icon'] ?>"></i></strong>

                                        <strong><?php echo htmlspecialchars($row['menu']); ?></strong>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($row['menu_link_path']); ?></strong>

                                    </div>

                                    <div class="action-icons">

                                        <!-- Move Up -->
                                        <a href="menu_move.php?action=up&id=<?php echo $row['menu_id']; ?>">
                                            <i class="fas fa-arrow-up text-primary" title="Move Up"></i>
                                        </a>

                                        <!-- Move Down -->
                                        <a href="menu_move.php?action=down&id=<?php echo $row['menu_id']; ?>">
                                            <i class="fas fa-arrow-down text-primary" title="Move Down"></i>
                                        </a>

                                        <!-- Edit -->
                                        <!-- <a href="#"
                                            onclick="editMenu('<?php echo $row['menu_id']; ?>', '<?php echo htmlspecialchars($row['menu']); ?>')">
                                            <i class="fas fa-edit text-primary"></i>
                                        </a> -->
                                        <a href="#"
                                            onclick="editMenu( '<?= $row['menu_id']; ?>', '<?= htmlspecialchars($row['menu']); ?>','<?= htmlspecialchars($row['menu_link_path']); ?>', '<?= htmlspecialchars($row['menu_icon']); ?>')">
                                            <i class="fas fa-edit text-primary" title="Edit Menu"></i>
                                        </a>



                                        <!-- View -->
                                        <i class="fas fa-eye text-primary" style="cursor:pointer;" title="View Menu"
                                            onclick="viewMenu('<?php echo htmlspecialchars($row['menu']); ?>','<?= htmlspecialchars($row['menu_link_path']); ?>', '<?= htmlspecialchars($row['menu_icon']); ?>')">
                                        </i>


                                        <!-- Delete -->
                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete Menu"
                                            onclick="openDeleteModal('menu_delete.php?id=<?php echo $row['menu_id']; ?>', 'Are you sure you want to delete this menu?')">
                                        </i>

                                    </div>

                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No menus found.</p>
                    <?php endif; ?>



                

                <!-- Modal -->
                <div class="modal fade text-dark" id="menuModal" tabindex="-1" >
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <form method="POST" action="menu_save.php">

                                <div class="modal-header bg-primary">
                                    <h3 class="modal-title text-white" id="modalTitle">Add Menu</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="menuId" id="menuId">
                                    <label class="form-label">Menu</label>
                                    <input type="text" class="form-control" name="menuName" id="menuName"
                                        placeholder="Enter menu name" required>
                                    <!-- Menu Link Path -->
                                    <label class="form-label mt-3">Menu Link Path</label>
                                    <input type="text" class="form-control" name="menuLinkPath" id="menuLinkPath"
                                        placeholder="Example: manage-grade.php">

                                    <!-- Menu Icon -->
                                    <label class="form-label mt-3">Menu Icon (FontAwesome class)</label>
                                    <input type="text" class="form-control" name="menuIcon" id="menuIcon"
                                        placeholder="Example: fas fa-book">
                                </div>

                                <div class="modal-footer">
                                    <a href="manage_menus.php" class="btn btn-secondary" data-bs-dismiss="modal"
                                        id="cancelbtn">Cancel</a>
                                    <button class="btn btn-primary" type="submit" id="saveBtn">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                <!-- VIEW MENU MODAL -->
                <div class="modal fade" id="viewMenuModal" tabindex="-1" >
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header bg-primary text-white">
                                <h3 class="modal-title text-white" id="modalTitle">View Menu</h3>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Menu</label>
                                <input type="text" class="form-control" name="viewMenuName" id="viewMenuName" value=""
                                    readonly>

                                <!-- Menu Link Path -->
                                <label class="form-label mt-3">Menu Link Path</label>
                                <input type="text" class="form-control" name="viewmenuLinkPath" id="viewmenuLinkPath"
                                    value="" readonly>

                                <!-- Menu Icon -->
                                <label class="form-label mt-3">Menu Icon (FontAwesome class)</label>
                                <input type="text" class="form-control" name="viewmenuIcon" id="viewmenuIcon" value=""
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
        const modalAssign = new bootstrap.Modal(document.getElementById('menuModal'));

        // ADD MENU (Reset form)
        document.getElementById('addmenuBtn').addEventListener('click', () => {
            document.getElementById('modalTitle').innerText = "Add Menu";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('menuId').value = "";
            document.getElementById('menuName').value = "";

            modalAssign.show();
        });

        // EDIT MENU (Open same modal)
        function editMenu(id, name, path, icon) {
            document.getElementById('modalTitle').innerText = "Edit Menu";
            document.getElementById('saveBtn').innerText = "Update";

            document.getElementById('menuId').value = id;
            document.getElementById('menuName').value = name;
            document.getElementById('menuLinkPath').value = path;
            document.getElementById('menuIcon').value = icon;

            modalAssign.show();
        }



        function viewMenu(menuName, menuPath, menuIcon) {
            document.getElementById("viewMenuName").value = menuName;  // <-- Correct
            document.getElementById("viewmenuLinkPath").value = menuPath;
            document.getElementById("viewmenuIcon").value = menuIcon;

            const myModal = new bootstrap.Modal(document.getElementById('viewMenuModal'));
            myModal.show();
        }


        function confirmDelete(menuId) {
            document.getElementById("deleteMenuId").value = menuId;

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        }


        document.getElementById("confirmDeleteBtn").addEventListener("click", function () {
            let id = document.getElementById("deleteMenuId").value;
            window.location.href = "menu_delete.php?id=" + id;
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