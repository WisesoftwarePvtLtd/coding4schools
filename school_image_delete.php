<?php
session_start();
include "config.php";

$school_id = intval($_POST['school_id'] ?? 0);

if ($school_id <= 0) {
    echo "invalid_id";
    exit;
}

/* GET IMAGE */

$stmt = $conn->prepare("
SELECT school_profile_image 
FROM schools 
WHERE school_id = ?
");

$stmt->bind_param("i", $school_id);
$stmt->execute();

$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    echo "not_found";
    exit;
}

$image = $data['school_profile_image'];

/* DELETE FILE */

if (!empty($image) && file_exists($image)) {
    unlink($image);
}

/* UPDATE DB */

$stmt = $conn->prepare("
UPDATE schools 
SET school_profile_image = NULL 
WHERE school_id = ?
");

$stmt->bind_param("i", $school_id);

if ($stmt->execute()) {

    echo "success";

} else {

    echo "error";

}