<?php

//localhost 
$conn = new mysqli("localhost", "root", "", "chinese4school");

//prod
//$conn = new mysqli("localhost:3306", "coding4schools_prod_user", "52C~KhrgPjt%8gsc", "coding4schools_prod");
//uat 
//$conn = new mysqli("localhost:3306", "uatuser_coding4schools", "gtM&893p9", "uat_coding4schools");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// IMPORTANT: Set charset for unicode (Chinese, emojis, special characters)
$conn->set_charset("utf8mb4");
?>

