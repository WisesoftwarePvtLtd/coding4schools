<?php
session_start();
include "config.php";
include "standard_constants.php";

// ini_set('display_errors', 1);
// error_reporting(E_ALL);



$school_id = intval($_POST['school_id'] ?? 0);

$school_name = trim($_POST['school_name']);
$school_username = trim($_POST['school_username']);
$password = trim($_POST['school_password'] ?? '');

if ($school_name == "") {
    $_SESSION['msg'] = "All fields are required";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_schools.php");
    exit;
}

/* =========================
   CHECK DUPLICATE USERNAME
========================= */

if ($school_id == 0) {
    // INSERT CASE
    $check = $conn->prepare("SELECT school_id FROM schools WHERE school_name=?");
    $check->bind_param("s", $school_name);
} else {
    // UPDATE CASE
    $check = $conn->prepare("SELECT school_id FROM schools WHERE school_name=? AND school_id != ?");
    $check->bind_param("si", $school_name, $school_id);
}

$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $_SESSION['msg'] = "school name already exists!";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_schools.php");
    exit;
}

/* =========================
   PASSWORD ENCRYPT
========================= */

$key = DECRYPT_KEY;
$iv  = substr(hash("sha256", $key), 0, 16);

$hashed_password = "";
if (!empty($password)) {
    $hashed_password = openssl_encrypt($password, "AES-256-CBC", $key, 0, $iv);
}

/* =========================
   IMAGE UPLOAD
========================= */

$image_path = "";

if (!empty($_FILES['school_profile_image']['name'])) {

    $folder = "uploads/schools";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $clean = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES['school_profile_image']['name']);
    $name = time() . "_" . $clean;

    move_uploaded_file($_FILES['school_profile_image']['tmp_name'], "$folder/$name");

    $image_path = "$folder/$name";
}

/* =========================
   INSERT SCHOOL
========================= */

if ($school_id == 0) {

    // if (empty($password)) {
    //     $_SESSION['msg'] = "Password is required";
    //     $_SESSION['transaction_status'] = "error";
    //     header("Location: manage_schools.php");
    //     exit;
    // }

    $stmt = $conn->prepare("
        INSERT INTO schools
        (school_name,school_profile_image)
        VALUES (?,?)
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $school_name,  $image_path);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "School Added Successfully";
        $_SESSION['transaction_status'] = "success";
    } else {
        $_SESSION['msg'] = "Insert failed: " . $stmt->error;
        $_SESSION['transaction_status'] = "error";
    }
}

/* =========================
   UPDATE SCHOOL
========================= */

else {



    $stmt = $conn->prepare("SELECT school_password, school_profile_image FROM schools WHERE school_id=?");
    $stmt->bind_param("i", $school_id);
    $stmt->execute();
    $old = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $final_password = $old['school_password'];

    if (!empty($password)) {
        $final_password = $hashed_password;
    }

    $final_image = $old['school_profile_image'];

    if (!empty($image_path)) {

        if (!empty($old['school_profile_image'])) {
            $old_path = __DIR__ . "/" . $old['school_profile_image'];
            if (file_exists($old_path)) {
                unlink($old_path);
            }
        }

        $final_image = $image_path;
    }

    $stmt = $conn->prepare("
        UPDATE schools
        SET
            school_name=?,
            school_profile_image=?
        WHERE school_id=?
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssi", $school_name, $final_image, $school_id);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "School Updated Successfully";
        $_SESSION['transaction_status'] = "success";
    } else {
        $_SESSION['msg'] = "Update failed: " . $stmt->error;
        $_SESSION['transaction_status'] = "error";
    }
}

header("Location: manage_schools.php");
exit;