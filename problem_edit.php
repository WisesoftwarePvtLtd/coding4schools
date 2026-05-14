<?php

include 'config.php';
include 'header.php';
$problem_id = intval($_GET['problem_id'] ?? 0);
$lesson_id = intval($_GET['lesson_id'] ?? 0);

if ($problem_id <= 0) {
    die("Invalid problem");
}

/* FETCH problem */
$stmt = $conn->prepare("
    SELECT 
        *
    FROM problem
    WHERE problem_id = ?
");
$stmt->bind_param("i", $problem_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit problem</title>
    <style>
        .image-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

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
            <div class="main-area">
                <div id="globalMsg" class="global-msg" style="display:none;"></div>

                <div class="card shadow-sm" style="border-radius:12px; max-width:2000px; top:22px;">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;border-radius:12px 12px 0 0;">
                        <h4 class="m-0 fw-bold">
                            <i class=""></i> Edit problem
                        </h4>

                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">
                        <form action="problem_save.php" method="POST" enctype="multipart/form-data">



                            <input type="hidden" name="problem_id" value="<?= $data['problem_id'] ?>">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">

                            <!-- problem -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Problem Name *</label>

                                <textarea name="problem_name" class="form-control" rows="2"
                                    required><?= htmlspecialchars($data['problem_name']) ?></textarea>

                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    problem Text *
                                </label>
                                <textarea name="problem_text" class="form-control" rows="3"
                                    required><?= htmlspecialchars($data['problem_text']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Select Editor *</label>

                                <select name="problem_editor" id="editorSelect" class="form-control">

                                    <!-- <option value="">Select Editor</option> -->

                                    <option value="codey.php?course=Codey Rockey"
                                        <?= $data['editor_url'] == 'codey.php?course=Codey Rockey' ? 'selected' : '' ?>>
                                        Codey Rockey
                                    </option>

                                    <option value="ai_for_juniors.php" <?= $data['editor_url'] == 'ai_for_juniors.php' ? 'selected' : '' ?>>
                                        AI for Juniors
                                    </option>

                                    <option value="3d_drawing.php" <?= $data['editor_url'] == '3d_drawing.php' ? 'selected' : '' ?>>
                                        3D Drawing
                                    </option>

                                    <option value="python_editor.php" <?= $data['editor_url'] == 'python_editor.php' ? 'selected' : '' ?>>
                                        Python
                                    </option>

                                    <option value="vr_programming.php" <?= $data['editor_url'] == 'vr_programming.php' ? 'selected' : '' ?>>
                                        Virtual Reality Programming
                                    </option>

                                    <option value="introduction_to_ai.php"
                                        <?= $data['editor_url'] == 'introduction_to_ai.php' ? 'selected' : '' ?>>
                                        Introduction to Artificial Intelligence
                                    </option>

                                    <option value="introduction_to_javascript_gr8.php"
                                        <?= $data['editor_url'] == 'introduction_to_javascript_gr8.php' ? 'selected' : '' ?>>
                                        Introduction to Javascript gr8
                                    </option>

                                    <option value="introduction_to_data_analytics.php"
                                        <?= $data['editor_url'] == 'introduction_to_data_analytics.php' ? 'selected' : '' ?>>
                                        Introduction to Data Analytics
                                    </option>

                                    <option value="html_css.php" <?= $data['editor_url'] == 'html_css.php' ? 'selected' : '' ?>>
                                        HTML & CSS
                                    </option>

                                    <option value="game_dev_js.php" <?= $data['editor_url'] == 'game_dev_js.php' ? 'selected' : '' ?>>
                                        Game Development JS
                                    </option>

                                    <option value="data_visualisation.php"
                                        <?= $data['editor_url'] == 'data_visualisation.php' ? 'selected' : '' ?>>
                                        Data Visualisation
                                    </option>

                                    <option value="coding_logic.php" <?= $data['editor_url'] == 'coding_logic.php' ? 'selected' : '' ?>>
                                        Introduction to Coding Logic
                                    </option>

                                    <option value="javaP5.php" <?= $data['editor_url'] == 'javaP5.php' ? 'selected' : '' ?>>
                                        Java Processing p5 js
                                    </option>

                                    <option value="makeymakey.php" <?= $data['editor_url'] == 'makeymakey.php' ? 'selected' : '' ?>>
                                        Makey Makey
                                    </option>

                                    <option value="microbitprog.php" <?= $data['editor_url'] == 'microbitprog.php' ? 'selected' : '' ?>>
                                        Microbit Programming
                                    </option>

                                    <option value="microbitJs.php" <?= $data['editor_url'] == 'microbitJs.php' ? 'selected' : '' ?>>
                                        Microbit JavaScript
                                    </option>

                                    <option value="cyberpi.php" <?= $data['editor_url'] == 'cyberpi.php' ? 'selected' : '' ?>>
                                        CyberPi
                                    </option>

                                    <option value="mbotneo.php" <?= $data['editor_url'] == 'mbotneo.php' ? 'selected' : '' ?>>
                                        mBot Neo
                                    </option>

                                    <option value="tinybit_ai.php" <?= $data['editor_url'] == 'tinybit_ai.php' ? 'selected' : '' ?>>
                                        Tinybit AI Vision
                                    </option>

                                    <option value="scratchJr.php" <?= $data['editor_url'] == 'scratchJr.php' ? 'selected' : '' ?>>
                                        Scratch Jr
                                    </option>

                                    <option value="scratch.php" <?= $data['editor_url'] == 'scratch.php' ? 'selected' : '' ?>>
                                        Scratch
                                    </option>

                                    <option value="spherobolt.php" <?= $data['editor_url'] == 'spherobolt.php' ? 'selected' : '' ?>>
                                        Sphero Bolt
                                    </option>

                                    <option value="mbot.php" <?= $data['editor_url'] == 'mbot.php' ? 'selected' : '' ?>>
                                        mBot Robot Coding
                                    </option>

                                    <option value="arduino.php" <?= $data['editor_url'] == 'arduino.php' ? 'selected' : '' ?>>
                                        Arduino
                                    </option>

                                </select>
                            </div>
                            <?php
                            $existing_problem_image = $data['problem_image'] ?? '';
                            ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    problem Image
                                </label>

                                <input type="file" name="problem_image" id="problemImage" accept="image/*" hidden
                                    onchange="previewImage(this, 'problemPreview')">

                                <div class="image-wrapper">
                                    <img id="problemPreview" src="<?= !empty($existing_problem_image)
                                        ? 'uploads/' . htmlspecialchars($existing_problem_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="problem image"
                                        onclick="document.getElementById('problemImage').click();">

                                    <?php if (!empty($existing_problem_image)) { ?>
                                        <span class="remove-image" onclick="deleteProblemImage(<?= $data['problem_id'] ?>)">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- BUTTONS -->
                            <div class="text-end">
                                <button type="button" onclick="window.history.back()" class="btn btn-secondary px-4">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-primary px-4 btn-space"
                                    style="background:#1da1f2;color:#fff;">
                                    <i class="fas fa-save"></i> Update problem
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

        function deleteProblemImage(problemId) {

            fetch("problem_image_delete.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "problem_id=" + problemId
            })
                .then(res => res.text())
                .then(res => {
                    if (res.trim() === "success") {
                        document.getElementById("problemPreview").src =
                            "images/systemimages/placeholder-image.png";
                        showMessage("Image removed successfully", "success");
                        document.querySelector(".remove-image").remove();
                    } else {
                        showMessage("Failed to remove image", "danger");
                    }
                });
        }
    </script>




</body>