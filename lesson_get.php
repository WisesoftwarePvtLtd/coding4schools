<?php
include "config.php";

$course_id = intval($_GET['course_id']);
$result = $conn->query("SELECT lesson_id, lesson_title FROM lessons WHERE course_id = $course_id ORDER BY lesson_title ASC");

$lessons = [];

while ($row = $result->fetch_assoc()) {
    $lessons[] = $row;
}

echo json_encode($lessons);
?>
