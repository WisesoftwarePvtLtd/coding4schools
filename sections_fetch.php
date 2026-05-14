<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config.php";
include "standard_constants.php";

$grade_id = filter_input(INPUT_GET, 'grade', FILTER_VALIDATE_INT);
$search = $_GET['search'] ?? "";
// print_r($search);die;
// Invalid or missing grade
if (!$grade_id) {
    die("Invalid Grade");
}

$userType = $_SESSION['LoggedInUserType'] ?? '';
$userId   = $_SESSION['LoggedInUserId'] ?? 0;
$like   = "%{$search}%";

if ($userType == TEACHER) {

    // ✅ ONLY SECTIONS ASSIGNED TO THIS TEACHER
    $sql = "
        SELECT DISTINCT 
            s.section_id,
            s.section_name,
            s.gender,
            CONCAT(s.section_name, ' - ', s.gender) AS full_text
        FROM section_teachers st
        INNER JOIN sections s ON s.section_id = st.section_id
        INNER JOIN teachers t ON t.teacher_id = st.teacher_id
        WHERE st.grade_id = ?
          AND t.user_id = ?
          AND (
                s.section_name LIKE ?
                OR s.gender LIKE ?
                OR CONCAT(s.section_name, ' - ', s.gender) LIKE ?
              )
        ORDER BY s.section_id DESC
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iisss", $grade_id, $userId, $like, $like, $like);

} else {

$sql = "SELECT section_id, section_name, gender,
        CONCAT(section_name, ' - ', gender) AS full_text
        FROM sections
        WHERE grade_id = ?
          AND (
                section_name LIKE ?
                OR gender LIKE ?
                OR CONCAT(section_name, ' - ', gender) LIKE ?
              )
        ORDER BY section_id DESC";

$stmt = mysqli_prepare($conn, $sql);

$like = "%{$search}%";
mysqli_stmt_bind_param($stmt, "isss", $grade_id, $like, $like, $like);
}
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
// print_r($result);die;
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $section_id = $row['section_id'];
        $name = htmlspecialchars($row['section_name']);
        $gender = htmlspecialchars(ucfirst($row['gender']));
        ?>
        <div class="grade-box mb-3">
            <div class="grade-row">
                <div>
                    <strong><?= $name; ?> - <?= $gender; ?></strong>
                </div>

                <div class="action-icons">
                    <?php if (userHasPermission(MANAGE_COURSE_ICON)) { ?>
                        <!-- Manage Books -->
                        <a href="assign-course-section.php?section=<?= $section_id ?>&grade=<?= $grade_id ?>">
                            <i class="fas fa-book text-primary" title="Manage Courses"></i>
                        </a>
                    <?php } ?>
                    <?php if (userHasPermission(MANAGE_TEACHERS_ICON)) { ?>
                        <!-- Manage Teachers -->
                        <a href="assign-teacher-section.php?section=<?= $section_id ?>&grade=<?= $grade_id ?>">
                            <i class="fas fa-chalkboard-teacher text-primary" title="Manage Teachers"></i>
                        </a>
                    <?php } ?>
                    <?php if (userHasPermission(MANAGE_STUDENTS_ICON)) { ?>
                        <!-- Manage Students -->
                        <a href="assign-student-section.php?section=<?= $section_id ?>&grade=<?= $grade_id ?>">
                            <i class="fas fa-user-graduate text-primary" title="Manage Students"></i>
                        </a>
                    <?php } ?>
                    <?php if (userHasPermission(EDIT_SECTION)) { ?>
                        <!-- Edit -->
                        <i class="fas fa-edit text-primary"
                            onclick="editSection('<?= $section_id ?>','<?= $name ?>','<?= strtolower($row['gender']) ?>')"
                            title="Edit Section"></i>
                    <?php } ?>
                    <?php if (userHasPermission(SECTION_VIEW_ICON)) { ?>
                    <!-- View -->
                    <i class="fas fa-eye text-primary"
                        onclick="viewSection('<?= $section_id ?>','<?= $name ?>','<?= strtolower($row['gender']) ?>')"
                        title="View Section"></i>
                     <?php } ?>
                    <?php if (userHasPermission(DELETE_SECTION)) { ?>
                        <!-- Delete -->
                        <i class="fas fa-trash text-danger" onclick="deleteSection(<?= $section_id ?>)" title="Delete Section"></i>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='text-center text-muted mt-4'>No sections found</div>";
}

mysqli_stmt_close($stmt);

function userHasPermission($permissionId)
{

    if (!isset($_SESSION['UserPermissions'])) {
        return false;
    }

    return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}
?>