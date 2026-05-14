<?php
include 'header.php';
$COURSE_NAME = "HTML & CSS";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= $COURSE_NAME ?></title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

<style>
body{margin:0;font-family:Arial;background:#f5f7fb}
.main{padding:10px;margin-top: 81px;}
.top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:8px
}
.title{
    flex:1;
    text-align:center;
    font-size:20px;
    font-weight:bold;
}
.editor-box{
    display:grid;
    grid-template-columns:1fr 1fr 1fr;
    height:70vh;
    gap:4px;
}
.editor{border:1px solid #333}
iframe{width:100%;height:30vh;border:1px solid #ccc;background:#5ca5e9}
button{
    background:#2196f3;
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

<div class="top">
    <div></div>
    <div class="title"><?= $COURSE_NAME ?></div>
    <div>
        <button onclick="runCode()" class="btn btn-primary">Run</button>
   
    </div>
</div>

<div class="editor-box">
    <div id="e1" class="editor" style="font-size: 17px;"></div>
    <div id="e2" class="editor" style="font-size: 17px;"></div>
    <div id="e3" class="editor" style="font-size: 17px;"></div>
</div>

<iframe id="output"></iframe>

</div>

<script>
        showLoader();

let e1, e2, e3;

require.config({
    paths:{vs:"https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs"}
});

require(["vs/editor/editor.main"], function () {

    e1 = monaco.editor.create(
        document.getElementById("e1"),
        { value:"<!-- HTML here -->", language:"html", theme:"vs-dark", automaticLayout:true, fontSize:20 }
    );

    e2 = monaco.editor.create(
        document.getElementById("e2"),
        { value:"/* CSS here */", language:"css", theme:"vs-dark", automaticLayout:true, fontSize:20 }
    );

    e3 = monaco.editor.create(
        document.getElementById("e3"),
        { value:"// JS here", language:"javascript", theme:"vs-dark", automaticLayout:true, fontSize:20 }
    );
        hideLoader();

    // loadCode();
});

function runCode(){
    const out =
        e1.getValue() +
        "<style>"+e2.getValue()+"</style>" +
        "<script>"+e3.getValue()+"<\/script>";
    // document.getElementById("output").srcdoc = out;
    const newTab = window.open("", "_blank");

            if (!newTab) {
                alert("Popup blocked! Please allow popups.");
                return;
            }

            newTab.document.open();
            newTab.document.write(out);
            newTab.document.close();
}


</script>
</body>
</html>
