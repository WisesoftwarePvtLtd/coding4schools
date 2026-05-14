<?php
session_start();
include 'header.php';

?>
<!DOCTYPE html>
<html lang="en">
<body style="margin-top: 85px;">

<h2 style="text-align: center;">Makey Makey (MakeCode)</h2>

<!-- <iframe src="makeymakey/build/" width="100%" height="800" style="border:none;"></iframe> -->
    <iframe src="<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>" width="100%" height="800" style="border:none;"></iframe>

</body>
</html>