<?php

include 'config.php';
include 'header.php';

$school_id = intval($_GET['school_id'] ?? 0);

if ($school_id <= 0) {
    die("Invalid school");
}

/* FETCH SCHOOL */
$stmt = $conn->prepare("
    SELECT *
    FROM schools
    WHERE school_id = ?
");

$stmt->bind_param("i", $school_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit School</title>

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
        }
    </style>

</head>

<body>

    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area">

                <div class="card shadow">

                    <div class="card-header"
                        style="background:#1da1f2; color:white;">

                        <h4 class="m-0 fw-bold">Edit School</h4>

                    </div>

                    <div class="card-body p-4">

                        <form action="school_save.php" method="POST" enctype="multipart/form-data">

                            <input type="hidden" name="school_id" value="<?= $data['school_id'] ?>">

                            <!-- SCHOOL NAME -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    School Name <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="school_name" class="form-control"
                                    value="<?= htmlspecialchars($data['school_name']) ?>" required>

                            </div>

                            <!-- USERNAME -->
                            <!-- <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    School Username <span class="text-danger">*</span>
                                </label>

                                <input type="text" name="school_username" class="form-control"
                                    value="<?= htmlspecialchars($data['school_username']) ?>" required>

                            </div> -->

                            <!-- PASSWORD -->
                             <!-- <label class="form-label fw-semibold"> Password <span class="text-danger">*</span></label>
                            <div class="input-group mb-3">
                                <?php
                                    $key = DECRYPT_KEY;
                                    $iv = substr(hash("sha256", $key), 0, 16);
                                    $decryptedPassword = openssl_decrypt($data['school_password'], "AES-256-CBC", $key, 0, $iv);
                                ?>
                                

                                <input type="password" name="school_password" class="form-control"
                                    value="<?= htmlspecialchars($decryptedPassword) ?>" id="schoolPassword" required>
                                    <span class="input-group-text bg-primary" style="cursor:pointer;"
                                        onclick="togglePassword('schoolPassword', this)">
                                        <i class="fas fa-eye-slash text-white"></i>
                                    </span>

                            </div> -->

                            <?php
                            $existing_image = $data['school_profile_image'] ?? '';
                            ?>

                            <!-- IMAGE -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    School Profile Image
                                </label>

                                <input type="file" name="school_profile_image" id="schoolImage" accept="image/*" hidden
                                    onchange="previewImage(this,'schoolPreview')">

                                <div class="image-wrapper">

                                    <img id="schoolPreview" src="<?= !empty($existing_image)
                                        ? htmlspecialchars($existing_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>"
                                    onclick="document.getElementById('schoolImage').click();">

                                  <?php if (!empty($existing_image)) { ?>

                                        <span class="remove-image" onclick="deleteSchoolImage(<?= $data['school_id'] ?>)">

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

                                <button type="submit" class="btn btn-primary px-4"
                                    style="background:#1da1f2;color:#fff;">

                                    <i class="fas fa-save"></i>
                                    Update School

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

        function deleteSchoolImage(schoolId) {

            fetch("school_image_delete.php", {

                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "school_id=" + schoolId

            })
                .then(res => res.text())
                .then(res => {

                    if (res.trim() === "success") {

                        document.getElementById("schoolPreview").src =
                            "images/systemimages/placeholder-image.png";

                        document.querySelector(".remove-image").remove();

                        
                    } else {

                        alert("Failed to remove image");

                    }

                });

        }

    </script>

</body>

</html>