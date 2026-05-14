<?php
include 'config.php';

$id   = intval($_POST['id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$url  = trim($_POST['url'] ?? '');

if ($id<=0 || $name=='' || $url=='') {
    echo "Invalid data";
    exit;
}

$name = mysqli_real_escape_string($conn,$name);
$url  = mysqli_real_escape_string($conn,$url);

mysqli_query($conn,
"UPDATE editors SET
 editor_name='$name',
 editor_url='$url'
 WHERE editor_id=$id"
);

echo "success";







