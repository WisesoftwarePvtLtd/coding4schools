<?php
include 'header.php';
include 'config.php';

$lesson_id = intval($_GET['lesson_id'] ?? 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Problem</title>
    <style>
        .image-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
            cursor: pointer;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* ❌ CROSS */
        .remove-image {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 28px;
            height: 28px;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            font-size: 26px;
            display: flex;
        }
    </style>

</head>

<body>

    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN -->

            <div class="main-area" style="position:relative;">

                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>



                <div class="card shadow-sm" style="border-radius:12px; max-width:2000px; top:22px;">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;border-radius:12px 12px 0 0;">
                        <h4 class="m-0 fw-bold">
                            <i class=""></i> Add Problem
                        </h4>

                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">
                        <form action="problem_save.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Problem Name *
                                </label>
                                <textarea name="problem_name" class="form-control" rows="3" required></textarea>
                            </div>
                            <!-- problem -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Problem Text *
                                </label>
                                <textarea name="problem_text" class="form-control" rows="3" required></textarea>
                            </div>



                            <!-- Editor Dropdown -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Editor *</label>

                                <select name="problem_editor" id="editorSelect" class="form-control">

                                    <!-- <option value="">Select Editor</option> -->

                                    <option value="codey.php?course=Codey Rockey">Codey Rockey</option>
                                    <option value="ai_for_juniors.php">AI for Juniors</option>
                                    <option value="3d_drawing.php">3D Drawing</option>
                                    <option value="python_editor.php">Python</option>
                                    <option value="vr_programming.php">Virtual Reality Programming</option>
                                    <option value="introduction_to_ai.php">Introduction to Artificial Intelligence
                                    </option>
                                    <option value="introduction_to_javascript_gr8.php">Introduction to Javascript
                                        gr8</option>
                                    <option value="introduction_to_data_analytics.php">Introduction to Data
                                        Analytics</option>
                                    <option value="html_css.php">HTML & CSS</option>
                                    <option value="game_dev_js.php">Game Development JS</option>
                                    <option value="data_visualisation.php">Data Visualisation</option>

                                    <option value="coding_logic.php">Introduction to Coding Logic</option>
                                    <option value="javaP5.php">Java Processing p5 js</option>
                                    <option value="makeymakey.php">Makey Makey</option>
                                    <option value="microbitprog.php">Microbit Programming</option>
                                    <option value="microbitJs.php">Microbit JavaScript</option>
                                    <option value="cyberpi.php">CyberPi</option>
                                    <option value="mbotneo.php">mBot Neo</option>
                                    <option value="tinybit_ai.php">Tinybit AI Vision</option>
                                    <option value="scratchJr.php">Scratch Jr</option>
                                    <option value="scratch.php">Scratch</option>
                                    <option value="spherobolt.php">Sphero Bolt</option>
                                    <option value="mbot.php">mBot Robot Coding</option>
                                    <option value="arduino.php">Arduino</option>

                                </select>
                            </div>

                            <?php
                            $existing_problem_image = $data['problem_image'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Problem Image
                                </label>

                                <input type="file" name="problem_image" id="problemImage" accept="image/*" hidden
                                    onchange="previewImage(this, 'problemPreview')">

                                <!-- REMOVE FLAG -->
                                <input type="hidden" name="remove_problem_image" id="removeProblemImage" value="0">

                                <div class="image-wrapper">
                                    <img id="problemPreview" src="<?= !empty($existing_problem_image)
                                        ? 'uploads/' . htmlspecialchars($existing_problem_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="problem Image"
                                        onclick="document.getElementById('problemImage').click();">

                                    <!-- ❌ CROSS ICON -->
                                    <span class="remove-image" onclick="removeProblemImage()">
                                        <i class="fas fa-times"></i>
                                    </span>

                                </div>
                            </div>

                            <!-- BUTTONS -->
                            <div class="text-end">
                                <button type="button" onclick="window.history.back()" class="btn btn-secondary px-4">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-primary px-4 btn-space"
                                    style="background:#1da1f2;color:#fff;">
                                    <i class="fas fa-save"></i> Save problem
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeProblemImage() {
            // preview ko placeholder pe set karo
            document.getElementById("problemPreview").src =
                "images/systemimages/placeholder-image.png";

            // file input clear
            document.getElementById("problemImage").value = "";

            // server ko batao image remove karni hai
            document.getElementById("removeProblemImage").value = "1";
        }
    </script>



</body>

</html>