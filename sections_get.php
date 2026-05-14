<?php
include "config.php";

$grade   = $_GET['grade'] ?? "";
$section = $_GET['section'] ?? "";

// Fetch sections
$result = $conn->query("SELECT section_id, section_name, gender FROM sections WHERE grade_id='$grade'");

// Count sections
$totalSections = $result->num_rows;

echo "<option value=''>Select Section</option>";

while ($row = $result->fetch_assoc()) {

    $secId = (string)$row['section_id'];   // Convert to string for comparison
    $selected = ($secId === $section) ? "selected" : "";

    echo "<option value='{$secId}' {$selected}>{$row['section_name']} - {$row['gender']}</option>";
}

// Add hidden value for JS validation (very important)
echo "<input type='hidden' id='sectionCount' value='{$totalSections}'>";
?>
