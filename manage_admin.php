<?php
include 'header.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
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

                

                    <!-- Header Row -->
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">Manage Users</h3>
                        <button class="btn btn-primary" id="addUserBtn"><i class="fas fa-plus-circle"></i> Add
                            User</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search User</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter username...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : "";

                    // Fetch only schooladmin and siteadmin users
                    $sql = "SELECT u.user_id, u.username, u.password_hash, u.user_type, s.school_name,u.school_id
                            FROM users u
                            LEFT JOIN schools s ON u.school_id = s.school_id
                            WHERE u.user_type = " . SCHOOLADMIN;

                    if ($search !== "") {
                        $sql .= " AND username LIKE '%" . $conn->real_escape_string($search) . "%'";
                    }

                    $sql .= " ORDER BY user_id DESC";
                    $res = $conn->query($sql);
                    // print_r($res);
                    ?>



                    <!-- User List -->
                    <?php if ($res->num_rows > 0): ?>
                        <?php while ($row = $res->fetch_assoc()):

                            $key = DECRYPT_KEY;
                            $iv = substr(hash("sha256", $key), 0, 16);
                            $decryptedPassword = openssl_decrypt($row['password_hash'], "AES-256-CBC", $key, 0, $iv);


                            ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between">
                                    <strong>
                                        <?= htmlspecialchars($row['username']); ?>
                                        
                                        <?php if (!empty($row['school_name'])) { ?>
                                            - <?= htmlspecialchars($row['school_name']); ?>
                                        <?php } ?>

                                    </strong>
                                    <div class="action-icons">
                                        <i class="fas fa-edit text-primary" style="cursor:pointer;" title="Edit Admin"
                                            onclick="editUser('<?= $row['user_id']; ?>','<?= htmlspecialchars($row['username']); ?>','<?= $row['user_type']; ?>','<?= htmlspecialchars($decryptedPassword); ?>','<?= $row['school_id']; ?>')">
                                        </i>

                                        <i class="fas fa-trash text-danger" style="cursor:pointer;" title="Delete admin"
                                            onclick="openDeleteModal('admin_delete.php?id=<?= $row['user_id']; ?>', 'Are you sure you want to delete this user?')">
                                        </i>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No users found.</p>
                    <?php endif; ?>

                

                <!-- USER MODAL -->
                <div class="modal fade text-dark" id="userModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="admin_save.php">

                                <div class="modal-header bg-primary">
                                    <h3 class="modal-title text-white">Add User</h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="userId" id="userId">

                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control mb-3" name="username" id="username" required>

                                    <label class="form-label">Password</label>
                                    <div class="input-group mb-3">
                                        <input type="password" class="form-control" name="password" id="password">
                                        <span class="input-group-text bg-primary" style="cursor:pointer;"
                                            onclick="togglePassword('password', this)">
                                            <i class="fas fa-eye text-white"></i>
                                        </span>
                                    </div>
                                    <label class="form-label">School</label>
                                    <select class="form-control mb-3" name="school_id" id="school_id">

                                        <option value="">Select School</option>

                                        <?php
                                        $schoolQuery = $conn->query("SELECT school_id, school_name FROM schools ORDER BY school_name");

                                        while ($school = $schoolQuery->fetch_assoc()) {
                                            ?>

                                            <option value="<?= $school['school_id']; ?>">
                                                <?= htmlspecialchars($school['school_name']); ?>
                                            </option>

                                        <?php } ?>

                                    </select>
                                    <label class="form-label">User Type</label>
                                    <select class="form-control mb-3" name="user_type" id="user_type" required>
                                        <option value="<?php echo SCHOOLADMIN; ?>">School Admin</option>
                                        <!-- <option value="<?php echo SITEADMIN; ?>">Site Admin</option>
                                        <option value="<?php echo SUPERADMIN; ?>">Super Admin</option> -->
                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary" type="submit">Save</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const userModal = new bootstrap.Modal(document.getElementById("userModal"));

        // ADD USER BUTTON
        document.getElementById("addUserBtn").addEventListener("click", () => {
            document.querySelector("#userModal .modal-title").innerText = "Add User";
            document.getElementById("userId").value = "";
            document.getElementById("username").value = "";
            document.getElementById("password").value = "";
            document.getElementById("user_type").value = "schooladmin"; // default value
            userModal.show();
        });

        // EDIT USER
        function editUser(id, username, user_type, decryptedPassword, school_id) {
            document.querySelector("#userModal .modal-title").innerText = "Edit User";
            document.getElementById("userId").value = id;
            document.getElementById("username").value = username;
            document.getElementById("password").value = decryptedPassword;
            document.getElementById("user_type").value = user_type; // set existing type
            document.getElementById("school_id").value = school_id;
            userModal.show();
        }
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