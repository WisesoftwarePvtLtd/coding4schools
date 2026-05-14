<?php
include 'standard_constants.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$roles_id = $_SESSION['LoggedInUserRoles']; // array of role IDs
$roles_id_str = implode(",", $roles_id);

if (in_array(SUPERADMIN, $roles_id)) {
    header("Location: dashboard_superadmin.php");
}elseif (in_array(SITEADMIN, $roles_id)) {
    include 'config.php'; // DB connect

    // 🔥 check school count
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM schools");
    $data = mysqli_fetch_assoc($res);
    $totalSchools = $data['total'];

    // ✅ CASE 1: No school → direct dashboard
    if ($totalSchools == 0) {
        header("Location: dashboard_siteadmin.php");
    }
    // ✅ CASE 2: Schools exist → check selection
    else {
        if (empty($_SESSION['LoggedInSchoolId'])) {
            header("Location: select_school.php");
        } else {
            header("Location: dashboard_siteadmin.php");
        }
    }

    // 🔥 Agar school select nahi kiya hai tabhi select_school pe bhejo
    // if (empty($_SESSION['LoggedInSchoolId'])) {
    //     header("Location: select_school.php");
    // } else {
    //     header("Location: dashboard_siteadmin.php");
    // }
} elseif (in_array(SCHOOLADMIN, $roles_id)) {
    header("Location: dashboard_schooladmin.php");
} elseif (in_array(TEACHER, $roles_id)) {
    header("Location: dashboard_teacher.php");
} elseif (in_array(STUDENT, $roles_id)) {
    header("Location: dashboard_student.php");
} else{
    header("Location: login.php");
}
exit();
?>