<?php
include 'header.php';
include 'config.php';

$exercise_id = intval($_GET['exercise_id'] ?? 0);
if ($exercise_id <= 0) {
    die("Invalid Exercise");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Instruction</title>
</head>

<body>

    <div class="container-fluid p-4">
        <div class="layout-row">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN -->

            <div class="main-area" style="position:relative;">

                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>



                <div class="card shadow-sm" style="border-radius:12px; max-width:2000px; top:18px;">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;border-radius:12px 12px 0 0;">
                        <h4 class="m-0 fw-bold">
                            <i class=""></i> Add Instruction
                        </h4>

                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">
                        <form action="instruction_insert.php" method="POST" enctype="multipart/form-data">




                            <input type="hidden" name="exercise_id" value="<?= $exercise_id ?>">

                            <!-- Instruction -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Instruction Text *
                                </label>
                                <textarea name="instruction_text" class="form-control" rows="3" required></textarea>
                            </div>

                            <?php
                            $existing_instruction_image = $data['instruction_image'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Instruction Image
                                </label>

                                <input type="file" name="instruction_image" id="instructionImage" accept="image/*"
                                    hidden onchange="previewImage(this, 'instructionPreview')">

                                <div class="image-upload-box"
                                    onclick="document.getElementById('instructionImage').click();">
                                    <img id="instructionPreview" src="<?= !empty($existing_instruction_image)
                                        ? 'uploads/' . htmlspecialchars($existing_instruction_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="Instruction Image">
                                </div>
                            </div>


                            <!-- Hint -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Hint Text
                                </label>
                                <textarea name="hint_text" class="form-control" rows="3"></textarea>
                            </div>

                            <?php
                            $existing_hint_image = $data['hint_image'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Hint Image
                                </label>

                                <input type="file" name="hint_image" id="hintImage" accept="image/*" hidden
                                    onchange="previewImage(this, 'hintPreview')">

                                <div class="image-upload-box" onclick="document.getElementById('hintImage').click();">
                                    <img id="hintPreview" src="<?= !empty($existing_hint_image)
                                        ? 'uploads/' . htmlspecialchars($existing_hint_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="Hint Image">
                                </div>
                            </div>


                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Hint Video (URL)
                                </label>
                                <input type="text" name="hint_video" class="form-control">
                            </div>

                            <!-- BUTTONS -->
                            <div class="text-end">
                                <button type="button" onclick="window.history.back()" class="btn btn-secondary px-4">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-primary px-4 btn-space"
                                    style="background:#1da1f2;color:#fff;">
                                    <i class="fas fa-save"></i> Save Instruction
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
    </script>



</body>

</html>