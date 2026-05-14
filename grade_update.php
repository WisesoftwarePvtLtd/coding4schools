<?php
include "config.php";

$id   = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$name = trim($_POST['name']);
$gradeNumber = trim($_POST['gradeNumber']);
$academicYear = trim($_POST['academicYear']);
$schoolId = intval($_POST['schoolId'] ?? 0);


/* =========================
   DUPLICATE NAME CHECK (EXCLUDE SELF)
========================= */
$check1 = $conn->prepare("
    SELECT grade_id 
    FROM grades 
    WHERE LOWER(TRIM(grade_name)) = ?
      AND academic_year = ?
      AND school_id = ?
      AND grade_id != ?
");
$check1->bind_param("siii", $name, $academicYear, $schoolId, $id);
$check1->execute();
$result1 = $check1->get_result();

if ($result1->num_rows > 0) {
    echo "duplicate";
    exit;
}
$check1->close();

/* =========================
   DUPLICATE GRADE NUMBER CHECK (EXCLUDE SELF)
========================= */
$check2 = $conn->prepare("
    SELECT grade_id 
    FROM grades 
    WHERE grade_number = ?
      AND academic_year = ?
      AND school_id = ?
      AND grade_id != ?
");
$check2->bind_param("iiii", $gradeNumber, $academicYear, $schoolId, $id);
$check2->execute();
$check2->store_result();

if ($check2->num_rows > 0) {
    echo "duplicate_grade_number";
    exit;
}
$check2->close();

// --- UPDATE ---
$stmt = mysqli_prepare($conn, 
    "UPDATE grades SET grade_name = ?, grade_number = ?, academic_year = ?, school_id = ? WHERE grade_id = ?"
);
mysqli_stmt_bind_param($stmt, "siiii", $name, $gradeNumber, $academicYear, $schoolId, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>
