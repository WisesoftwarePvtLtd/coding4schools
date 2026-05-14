<?php
session_start();

include '../standard_constants.php';
$COURSE_NAME = "Data Visualisation";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $COURSE_NAME ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/pyodide/v0.25.0/full/pyodide.js"></script>
    <script src="../js/common.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f7fb
        }

        .main {
            padding: 10px;
            margin-top: 85px;
        }

        /* TOP BAR */
        .top {
            display: flex;
            align-items: center;
            margin-bottom: 8px
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
</head>

<body>
    <div class="main">

        <!-- TOP BAR -->
        <div class="top">
            <div></div>
            <div class="title"><?= $COURSE_NAME ?></div>
           
        </div>

        <!-- EDITOR + OUTPUT -->
        <div class="editor-box">
             <?php
            $userRoles = $_SESSION['LoggedInUserRoles'] ?? [];
            $studentRoleId = $userRoles['student'] ?? null;
            ?>

            <?php if ($studentRoleId == STUDENT) { ?>

                <div class="mb-2 p-2" style="background:#f8f9fa; border:1px solid #ddd; padding: 13px; margin-bottom:10px;width: 15%;">

                    <strong>Instructions:</strong><br>
                    1. Open Trinket editor<br>
                    2. Write your Python code<br>
                    3. Click on SHARE button<br>
                    4. Copy the link<br><br>
                    <span style="color:red; font-weight:bold;">
                        ⚠️ Important: Please save your link yourself. It will not be stored automatically in the system.
                    </span>

                </div>

            <?php } ?>
            
            <iframe src="https://trinket.io/embed/python3" width="100%" height="500" frameborder="0" marginwidth="0"
                marginheight="0" allowfullscreen>
            </iframe>

        </div>

 

    </div>
   <script>
        function getEditorCode() {
            
        }
    </script>
    
    <script>
        function setEditorCode(data) {
           
        }
    </script>
   

</body>

</html>