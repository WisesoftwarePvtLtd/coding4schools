<?php
include 'header.php';
$COURSE_NAME = "Java Processing p5.js";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $COURSE_NAME ?></title>

<script>
/* avoid require conflict */
window.require = { paths:{} };
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f7fb
}
.main{ padding:10px ;margin-top: 85px;
}

/* TOP BAR */
.top{
    display:flex;
    align-items:center;
    margin-bottom:8px
}
.title{
    flex:1;
    text-align:center;
    font-size:20px;
    font-weight:bold
}

/* LAYOUT */
.editor-box{
    display:flex;
    height:80vh;
    gap:6px
}
.editor{
    width:100%;
    border:1px solid #333
}
iframe{
    width:40%;
    border:1px solid #ccc;
    background:#fff
}

button{
    background:#1da1f2;
    color:#fff;
    border:none;
    padding:6px 12px;
    margin-left:4px;
    cursor:pointer
}
</style>
</head>

<body>
<div class="main">
     <!-- DUSTY HOURGLASS LOADER -->
        <div id="page-loader" class="loader-overlay">
            <div class="hourglass"></div>
        </div>

<!-- TOP BAR -->
<div class="top">
    <div></div>
    <div class="title"><?= $COURSE_NAME ?></div>
    <div>
        <button onclick="runCode()" class="btn btn-primary">Run</button>
        
    </div>
</div>

<!-- EDITOR + OUTPUT -->
<div class="editor-box">
    <div id="editor" class="editor"></div>
    
</div>

</div>
<script>
          showLoader();
let editor;

/* MONACO */
require.config({
    paths:{vs:"https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs"}
});

require(["vs/editor/editor.main"], function () {

    editor = monaco.editor.create(
        document.getElementById("editor"), {
        value:
`function setup() {
  createCanvas(400, 400);
}

function draw() {
  background(220);
  ellipse(mouseX, mouseY, 50, 50);
}`,
        language:"javascript",
        theme:"vs-dark",
        automaticLayout:true
    });

    loadCode();
});
hideLoader();

/* RUN */
function runCode(){
    if(!editor) return;

    const code = editor.getValue();

    const html =
'<!DOCTYPE html>' +
'<html>' +
'<head>' +
'<meta charset="UTF-8">' +
'<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.min.js"><\/script>' +
'</head>' +
'<body>' +
'<script>' +
'window.onerror=function(e){document.body.innerHTML="<pre style=color:red>"+e+"</pre>";};' +
code +
'<\/script>' +
'</body>' +
'</html>';

    const newTab = window.open("", "_blank");

            if (!newTab) {
                alert("Popup blocked! Please allow popups.");
                return;
            }

            newTab.document.open();
            newTab.document.write(html);
            newTab.document.close();
}



</script>




</body>
</html>



