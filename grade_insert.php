<?php
include "config.php";

$name = trim($_POST['name']);
$gradeNumber = trim($_POST['gradeNumber']);
$academicYear = trim($_POST['academicYear']);
$schoolId = intval($_POST['schoolId'] ?? 0);

// --- DUPLICATE CHECK ---
/* =========================
   DUPLICATE NAME CHECK
========================= */
$check1 = $conn->prepare("
    SELECT grade_id 
    FROM grades 
    WHERE grade_name = ? 
      AND academic_year = ? 
      AND school_id = ?
");
$check1->bind_param("sii", $name, $academicYear, $schoolId);
$check1->execute();
$result1 = $check1->get_result();

if ($result1->num_rows > 0) {
    echo "duplicate";
    exit;
}
$check1->close();

/* =========================
   DUPLICATE GRADE NUMBER CHECK
========================= */
$check2 = $conn->prepare("
    SELECT grade_id 
    FROM grades 
    WHERE grade_number = ?
      AND school_id = ?
    LIMIT 1
");
$check2->bind_param("ii", $gradeNumber, $schoolId);
$check2->execute();
$check2->store_result();

if ($check2->num_rows > 0) {
    echo "duplicate_grade_number";
    exit;
}
$check2->close();



// --- INSERT IF NOT DUPLICATE ---
$stmt = mysqli_prepare($conn, "INSERT INTO grades (grade_name, grade_number, academic_year, school_id) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "siii", $name, $gradeNumber, $academicYear, $schoolId);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>

