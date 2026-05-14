<?php
$code = $_POST['code'] ?? '';
$mode = $_POST['mode'] ?? '';

if ($mode !== 'python') {
    echo "Invalid mode";
    exit;
}

/* ===== ONLINE PYTHON EXECUTION (NO INSTALL) ===== */

$data = [
    "language" => "python",
    "version" => "3.10.0",
    "files" => [
        [
            "name" => "main.py",
            "content" => $code
        ]
    ]
];

$ch = curl_init("https://emkc.org/api/v2/piston/execute");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

$output =
    $result['run']['output'] ??
    $result['compile']['output'] ??
    "Execution failed";

echo "<pre style='background:#111;color:#0f0;padding:10px'>$output</pre>";
