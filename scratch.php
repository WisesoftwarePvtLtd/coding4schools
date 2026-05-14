<?php
session_start();
include 'header.php';

?>
<!DOCTYPE html>
<html>

<body style="margin-top:87px">

    <h2 style="text-align: center;">Scratch</h2>
    

    <!-- <iframe src="makeymakey/build/" width="100%" height="800" style="border:none;"></iframe> -->
    <iframe src="<?=  SCRATCHWITHMAKEYMAKEYBUILDPATH ?>" width="100%" height="800" style="border:none;" sandbox="allow-scripts allow-same-origin allow-forms allow-modals allow-popups" target="_blank"></iframe>
     <!-- <a href="makeymakey/build/" target="_blank">Open Scratch Editor</a> -->

     <!-- <iframe src="http://localhost:8601/" width="100%" height="800" style="border:none;"></iframe> -->

    <!-- <iframe id="scratchFrame" 
    src="http://localhost/coding4schools/scratch/build/index.html?project_url=http://localhost/coding4schools/projects/project_1773756604.sb3" 
    width="100%" height="800" style="border:none;">
</iframe> -->
<?php 
// $projectUrl = "http://localhost/coding4schools/projects/project_1773756604.sb3";
// $encodedUrl = rawurlencode($projectUrl);?>
<!-- <iframe id="scratchFrame" 
    src="scratch/build/index.html?project_url=<?= $encodedUrl ?>" 
    width="100%" height="800" style="border:none;">
    
</iframe> -->

</body>

</html>