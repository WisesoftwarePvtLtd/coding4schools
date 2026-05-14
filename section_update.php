<?php
include "config.php";

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$grade_id = filter_input(INPUT_POST, 'grade_id', FILTER_VALIDATE_INT);
$name = trim($_POST['name'] ?? "");
$gender = $_POST['gender'] ?? "";

if (!$id || !$grade_id || $name === "" || ($gender !== "boy" && $gender !== "girl")) {
    die("invalid");
}

// --- DUPLICATE CHECK ---
// SAME grade + SAME name + SAME gender BUT different section_id
$check = mysqli_prepare($conn, 
    "SELECT section_id 
     FROM sections
     WHERE grade_id = ? 
       AND section_name = ? 
       AND gender = ?
       AND section_id != ?"
);
mysqli_stmt_bind_param($check, "issi", $grade_id, $name, $gender, $id);
mysqli_stmt_execute($check);

$result = mysqli_stmt_get_result($check);

if ($result->num_rows > 0) {
    echo "duplicate";
    exit;
}
mysqli_stmt_close($check);

// --- UPDATE ---
$stmt = mysqli_prepare($conn, 
    "UPDATE sections 
     SET section_name = ?, gender = ? 
     WHERE section_id = ? AND grade_id = ?"
);
mysqli_stmt_bind_param($stmt, "ssii", $name, $gender, $id, $grade_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "success";
?>
