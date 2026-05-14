<?php
session_start();

$school_id = intval($_GET['school_id'] ?? 0);

if ($school_id <= 0) {
    header("Location: select_school.php");
    exit();
}

// ✅ Selected school ko session me set karo
$_SESSION['LoggedInSchoolId'] = $school_id;

// 👉 Ab dashboard par bhejo
header("Location: dashboard_siteadmin.php");
exit();