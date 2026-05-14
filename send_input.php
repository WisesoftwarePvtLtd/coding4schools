<?php

$run = $_POST['run_id'];
$input = $_POST['input'];

$dir = "sessions/$run";

$meta = unserialize(file_get_contents("$dir/meta"));

$process = $meta['process'];

$stdin = $meta['pipes'][0];
$stdout = $meta['pipes'][1];

fwrite($stdin, $input . PHP_EOL);

$output = stream_get_contents($stdout);

echo $output;
?>