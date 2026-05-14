<?php
include 'config.php';

$id = intval($_POST['id'] ?? 0);
if ($id<=0) exit;

mysqli_query($conn,"DELETE FROM editors WHERE editor_id=$id");
echo "success";











