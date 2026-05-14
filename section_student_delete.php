<?php
session_start();

include "config.php";

$student_id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($student_id > 0) {

    // First get the grade_id and section_id based on student_id
    $getInfo = $conn->query("
        SELECT grade_id, section_id 
        FROM section_students 
        WHERE student_id = $student_id
        LIMIT 1
    ");

    if ($getInfo->num_rows === 0) {
        echo "not_found";
        exit;
    }

    // UPDATE grade_id and section_id to NULL
    $sql = "UPDATE section_students 
            SET grade_id = NULL, section_id = NULL
            WHERE student_id = $student_id";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $conn->error;
    }

} else {
    echo "invalid_id";
}
?>
