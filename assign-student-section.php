<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$grade = isset($_GET['grade']) ? htmlspecialchars($_GET['grade']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign Student to Section </title>
</head>
<?php
include "config.php";

// GET grade & section (already sanitized earlier)
$grade = isset($_GET['grade']) ? $conn->real_escape_string($_GET['grade']) : '';
$section = isset($_GET['section']) ? intval($_GET['section']) : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

// ----------------------------
// 1) Fetch grade & section names (single query)
// ----------------------------
$grade_name = "";
$section_name = "";
$section_gender = "";

if ($grade !== "" && $section > 0) {
    // Use prepared statement for safety
    $stmtMeta = $conn->prepare("
        SELECT g.grade_name, sec.section_name, sec.gender
        FROM grades g, sections sec
        WHERE g.grade_id = ? AND sec.section_id = ?
        LIMIT 1
    ");
    $stmtMeta->bind_param("ii", $grade, $section);
    $stmtMeta->execute();
    $stmtMeta->bind_result($gname, $sname, $sgender);
    if ($stmtMeta->fetch()) {
        $grade_name = $gname;
        $section_name = $sname;
        $section_gender = $sgender;
    }

    $stmtMeta->close();
}

// ----------------------------
// 2) Build students query
// ----------------------------
$sql = "
SELECT 
    s.student_id,
    s.student_number,
    s.student_name,
    s.family_name,
    s.gender,
    u.username,
    u.full_name
FROM section_students ss
JOIN students s ON ss.student_id = s.student_id
JOIN users u ON s.user_id = u.user_id
WHERE ss.grade_id = ? AND ss.section_id = ?
";

// add search filter if needed
$params = [];
$types = "ii";
$params[] = $grade;
$params[] = $section;

if ($search !== "") {
    $sql .= " AND (s.student_name LIKE ? OR s.family_name LIKE ? OR s.student_number LIKE ? OR u.full_name LIKE ? OR u.username LIKE ?)";
    $like = '%' . $search . '%';
    // append types and params for the five LIKE placeholders
    $types .= str_repeat("s", 5);
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

$sql .= " ORDER BY s.student_name ASC";

// prepare and bind dynamically
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// bind parameters dynamically
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result(); // mysqli_result

// now $res can be looped safely below
?>


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
                        <h3 class="fw-bolder ">
                            <?php echo htmlspecialchars($grade_name ?: $grade); ?> -
                            <?php echo htmlspecialchars($section_name ?: $section); ?> -
                            <?php echo htmlspecialchars($section_gender ?: ''); ?> - Manage Students
                        </h3>
                        <a href="manage-section.php?grade=<?php echo $grade; ?>" class="btn btn-primary">Back To Section</a>
                    </div>

                    <!-- Filter Box -->
                    <?php if (userHasPermission(SECTION_STUDENT_SEARCH)) { ?>
                        <div class="filter-box mb-4 ">
                            <form method="GET">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="input-group" style="gap:25px;">
                                            <input type="hidden" id="sectionJS" name="section"
                                                value="<?php echo $section; ?>">
                                            <input type="hidden" id="gradeJS" name="grade" value="<?php echo $grade; ?>">
                                            <span class="fw-semibold">Search Student:</span>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Enter student name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                        <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                        <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                    <?php if (userHasPermission(SECTION_STUDENT_LIST_VIEW)) { ?>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered common-table">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Student Number</th>
                                        <th>Full Name</th>
                                        <th>Gender</th>
                                        <?php if (userHasPermission(REMOVE_STUDENT_FROM_SECTION)) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    if ($res->num_rows > 0) {
                                        while ($row = $res->fetch_assoc()) {
                                            $full_name = $row['student_name'] . " " . $row['family_name'];
                                            ?>
                                            <tr>
                                                <td><?php echo $row['username']; ?></td>
                                                <td><?php echo $row['student_number']; ?></td>
                                                <td><?php echo $full_name; ?></td>
                                                <td><?php echo ucfirst($row['gender']); ?></td>

                                                <?php if (userHasPermission(REMOVE_STUDENT_FROM_SECTION)) { ?>
                                                    <td>
                                                        <i class="fas fa-trash text-danger"
                                                            onclick="deleteSectionFormStudent(<?php echo $row['student_id']; ?>)"
                                                            style="cursor:pointer;" title="Remove from Section"></i>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No students found</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>




                
            </div>
        </div>
        <script>

            function deleteSectionFormStudent(id) {
                openDeletePopup(id, "section_student_delete.php", "Student Remove Form Section Deleted Successfully!");
            }

            // DELETE (Confirmation + Success Msg)

            let deleteID = null;
            let deleteUrl = null;
            let deleteSuccessMsg = "";

            // Open Delete Modal
            function openDeletePopup(id, apiUrl, msg) {
                deleteID = id;
                deleteUrl = apiUrl;
                deleteSuccessMsg = msg;

                let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
            function confirmDelete() {

                ajaxPost(deleteUrl, { id: deleteID }, function (res) {

                    if (res.trim() === "success") {
                        showMessage(deleteSuccessMsg, "success");
                        let section = document.getElementById("sectionJS").value;
                        let grade = encodeURIComponent(document.getElementById("gradeJS").value);

                        window.location.href =
                            "assign-student-section.php?section=" + section + "&grade=" + grade;

                    } else {
                        showMessage("Error deleting! " + res, "error");
                    }

                });

                bootstrap.Modal.getInstance(
                    document.getElementById('deleteConfirmModal')
                ).hide();
            }

        </script>
</body>

</html>