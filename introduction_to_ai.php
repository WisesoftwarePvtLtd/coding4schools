<?php
session_start();
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<style>
    body {
        margin: 0;
        font-family: Arial;
        background: #f5f7fb
    }

    .main {
        padding: 10px;
        margin-top: 100px;
    }

    /* TOP BAR */
    .top {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
        margin-right: 8px;

    }

    .title {
        flex: 1;
        text-align: center;
        font-size: 20px;
        font-weight: bold
    }

    /* LAYOUT */
    .editor-box {
        display: flex;
        height: 80vh;
        gap: 6px
    }

    .editor {
        width: 100%;
        border: 1px solid #333
    }

    iframe {
        width: 100%;
        border: 1px solid #ccc;
        background: #fff
    }

    button {
        background: #1da1f2;
        color: #fff;
        border: none;
        padding: 6px 12px;
        margin-left: 4px;
        cursor: pointer
    }
</style>


<body style="margin-top: 85px;">

    <div class="top">

        <div></div>
        <div class="title">
            <h2 style="text-align: center;">Introduction To Artificial Intelligence</h2>
        </div>

        <div>
            <button id="runBtn" class="btn-primary btn"
                onclick="window.open('https://colab.research.google.com/', '_blank')">
                Google Colab
            </button>
           
        </div>
    </div>
    <!-- Replace the src URL below with your Trinket embed link -->
    <iframe src="https://trinket.io/embed/blocks/a40aeed5c9" width="100%" height="500" frameborder="0" marginwidth="0"
        marginheight="0" allowfullscreen>
    </iframe>




</body>

</html>