<?php
session_start();
include 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['msg'] = "Invalid asset request";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_assets.php");
    exit;
}

$assetId = intval($_GET['id']);

/* =========================
   GET FILE PATH
========================= */
$stmt = $conn->prepare("SELECT asset_file_path FROM assets WHERE asset_id = ?");
$stmt->bind_param("i", $assetId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['msg'] = "Asset not found";
    $_SESSION['transaction_status'] = "error";
    header("Location: manage_assets.php");
    exit;
}

$row = $result->fetch_assoc();
$filePath = $row['asset_file_path'];
$stmt->close();

/* =========================
   DELETE FILE (if exists)
========================= */
if (!empty($filePath) && file_exists($filePath)) {
    unlink($filePath);
}

/* =========================
   DELETE RECORD
========================= */
$stmt = $conn->prepare("DELETE FROM assets WHERE asset_id = ?");
$stmt->bind_param("i", $assetId);
$stmt->execute();
$stmt->close();

$_SESSION['msg'] = "Asset deleted successfully";
$_SESSION['transaction_status'] = "success";

header("Location: manage_assets.php");
exit;
