<?php
include "config.php";
include "standard_constants.php";

if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {

    $file = $_FILES['excel_file']['tmp_name'];

    // Only CSV allowed
    $ext = pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
    if ($ext != 'csv') {
        header("Location: manage-student.php?error=invalid_format");
        exit;
    }

    $handle = fopen($file, "r");
    if ($handle === false) {
        header("Location: manage-student.php?error=file_open");
        exit;
    }

    $rowNumber = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $rowNumber++;
        if ($rowNumber == 1) continue; // Skip header

        list($username, $studentNumber, $password, $studentName, $fatherName, $familyName, $gender, $grade, $section) = $data;
// echo $list;die;
    // ===============================
        //  FIND GRADE ID
        // ===============================
        $g = $conn->prepare("SELECT grade_id FROM grades WHERE grade_name LIKE ? LIMIT 1");
        
        $likeGrade = "%" . $grade . "%";
        $g->bind_param("s", $likeGrade);

        // echo $g;die;
        $g->execute();
        $g->bind_result($grade_id);
        $g->fetch();
        $g->close();

        if (!$grade_id) continue;

        // CSV section format: peaches-girl OR roses-boy
$section_parts = explode("-", $section);

$section_name_csv = trim($section_parts[0]);   // peaches
$gender_csv       = trim($section_parts[1]);   // girl

// Normalize gender
if ($gender_csv == "boy") $gender_csv = "boy";
elseif ($gender_csv == "girl") $gender_csv = "girl";

// ===============================
// FIND SECTION ID using section_name + gender
// ===============================
$sec = $conn->prepare("
    SELECT section_id 
    FROM sections 
    WHERE section_name LIKE ? 
      AND gender LIKE ? AND grade_id = ?
    LIMIT 1
");

$likeSecName = "%" . $section_name_csv . "%";
$likeGender  = "%" . $gender_csv . "%";

$sec->bind_param("ssi", $likeSecName, $likeGender, $grade_id);
$sec->execute();
$sec->bind_result($section_id);
$sec->fetch();
$sec->close();

if (!$section_id) continue; // if not found, skip

        // Full names
        $student_full_name = $studentName . ' ' . $fatherName;
        $full_name = $studentName . ' ' . $fatherName . ' ' . $familyName;

        // ---- Duplicate username check ----
        $chk = $conn->prepare("SELECT user_id FROM users WHERE username=?");
        $chk->bind_param("s", $username);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) { 
            continue; // Skip duplicate entry
        }
        $chk->close();

        // ---- Encrypt password ----
        $key = DECRYPT_KEY;
        $iv  = substr(hash("sha256", $key), 0, 16);
        $encryptedPassword = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);

        // ------------------------------
        // 1) INSERT INTO USERS
        // ------------------------------
        $user_sql = "INSERT INTO users (username, password_hash, full_name, user_type) VALUES (?, ?, ?, ?)";
        $user_stmt = $conn->prepare($user_sql);
        $user_type = STUDENT;

        $user_stmt->bind_param("ssss", $username, $encryptedPassword, $full_name, $user_type);
        $user_stmt->execute();
        $user_id = $conn->insert_id; 
        $user_stmt->close();

        // ------------------------------
        // 2) INSERT INTO STUDENTS
        // ------------------------------
        $student_stmt = $conn->prepare(
            "INSERT INTO students (student_number, student_name, family_name, user_id, gender)
             VALUES (?, ?, ?, ?, ?)"
        );

        $student_stmt->bind_param("sssis", $studentNumber, $student_full_name, $familyName, $user_id, $gender);
        $student_stmt->execute();
        $student_id = $conn->insert_id;
        $student_stmt->close();

        // ------------------------------
        // 3) INSERT INTO SECTION STUDENTS
        // ------------------------------
        $sec_stmt = $conn->prepare(
            "INSERT INTO section_students (student_id, grade_id, section_id)
             VALUES (?, ?, ?)"
        );

       $sec_stmt->bind_param("iii", $student_id, $grade_id, $section_id);
        $sec_stmt->execute();
        $sec_stmt->close();

        // ------------------------------
        // 4) INSERT INTO USER ROLES
        // ------------------------------
        $role_stmt = $conn->prepare(
            "INSERT INTO user_roles (role_id, user_id)
             VALUES (?, ?)"
        );

        $role_id = STUDENT;
        $role_stmt->bind_param("ii", $role_id, $user_id);
        $role_stmt->execute();
        $role_stmt->close();
    }

    fclose($handle);

    header("Location: manage-student.php?success=1");
    exit;
}
else {
    header("Location: manage-student.php?error=1");
    exit;
}
?>
