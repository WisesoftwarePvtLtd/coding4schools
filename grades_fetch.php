<style>
    .grade-card {
        display: block;
        background: #2ea3f2;
        border-radius: 5px;
        height: 212px;
        text-align: center;
        color: white;
        padding-top: 40px;
        position: relative;
        text-decoration: none;
        transition: 0.3s;
    }

    .grade-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .grade-icon {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background: #2ea3f2;
        width: 70px;
        height: 70px;
        border-radius: 59%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }

    .grade-title {
        font-size: 18px;
        font-weight: 600;
        margin-top: 50px;
    }

    .blue-card {
        background: #2ea3f2;
        ;
    }

    .orange-card {
        background: #ff5f45;
    }

    .manage-icons {
        position: absolute;
        top: 10px;
        right: 22px;
        display: flex;
        gap: 7px;
        z-index: 2;
    }

    .manage-icons i {
        font-size: 20px;
        cursor: pointer;
        border-radius: 4px;
    }

    .manage-icons a {
        font-size: 20px;
        cursor: pointer;
        border-radius: 4px;
        margin-top: -5px;
    }
</style>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config.php";
include "standard_constants.php";

// $search = isset($_GET['search']) ? trim($_GET['search']) : "";
// $year   = isset($_GET['year']) ? trim($_GET['year']) : "";

// // =====================================
// // BUILD DYNAMIC QUERY
// // =====================================
// $sql = "SELECT grade_id, grade_name, academic_year FROM grades WHERE 1=1";
// $params = [];
// $types  = "";

// // Search filter
// if ($search !== "") {
//     $sql .= " AND grade_name LIKE ?";
//     $params[] = "%{$search}%";
//     $types   .= "s";
// }

// // Year filter
// if ($year !== "") {
//     $sql .= " AND academic_year = ?";
//     $params[] = $year;
//     $types   .= "s";
// }

// $sql .= " ORDER BY grade_id DESC";

// // Prepare statement
// $stmt = mysqli_prepare($conn, $sql);

// // Bind parameters if any exist
// if (!empty($params)) {
//     mysqli_stmt_bind_param($stmt, $types, ...$params);
// }

// mysqli_stmt_execute($stmt);
// $query = mysqli_stmt_get_result($stmt);
// mysqli_stmt_close($stmt);

// // =====================================
// // OUTPUT RESULTS
// // =====================================
// if ($query->num_rows > 0) {

//     while ($row = $query->fetch_assoc()) {
//         $id = $row['grade_id'];
//         $name = $row['grade_name'];
//         $academicYear = $row['academic_year'];
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$year = isset($_GET['year']) ? trim($_GET['year']) : "";

$userType = $_SESSION['LoggedInUserType'] ?? '';
$userId = $_SESSION['LoggedInUserId'] ?? 0;
$schoolId = $_SESSION['LoggedInSchoolId'] ?? 0; // SELECTED SCHOOL

$params = [];
$types = "";

/* ================================
   TEACHER → ONLY ASSIGNED GRADES
================================ */
if ($userType == TEACHER) {

    $sql = "
        SELECT 
            g.grade_id,
            g.grade_name,
            g.grade_number,
            g.academic_year,
            GROUP_CONCAT(DISTINCT s.section_name SEPARATOR ', ') AS sections
        FROM section_teachers st
        INNER JOIN grades g ON g.grade_id = st.grade_id
        INNER JOIN sections s ON s.section_id = st.section_id
        INNER JOIN teachers t ON t.teacher_id = st.teacher_id
       WHERE t.user_id = ? AND g.school_id = ?
    ";

    $params[] = $userId;
    $params[] = $schoolId;
    $types .= "ii";

    if ($search !== "") {
        $sql .= " AND g.grade_name LIKE ?";
        $params[] = "%{$search}%";
        $types .= "s";
    }

    if ($year !== "") {
        $sql .= " AND g.academic_year = ?";
        $params[] = $year;
        $types .= "s";
    }

    $sql .= "
        GROUP BY g.grade_id
        ORDER BY g.grade_number ASC
    ";

    /* ================================
       OTHER USERS → ALL GRADES
    ================================ */
} else {

    $sql = "SELECT grade_id, grade_name, grade_number, academic_year FROM grades WHERE school_id = ?";
    $params[] = $schoolId;
    $types .= "i";
    if ($search !== "") {
        $sql .= " AND grade_name LIKE ?";
        $params[] = "%{$search}%";
        $types .= "s";
    }

    if ($year !== "") {
        $sql .= " AND academic_year = ?";
        $params[] = $year;
        $types .= "s";
    }

    $sql .= " ORDER BY grade_number";
}

/* ================================
   EXECUTE
================================ */
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("SQL ERROR: " . mysqli_error($conn));
}

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

/* ================================
   OUTPUT
================================ */
if ($query->num_rows > 0) {

    while ($row = $query->fetch_assoc()) {

        $id = $row['grade_id'];
        $name = $row['grade_name'];
        $gradeNumber = $row['grade_number'];
        $academicYear = $row['academic_year'];
        $sections = $row['sections'] ?? '';
        $colorClass = ($gradeNumber % 2 == 0) ? "orange-card" : "blue-card";
        ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 mt-4">

            <a href="manage-section.php?grade=<?php echo $id; ?>" class="grade-card <?php echo $colorClass; ?>">

                <div class="grade-icon <?php echo $colorClass; ?>">
                    <i class="fas fa-users"></i>

                </div>

                
                <div class="grade-title">
                    <?php echo htmlspecialchars($name); ?>
                </div>
                <div class="manage-icons">
                    <?php if (userHasPermission(MANAGE_SECTION)) { ?>
                        <a href="manage-section.php?grade=<?php echo $id; ?>" data-bs-toggle="tooltip" title="Manage Sections">
                            <i class="fas fa-tools text-white"></i>
                        </a>
                    <?php } ?>
                    <?php if (userHasPermission(GRADE_EDIT)) { ?>
                        <i class="fas fa-edit text-white"
                            onclick="editGrade(<?php echo $id; ?>, '<?php echo addslashes($name); ?>', '<?php echo addslashes($gradeNumber); ?>', '<?php echo addslashes($academicYear); ?>')"
                            data-bs-toggle="tooltip" title="Edit Grade"></i>
                    <?php } ?>
                    <?php if (userHasPermission(GRADE_VIEW_ICON)) { ?>
                        <i class="fas fa-eye text-white"
                            onclick="viewGrade(<?php echo $id; ?>, '<?php echo addslashes($name); ?>', '<?php echo addslashes($gradeNumber); ?>', '<?php echo addslashes($academicYear); ?>')"
                            data-bs-toggle="tooltip" title="View Grade"></i>
                    <?php } ?>
                    <?php if (userHasPermission(GRADE_DELETE)) { ?>
                        <i class="fas fa-trash text-white" onclick="deleteGrade(<?php echo $id; ?>)" data-bs-toggle="tooltip"
                            title="Delete Grade"></i>
                    <?php } ?>
                </div>


            </a>

        </div>
        <?php
    }

} else {
    echo "<div class='text-center ' style='margin-left: 44%;'>No grades found</div>";
}

function userHasPermission($permissionId)
{

    if (!isset($_SESSION['UserPermissions'])) {
        return false;
    }

    return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}
?>