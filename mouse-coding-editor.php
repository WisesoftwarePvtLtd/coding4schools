<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mouse Coding Editor</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
}

.editor {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.panel {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    width: 50%;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

button {
    padding: 10px 15px;
    margin: 5px 0;
    width: 100%;
    cursor: pointer;
    font-size: 16px;
}

#playground {
    height: 300px;
    border: 2px dashed #999;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background: #fafafa;
}

.log {
    margin-top: 10px;
    background: #000;
    color: #0f0;
    padding: 10px;
    height: 120px;
    overflow-y: auto;
    font-family: monospace;
}
</style>
</head>

<body>

<h1>🖱️ Mouse Coding Editor</h1>

<div class="editor">

    <!-- CONTROLS -->
    <div class="panel">
        <h3>Actions</h3>

        <button onclick="setClick()">On Mouse Click</button>
        <button onclick="setHover()">On Mouse Hover</button>
        <button onclick="setMove()">On Mouse Move</button>
        <button onclick="reset()">Reset</button>

        <div class="log" id="log"></div>
    </div>

    <!-- PLAYGROUND -->
    <div class="panel">
        <h3>Playground</h3>
        <div id="playground">Move or Click Here</div>
    </div>

</div>

<script>
const playground = document.getElementById("playground");
const logBox = document.getElementById("log");

function log(msg) {
    logBox.innerHTML += msg + "<br>";
    logBox.scrollTop = logBox.scrollHeight;
}

function reset() {
    playground.onmousemove = null;
    playground.onclick = null;
    playground.onmouseenter = null;
    playground.innerHTML = "Move or Click Here";
    log("Reset events");
}

function setClick() {
    reset();
    playground.onclick = () => {
        playground.innerHTML = "🖱️ Click Detected!";
        log("Mouse Click Event Fired");
    };
    log("Click event added");
}

function setHover() {
    reset();
    playground.onmouseenter = () => {
        playground.innerHTML = "✨ Hovering!";
        log("Mouse Hover Event Fired");
    };
    log("Hover event added");
}

function setMove() {
    reset();
    playground.onmousemove = () => {
        playground.innerHTML = "➡️ Moving...";
    };
    log("Mouse Move event added");
}
</script>

</body>
</html>
