<?php
include 'header.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add School</title>

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

                <div id="globalMsg" class="global-msg"></div>

                <div class="card shadow">

                    <div class="card-header" style="background:#1da1f2; color:white;">
                        <h4 class="m-0 fw-bold">Add School</h4>
                    </div>

                    <div class="card-body p-4">

                        <form action="school_save.php" method="POST" enctype="multipart/form-data">

                            <!-- School Name -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
                                <input type="text" name="school_name" class="form-control" required>
                            </div>

                            <!-- Username -->
                            <!-- <div class="mb-3">
                                <label class="form-label fw-semibold">School Username <span class="text-danger">*</span></label>
                                <input type="text" name="school_username" class="form-control" required>
                            </div> -->

                            <!-- Password -->
                            <!-- <label class="form-label fw-semibold">Password <span class="text-danger">*</span> </label>
                            <div class="input-group mb-3">
                                <input type="password" name="school_password" class="form-control" id="schoolPassword" required>
                                <span class="input-group-text bg-primary" style="cursor:pointer;" onclick="togglePassword('schoolPassword', this)"> <i class="fas fa-eye-slash text-white"></i></span>
                            </div> -->

                            <!-- School Image -->
                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    School Profile Image
                                </label>

                                <input type="file" name="school_profile_image" id="schoolImage" accept="image/*" hidden
                                    onchange="previewImage(this,'schoolPreview')">

                                <input type="hidden" name="remove_school_image" id="removeSchoolImage" value="0">

                                <div class="image-wrapper">

                                    <img id="schoolPreview" src="images/systemimages/placeholder-image.png"
                                        onclick="document.getElementById('schoolImage').click();">

                                    <span class="remove-image" onclick="removeSchoolImage()">
                                        <i class="fas fa-times"></i>
                                    </span>

                                </div>

                            </div>

                            <div class="text-end">

                                <button type="button" onclick="window.history.back()" class="btn btn-secondary px-4">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-primary px-4"
                                    style="background:#1da1f2;color:#fff;">

                                    <i class="fas fa-save"></i> Save School

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

        function removeSchoolImage() {

            document.getElementById("schoolPreview").src =
                "images/systemimages/placeholder-image.png";

            document.getElementById("schoolImage").value = "";

            document.getElementById("removeSchoolImage").value = "1";

        }

    </script>

</body>

</html>