<?php
include 'config.php';

$lesson_id = intval($_POST['lesson_id'] ?? 0);
if ($lesson_id <= 0) {
    exit("invalid");
}

/* fetch image */
$stmt = $conn->prepare("SELECT lesson_guideline_image FROM lessons WHERE lesson_id=?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!empty($result['lesson_guideline_image'])) {
    $filePath = "uploads/" . $result['lesson_guideline_image'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* update DB */
$stmt = $conn->prepare("UPDATE lessons SET lesson_guideline_image =NULL WHERE lesson_id=?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$stmt->close();

echo "success";
