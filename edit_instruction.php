<?php
include 'header.php';
include 'config.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Invalid Instruction");
}

/* FETCH INSTRUCTION */
$stmt = $conn->prepare("
    SELECT 
        id,
        exercise_id,
        instruction_text,
        instruction_image,
        hint_text,
        hint_image,
        hint_video
    FROM exercise_instruction_hints
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Instruction not found");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Instruction</title>


</head>

<body>

    <div class="container-fluid p-4">
        <div class="layout-row">

            <!-- SIDEBAR -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- MAIN -->
            <div class="main-area">
                <div id="globalMsg" class="global-msg" style="display:none;"></div>

                <div class="card shadow-sm" style="border-radius:12px; max-width:2000px; top:18px;">

                    <!-- HEADER -->
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;border-radius:12px 12px 0 0;">
                        <h4 class="m-0 fw-bold">
                            <i class=""></i> Edit Instruction
                        </h4>

                    </div>

                    <!-- BODY -->
                    <div class="card-body p-4">
                        <form action="instruction_update.php" method="POST" enctype="multipart/form-data">



                            <input type="hidden" name="instruction_id" value="<?= $data['id'] ?>">
                            <input type="hidden" name="exercise_id" value="<?= $data['exercise_id'] ?>">

                            <!-- Instruction -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Instruction Text *
                                </label>
                                <textarea name="instruction_text" class="form-control" rows="3"
                                    required><?= htmlspecialchars($data['instruction_text']) ?></textarea>
                            </div>
                            <?php
                            $existing_instruction_image = $data['instruction_image'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Instruction Image
                                </label>

                                <!-- Hidden file input -->
                                <input type="file" name="instruction_image" id="instructionImage" accept="image/*"
                                    hidden onchange="previewImage(this, 'instructionPreview')">

                                <div class="image-upload-box"
                                    onclick="document.getElementById('instructionImage').click();">

                                    <img id="instructionPreview" src="<?php echo (!empty($existing_instruction_image))
                                        ? 'uploads/' . htmlspecialchars($existing_instruction_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>"
                                        alt="Upload instruction image">
                                </div>

                            </div>


                            <!-- Hint -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Hint Text
                                </label>
                                <textarea name="hint_text" class="form-control"
                                    rows="3"><?= htmlspecialchars($data['hint_text']) ?></textarea>
                            </div>

                            <?php
                            $existing_hint_image = $data['hint_image'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Hint Image
                                </label>

                                <!-- Hidden file input -->
                                <input type="file" name="hint_image" id="hintImage" accept="image/*" hidden
                                    onchange="previewImage(this, 'hintPreview')">

                                <div class="image-upload-box" onclick="document.getElementById('hintImage').click();">

                                    <img id="hintPreview" src="<?php echo (!empty($existing_hint_image))
                                        ? 'uploads/' . htmlspecialchars($existing_hint_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="Upload hint image">
                                </div>

                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Hint Video (URL)
                                </label>
                                <input type="text" name="hint_video" class="form-control"
                                    value="<?= htmlspecialchars($data['hint_video']) ?>">
                            </div>

                            <!-- BUTTONS -->
                            <div class="text-end">
                                <button type="button" onclick="window.history.back()" class="btn btn-secondary px-4">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-primary px-4 btn-space"
                                    style="background:#1da1f2;color:#fff;">
                                    <i class="fas fa-save"></i> Update Instruction
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