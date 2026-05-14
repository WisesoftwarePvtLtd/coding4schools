<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "config.php";
include "header.php";

$user_id = $_SESSION['LoggedInUserId'] ?? 0;

/* =========================
   TEACHER BASIC INFO
========================= */

$sql = "
SELECT 
    u.full_name,
    u.username,
    t.teacher_id
FROM users u
JOIN teachers t ON u.user_id = t.user_id
WHERE u.user_id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$teacher = $res->fetch_assoc();

$teacher_id = $teacher['teacher_id'] ?? 0;

/* =========================
   ASSIGNED GRADES
========================= */

$grades = [];

$q = "
SELECT DISTINCT g.grade_name
FROM section_teachers st
JOIN grades g ON st.grade_id = g.grade_id
WHERE st.teacher_id = ?
ORDER BY g.grade_id
";

$stmt2 = $conn->prepare($q);
$stmt2->bind_param("i", $teacher_id);
$stmt2->execute();
$res2 = $stmt2->get_result();

while ($row = $res2->fetch_assoc()) {
    $grades[] = $row['grade_name'];
}

/* =========================
   SPLIT NAME
========================= */

$nameParts = explode(" ", $teacher['full_name'] ?? "");
$first = $nameParts[0] ?? "";
$last = $nameParts[1] ?? "";
?>

<!DOCTYPE html>
<html>

<head>

    <title>Teacher Profile</title>

    <style>
        .profile-card {
            background: #f3f3f3;
            border-radius: 6px;
            padding: 11px;
            margin-bottom: 20px;
        }

        .grade-badge {
            display: inline-block;
            padding: 6px 8px;
            margin: 4px;
            border-radius: 4px;
            font-size: 18px;
        }
    </style>

</head>

<body>

    <div class="container-fluid" style="padding:30px">

        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include "menus.php"; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area">

                <div id="globalMsg" class="global-msg"></div>
                <div class="container-fluid p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bolder">
                            My Profile
                        </h3>

                    </div>

                    <div class="profile-card">

                        <div class="row profile-row">

                            <div class="col-md-3 profile-label">
                                First Name
                            </div>

                            <div class="col-md-9" style="font-weight: 600;">
                                <?php echo htmlspecialchars($first); ?>
                            </div>

                        </div>
                    </div>
                    <div class="profile-card">
                        <div class="row profile-row">

                            <div class="col-md-3 profile-label">
                                Last Name
                            </div>

                            <div class="col-md-9" style="font-weight: 600;">
                                <?php echo htmlspecialchars($last); ?>
                            </div>

                        </div>

                    </div>
                    <div class="profile-card">
                        <div class="row profile-row">

                            <div class="col-md-3 profile-label">
                                Username
                            </div>

                            <div class="col-md-9" style="font-weight: 600;">
                                <?php echo htmlspecialchars($teacher['username']); ?>
                            </div>

                        </div>
                    </div>
                    <div class="profile-card">

                        <div class="row profile-row">

                            <div class="col-md-12 profile-label">
                                Assigned Grades
                            </div>

                            <div class="col-md-12" style="font-weight: 600;margin-left: -12px;">

                                <?php
                                if (!empty($grades)) {
                                    foreach ($grades as $g) {
                                        echo '<span class="grade-badge">' . $g . '</span>';
                                    }
                                } else {
                                    echo "No grades assigned";
                                }
                                ?>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>

    </div>
    </div>

</body>

</html>