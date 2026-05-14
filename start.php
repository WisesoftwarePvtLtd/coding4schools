<?php
session_start();

$code = $_POST['code'];

$id = uniqid();
$dir = "sessions/$id";

mkdir($dir);

$file = "$dir/code.py";

$template = file_get_contents("runner_template.py");
file_put_contents($file, $template . "\n" . $code);

$descriptorspec = [
   0 => ["pipe", "r"],
   1 => ["pipe", "w"],
   2 => ["pipe", "w"]
];

$process = proc_open("python3 $file", $descriptorspec, $pipes);

file_put_contents("$dir/meta", serialize([
    "process"=>$process,
]));

$_SESSION['run_id'] = $id;

echo json_encode([
   "run_id"=>$id
]);
?>