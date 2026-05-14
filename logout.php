<?php
session_start();

// Remove all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();
?>
