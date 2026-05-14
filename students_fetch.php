<?php
session_start();
include "standard_constants.php";
include "config.php";
$school_id = $_SESSION['LoggedInSchoolId'] ?? 0;
$search = isset($_GET['search']) ? $_GET['search'] : "";
$searchParam = "%$search%";
$query = "SELECT 
            students.student_id,
            students.student_number,
            students.student_name,
            students.family_name,
            students.user_id,
            students.gender,
            grades.grade_id,
            grades.grade_name,
            sections.section_id,
            sections.section_name,
            sections.gender AS section_gender,
            users.username,
            users.password_hash
          FROM students
          LEFT JOIN users ON students.user_id = users.user_id
          LEFT JOIN section_students ON students.student_id = section_students.student_id
          LEFT JOIN grades ON section_students.grade_id = grades.grade_id
          LEFT JOIN sections ON section_students.section_id = sections.section_id
           WHERE users.school_id = ?
          AND (
              students.student_name LIKE ?
           OR users.username LIKE ?
           OR students.student_number LIKE ?
           OR students.family_name LIKE ?
           OR students.gender LIKE ?
           OR grades.grade_name LIKE ?
           OR sections.section_name LIKE ?
          )
          ORDER BY students.student_id DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param(
    $stmt,
    "isssssss",
    $school_id,
    $searchParam,
    $searchParam,
    $searchParam,
    $searchParam,
    $searchParam,
    $searchParam,
    $searchParam
);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($result)) {
// Decrypt Password
    $key = DECRYPT_KEY;
    $iv = substr(hash("sha256", $key), 0, 16);
$password = openssl_decrypt($row['password_hash'], "AES-256-CBC", $key, 0, $iv);
    $student_name = "";
    $father_name = "";
if (!empty($row['student_name'])) {
        $parts = explode(" ", $row['student_name'], 2);
        $student_name = $parts[0];
        $father_name = isset($parts[1]) ? $parts[1] : "";
    }
$secName = trim($row['section_name'] ?? '');
    $secGender = trim($row['section_gender'] ?? '');
    $gradeName = trim($row['grade_name'] ?? '');
if ($secName === "" && $secGender === "") {
        $secDisplay = "";
    } elseif ($secName !== "" && $secGender === "") {
        $secDisplay = $secName;
    } elseif ($secName === "" && $secGender !== "") {
        $secDisplay = $secGender;
    } else {
        $secDisplay = $secName . " - " . $secGender;
    }

    if ($gradeName === "" && $secDisplay === "") {
        $gradesecDisplay = "";
    } elseif ($gradeName !== "" && $secDisplay === "") {
        $gradesecDisplay = $gradeName;
    } elseif ($gradeName === "" && $secDisplay !== "") {
        $gradesecDisplay = $secDisplay;
    } else {
        $gradesecDisplay = $gradeName . " - " . $secDisplay;
    }
    $genderIcon = ($row['gender'] === "Male")
        ? '<i class="fas fa-male" style="font-size:28px; color:#2196f3;"></i>'
        : '<i class="fas fa-female" style="font-size:28px; color:#e91e63;"></i>';

    echo "<tr>
        <td>{$gradesecDisplay}</td>
        <td>{$row['username']}</td>
        <td>{$row['student_number']}</td>
        <td>{$genderIcon}  {$student_name} {$row['family_name']}</td>
        <td class='text-center'>";
    // ---------- EDIT ----------
    if (userHasPermission(STUDENT_EDIT)) {
       echo "<i class='fas fa-edit text-primary me-2' style='cursor:pointer'
          onclick=\"window.location.href='edit_student.php?user_id={$row['user_id']}'\"
          title='Edit Student'></i>";  
    }
    // ---------- VIEW ----------
    if (userHasPermission(STUDENT_VIEW_ICON)) {
        echo "<i class=\"fas fa-eye text-primary me-2 mr-2 \" style=\"cursor:pointer;\"
                onclick='viewStudent(
                   \"{$row['user_id']}\",
                   \"{$row['username']}\",
                   \"{$row['student_number']}\",
                   \"{$student_name}\",
                   \"{$father_name}\",
                   \"{$row['family_name']}\",
                   \"{$row['gender']}\",
                   \"{$row['grade_id']}\",
                   \"{$row['section_id']}\",
                   \"{$password}\"
                )' title='View student'></i>";
    }

    // ---------- DELETE ----------
    if (userHasPermission(STUDENT_DELETE)) {
        echo "<i class='fas fa-trash text-danger' style='cursor:pointer;'
               onclick='deleteStudent(\"{$row['student_id']}\")' title='Delete student'></i>
               ";
    }

    echo "</td></tr>";
}
function userHasPermission($permissionId)
{
    if (!isset($_SESSION['UserPermissions'])) {
        return false;
    }
    return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}
mysqli_stmt_close($stmt);
?>