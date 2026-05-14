<?php
session_start();

$exercise_id = intval($_POST['exercise_id'] ?? 0);
$code        = $_POST['code'] ?? '';

if ($exercise_id <= 0 || empty($code)) {
    echo "Invalid Data";
    exit;
}

// JSON decode
$data = json_decode($code, true);

$html = $data['html'] ?? '';
$css  = $data['css'] ?? '';
$js   = $data['js'] ?? '';

// 🔥 CHECK: already full HTML hai kya?
if (stripos($html, '<html') !== false) {

    // FULL HTML case
    $finalHTML = $html;

    // CSS inject (before </head>)
    if (!empty($css)) {
        if (stripos($finalHTML, '</head>') !== false) {
            $finalHTML = preg_replace('/<\/head>/i', "<style>$css</style></head>", $finalHTML);
        } else {
            $finalHTML = "<style>$css</style>" . $finalHTML;
        }
    }

    // JS inject (before </body>)
    if (!empty($js)) {
        if (stripos($finalHTML, '</body>') !== false) {
            $finalHTML = preg_replace('/<\/body>/i', "<script>$js</script></body>", $finalHTML);
        } else {
            $finalHTML .= "<script>$js</script>";
        }
    }

} else {

    // NORMAL case (no full HTML)
    $finalHTML = "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>Exercise $exercise_id</title>

<style>
$css
</style>

</head>
<body>

$html

<script>
$js
</script>

</body>
</html>
";
}

// download
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="exercise_'.$exercise_id.'.html"');

echo $finalHTML;
exit;