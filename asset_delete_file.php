<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$assetId = intval($data['assetId'] ?? 0);

if ($assetId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid asset ID']);
    exit;
}

// Fetch the file path
$result = $conn->query("SELECT asset_file_path FROM assets WHERE asset_id = $assetId");
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Asset not found']);
    exit;
}

$row = $result->fetch_assoc();
$path = $row['asset_file_path'];

// Delete file from server
if ($path && file_exists($path)) {
    unlink($path);
}

// Update database to remove file path
$conn->query("UPDATE assets SET asset_file_path = NULL WHERE asset_id = $assetId");

echo json_encode(['success' => true]);
?>
