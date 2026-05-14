<?php
session_start();
include 'header.php';
include 'config.php';
$user_id = intval($_GET['user_id'] ?? 0);
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

if ($user_id <= 0)
    die("Invalid Student");
// ---------------- FETCH STUDENT DATA --------------
$q = $conn->prepare("
    SELECT 
        u.user_id,
        u.username,
        u.password_hash,
        s.student_number,
        s.student_name,
        s.family_name,
        s.gender,
        ss.grade_id,
        ss.section_id
    FROM users u
    JOIN students s ON u.user_id = s.user_id
    LEFT JOIN section_students ss ON s.student_id = ss.student_id
    WHERE u.user_id = ?
");
$q->bind_param("i", $user_id);
$q->execute();
$data = $q->get_result()->fetch_assoc();
$q->close();

if (!$data)
    die("Student not found");
// Split name
$student_name = "";
$father_name = "";
if (!empty($data['student_name'])) {
    $parts = explode(" ", $data['student_name'], 2);
    $student_name = $parts[0];
    $father_name = $parts[1] ?? "";
}

// Decrypt password
$key = DECRYPT_KEY;
$iv = substr(hash("sha256", $key), 0, 16);

$decryptedPassword = openssl_decrypt(
    $data['password_hash'],
    "AES-256-CBC",
    $key,
    0,
    $iv
);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Student</title>
</head>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">
            <!-- Sidebar -->
            <div class="sidebar"><?php include 'menus.php'; ?></div>
            <div class="main-area">
                <div id="globalMsg" class="global-msg"></div>
                <div class="card shadow">
                    <div class="card-header" style="background:#1da1f2; color:white;">
                        <h4 class="m-0">Edit Student</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="student_update.php">
                            <input type="hidden" name="id" value="<?= $user_id ?>">
                            <input type="hidden" id="userSchoolId" name="userSchoolId" value="<?= $userSchoolId ?>">

                            <label class="form-label">Username *</label>
                            <input type="text" name="username" class="form-control mb-3"
                                value="<?= htmlspecialchars($data['username']) ?>" maxlength="50"  
                                        onkeydown="restrictSpace(event)" onpaste="restrictSpace(event)" required>
                            <label class="form-label">Student Number *</label>
                            <input type="text" name="student_number" class="form-control mb-3"
                                value="<?= htmlspecialchars($data['student_number']) ?>" required>
                            <label class="form-label">Password <span class="text-danger">*</span></label>

                            <div class="input-group mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Enter password"
                                    id="studentPassword" required
                                    value="<?= $decryptedPassword ?>">
                                <span class="input-group-text bg-primary" style="cursor:pointer;"
                                    onclick="togglePassword('studentPassword', this)">
                                    <i class="fas fa-eye-slash text-white"></i>
                                </span>
                            </div>
                            <label class="form-label">Student Name *</label>
                            <input type="text" name="student_name" class="form-control mb-3"
                                value="<?= htmlspecialchars($student_name) ?>" required>
                            <label class="form-label">Father Name *</label>
                            <input type="text" name="father_name" class="form-control mb-3"
                                value="<?= htmlspecialchars($father_name) ?>" required>
                            <label class="form-label">Family Name *</label>
                            <input type="text" name="family_name" class="form-control mb-3"
                                value="<?= htmlspecialchars($data['family_name']) ?>" required>
                            <label class="form-label">Gender *</label>
                            <select name="gender" class="form-control mb-3" required>
                                <option value="">Select</option>
                                <option value="Male" <?= ($data['gender'] ?? '') === "Male" ? "selected" : "" ?>>Male
                                </option>
                                <option value="Female" <?= ($data['gender'] ?? '') === "Female" ? "selected" : "" ?>>Female
                                </option>
                            </select>
                            <label class="form-label">Grade *</label>
                            <select name="grade" id="gradeSelect" class="form-control mb-3"
                                onchange="onGradeSelect(this.value)" required>
                                <option value="">Select Grade</option>
                                <?php
                                $grades = $conn->query("SELECT grade_id, grade_name FROM grades ORDER BY grade_name");
                                while ($g = $grades->fetch_assoc()) {
                                    $sel = ($g['grade_id'] == $data['grade_id']) ? "selected" : "";
                                    echo "<option value='{$g['grade_id']}' $sel>{$g['grade_name']}</option>";
                                }
                                ?>
                            </select>
                            <label class="form-label">Section</label>
                            <select name="section" id="sectionSelect" class="form-control mb-4">
                                <option value="">Select Section</option>
                            </select>
                            <div class="text-end">
                                <a href="manage-student.php" class="btn btn-secondary">Cancel</a>
                                <button class="btn btn-primary">Update Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function onGradeSelect(grade_id, selected_section = "<?= $data['section_id'] ?>") {
            fetch("sections_get.php?grade=" + grade_id + "&section=" + selected_section)
                .then(res => res.text())
                .then(html => document.getElementById("sectionSelect").innerHTML = html);
        }
        onGradeSelect(document.getElementById("gradeSelect").value);
    </script>
    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>

    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>
</body>

</html>