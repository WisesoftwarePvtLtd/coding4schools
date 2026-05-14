<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage_assets.php");
    exit;
}

$assetId   = isset($_POST['assetId']) ? intval($_POST['assetId']) : 0;
$assetName = trim($_POST['assetName'] ?? "");

/* =========================
   DUPLICATE NAME CHECK
========================= */

if ($assetId > 0) {
    // EDIT MODE → current id ignore
    $stmt = $conn->prepare("SELECT asset_id FROM assets WHERE asset_name = ? AND asset_id != ?");
    $stmt->bind_param("si", $assetName, $assetId);
} else {
    // ADD MODE
    $stmt = $conn->prepare("SELECT asset_id FROM assets WHERE asset_name = ?");
    $stmt->bind_param("s", $assetName);
}

$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['msg'] = "Asset name already exists";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_assets.php");
    exit;
}

$stmt->close();

if ($assetName === "") {
    $_SESSION['msg'] = "Asset name is required";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_assets.php");
    exit;
}

/* =========================
   FILE UPLOAD HANDLING
========================= */

$uploadDir = "assets/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$filePath = null;

if (!empty($_FILES['assetFilePath']['name'])) {

    $originalName = basename($_FILES['assetFilePath']['name']);
    $extension    = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $originalName);
    $target   = $uploadDir . $safeName;

    if (!move_uploaded_file($_FILES['assetFilePath']['tmp_name'], $target)) {
        $_SESSION['msg'] = "File upload failed";
        $_SESSION['transaction_status'] = "error";
        header("Location: manage_assets.php");
        exit;
    }

    $filePath = $target;
}

/* =========================
   INSERT OR UPDATE
========================= */

if ($assetId > 0) {

    // UPDATE
    if ($filePath !== null) {
        $stmt = $conn->prepare(
            "UPDATE assets SET asset_name = ?, asset_file_path = ? WHERE asset_id = ?"
        );
        $stmt->bind_param("ssi", $assetName, $filePath, $assetId);
    } else {
        $stmt = $conn->prepare(
            "UPDATE assets SET asset_name = ? WHERE asset_id = ?"
        );
        $stmt->bind_param("si", $assetName, $assetId);
    }

    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Asset updated successfully";

} else {

    // INSERT
    $stmt = $conn->prepare(
        "INSERT INTO assets (asset_name, asset_file_path) VALUES (?, ?)"
    );
    $stmt->bind_param("ss", $assetName, $filePath);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Asset added successfully";
}

$_SESSION['transaction_status'] = "success";
header("Location: manage_assets.php");
exit;
