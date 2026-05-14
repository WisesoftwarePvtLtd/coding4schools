<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// print_r($_POST);die;
require "config.php";
include 'standard_constants.php';
$username = $_POST['username'];
$password = $_POST['password'];
$login_as = isset($_POST['login_as']) ? strtolower($_POST['login_as']) : "";
// $login_as = "";
$student_id = "";
$teacher_id = "";
$fromPage = basename(parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_PATH));


// --------------------------------------------------------
// 1. Fetch User
// --------------------------------------------------------
if ($fromPage === "login.php") {
    $studentType = STUDENT;
    $teacherType = TEACHER;
    $sql = "SELECT user_id, username, password_hash, user_type,school_id FROM users WHERE username = ? AND user_type IN (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $username, $studentType, $teacherType);
    $stmt->execute();
    $stmt->bind_result($user_id, $username_db, $password_hash, $user_type, $school_id);
    if (!$stmt->fetch()) {
        header("Location: login.php?error=Invalid Username or Password");
        exit();
    }
    $stmt->close();
} elseif ($fromPage === "admin.php") {
    
     $sql = "SELECT user_id, username, password_hash, user_type,school_id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $username_db, $password_hash, $user_type, $school_id);
    if (!$stmt->fetch()) {
        header("Location: admin.php?error=Invalid Username or Password");
        exit();
    }
    $stmt->close();

}



// --------------------------------------------------------
// 2. Decrypt Password
// --------------------------------------------------------
$key = DECRYPT_KEY;
$iv = substr(hash("sha256", $key), 0, 16);

$decryptedPassword = openssl_decrypt(
    $password_hash,
    "AES-256-CBC",
    $key,
    0,
    $iv
);

if ($password !== $decryptedPassword) {

    header("Location: login.php?error=Invalid Username or Password");
    exit();
}


// --------------------------------------------------------
// 3. Fetch All Roles Assigned to User
// --------------------------------------------------------
$sqlGetUserRoles = "
    SELECT r.role_id, r.role_name
    FROM user_roles ur
    INNER JOIN roles r ON r.role_id = ur.role_id
    WHERE ur.user_id = ?
";
$qryGetUserRoles = $conn->prepare($sqlGetUserRoles);
$qryGetUserRoles->bind_param("i", $user_id);
$qryGetUserRoles->execute();
$qryGetUserRoles->store_result();
$qryGetUserRoles->bind_result($db_role_id, $db_role_name);

$roles = [];
while ($qryGetUserRoles->fetch()) {

    switch ($db_role_id) {

        case STUDENT:
            $login_as = STUDENT;
            $studentsql = "SELECT student_id FROM students WHERE user_id = $user_id";
            $stdresult = $conn->query($studentsql);

            if ($stdresult && $row = $stdresult->fetch_assoc()) {
                $student_id = $row['student_id'];
            } else {
                $student_id = null;
            }
            break;

        case TEACHER:
            $login_as = TEACHER;
            $teachersql = "SELECT teacher_id FROM teachers WHERE user_id = $user_id";
            $result = $conn->query($teachersql);

            if ($result && $row = $result->fetch_assoc()) {
                $teacher_id = $row['teacher_id'];
            } else {
                $teacher_id = null;
            }
            break;

        case SCHOOLADMIN:
            $login_as = SCHOOLADMIN;
            break;

        case SITEADMIN:
            $login_as = SITEADMIN;
            break;

        case SUPERADMIN:
            $login_as = SUPERADMIN;
            break;

        default:
            if ($login_as === "") {
                $login_as = UN_AUTHORIZED_USER;
            }

    }

    $roles[strtolower($db_role_name)] = $db_role_id;
}
$qryGetUserRoles->close();

if ($login_as === UN_AUTHORIZED_USER) {
    header("Location: login.php?error=You are not an authorized user");
    exit();
}

// --------------------------------------------------------
// Convert all role IDs → Comma separated string
// --------------------------------------------------------
$all_role_ids = implode(",", array_values($roles));


// --------------------------------------------------------
// 6. FETCH PERMISSIONS FOR ALL ROLES
// --------------------------------------------------------
if ($login_as === SUPERADMIN) {
    $qryGetUserPermission = "SELECT DISTINCT permission_id, permission FROM permissions";
} else {
    $qryGetUserPermission = "
    SELECT DISTINCT p.permission_id, p.permission
    FROM role_permissions rp
    INNER JOIN permissions p ON p.permission_id = rp.permission_id
    WHERE rp.role_id IN ($all_role_ids)
";
}


$result4 = $conn->query($qryGetUserPermission);

$user_permissions = [];
while ($row = $result4->fetch_assoc()) {
    $user_permissions[$row['permission_id']] = $row['permission'];
}

// --------------------------------------------------------
// 7. SAVE INTO SESSION
// --------------------------------------------------------
$_SESSION['LoggedInStudentId'] = $student_id;
$_SESSION['LoggedInTeacherId'] = $teacher_id;
$_SESSION['LoggedInUserId'] = $user_id;
$_SESSION['LoggedInUsername'] = $username_db;
$_SESSION['LoggedInUserType'] = $user_type;
$_SESSION['LoggedInUserRoles'] = $roles;
$_SESSION['UserMenus'] = $user_menus;
$_SESSION['UserPermissions'] = $user_permissions;
$_SESSION['LoggedInSchoolId'] = $school_id;

header("Location: dashboard.php");


exit();
?>