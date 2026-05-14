<?php
session_start();
include "standard_constants.php";
include "config.php";
$school_id = $_SESSION['LoggedInSchoolId'] ?? 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$like = "%$search%";

// SQL WITH JOIN
$sql = "
    SELECT 
        t.teacher_id,
        t.teacher_name,
        t.gender,
        u.user_id,
        u.username,
        u.password_hash,
        u.full_name
    FROM teachers t
    INNER JOIN users u ON t.user_id = u.user_id
    WHERE u.school_id = ?
";

// Search filter
if ($search != "") {
    $sql .= " AND (
                t.teacher_name LIKE ?
                OR u.username LIKE ?
                OR u.full_name LIKE ?
              )
              ORDER BY t.teacher_id DESC";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $school_id,$like, $like, $like);

} else {
    $sql .= " ORDER BY t.teacher_id DESC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $school_id);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result->num_rows > 0) {

    while ($t = mysqli_fetch_assoc($result)) {

        // Decrypt password
        $key = DECRYPT_KEY;
        $iv = substr(hash("sha256", $key), 0, 16);

        $decryptedPassword = openssl_decrypt(
            $t['password_hash'],
            "AES-256-CBC",
            $key,
            0,
            $iv
        );


        ?>
        <div class="grade-box mb-3">
            <div class="grade-row">

                <div>
                    <?php if ($t['gender'] === "Male") { ?>
                        <i class="fas fa-male" style="font-size: 28px; color:#2196f3;"></i>
                    <?php } else { ?>
                        <i class="fas fa-female" style="font-size: 28px; color:#e91e63;"></i>
                    <?php } ?>

                    <strong><?= $t['full_name']; ?></strong><br>
                    Username: <?= $t['username']; ?>
                   
                </div>

                <div class="action-icons">
                    <?php if (userHasPermission(TEACHER_EDIT)) { ?>
                        <i class="fas fa-edit text-primary" onclick="editTeacher(
                           '<?= $t['user_id'] ?>',
                           '<?= addslashes($t['full_name']) ?>',
                           '<?= addslashes($t['username']) ?>',
                           '<?= addslashes($decryptedPassword) ?>',
                           '<?= $t['gender'] ?>'
                       )" title="Edit teacher"></i>
                    <?php } ?>
                    <?php if (userHasPermission(TEACHER_VIEW_ICON)) { ?>
                        <i class="fas fa-eye text-primary" onclick="viewTeacher(
                           '<?= $t['user_id'] ?>',
                           '<?= addslashes($t['full_name']) ?>',
                           '<?= addslashes($t['username']) ?>',
                           '<?= addslashes($decryptedPassword) ?>',
                           '<?= $t['gender'] ?>'
                       )" title="View teacher"></i>
                    <?php } ?>
                    <?php if (userHasPermission(TEACHER_DELETE)) { ?>
                        <i class="fas fa-trash text-danger" onclick="deleteTeacher(<?= $t['user_id'] ?>)" title="Delete teacher"></i>
                    <?php } ?>

                </div>

            </div>
        </div>
        <?php
    }

} else {
    echo "<div class='text-center text-muted mt-4'>No teachers found</div>";
}
function userHasPermission($permissionId)
{

    if (!isset($_SESSION['UserPermissions'])) {
        return false;
    }

    return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}
?>