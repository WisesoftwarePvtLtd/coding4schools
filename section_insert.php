<?php
include "config.php";

$grade_id = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? "");
$gender = $_POST['gender'] ?? "";

if (!$grade_id || $name === "" || ($gender !== "boy" && $gender !== "girl")) {
    die("invalid");
}

// --- DUPLICATE CHECK ---
// Check SAME grade + SAME section name + SAME gender
$check = mysqli_prepare($conn, 
    "SELECT section_id 
     FROM sections 
     WHERE grade_id = ? AND section_name = ? AND gender = ?"
);
mysqli_stmt_bind_param($check, "iss", $grade_id, $name, $gender);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);

if ($result->num_rows > 0) {
    echo "duplicate";
    exit;
}
mysqli_stmt_close($check);

// --- INSERT IF NOT DUPLICATE ---
$stmt = mysqli_prepare($conn, 
    "INSERT INTO sections (grade_id, section_name, gender) VALUES (?, ?, ?)"
);
mysqli_stmt_bind_param($stmt, "iss", $grade_id, $name, $gender);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>
