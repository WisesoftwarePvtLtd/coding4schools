<?php
include 'config.php';

$name = trim($_POST['name'] ?? '');
$url  = trim($_POST['url'] ?? '');

if ($name === '' || $url === '') {
    echo "invalid";
    exit;
}

/* =========================
   CHECK DUPLICATE NAME
========================= */
$stmt = $conn->prepare("SELECT editor_id FROM editors WHERE editor_name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "duplicate";
    exit;
}
$stmt->close();

/* =========================
   INSERT EDITOR
========================= */
$stmt = $conn->prepare("
    INSERT INTO editors (editor_name, editor_url)
    VALUES (?, ?)
");
$stmt->bind_param("ss", $name, $url);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}




