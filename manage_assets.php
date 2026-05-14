<?php

include 'header.php';
include 'config.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My assets </title>
    <style>
        .upload-box pre {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 8px;
            font-size: 11px;
            overflow: hidden;
            white-space: pre-wrap;
        }

        .upload-box {
            width: 100%;
            height: 260px;
            border: 2px dashed #bbb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: #fafafa;
        }

        .remove-image {
            top: 157px;
            right: 20px;
        }

        .upload-box img {
            max-width: 100%;
            max-height: 100%;
            border-radius: 8px;
        }

        .file-placeholder {
            text-align: center;
            color: #777;
        }

        .file-placeholder i {
            font-size: 60px;
            margin-bottom: 8px;
        }

        .file-placeholder p {
            font-size: 16px;
        }

        /* ===== ASSET GALLERY ===== */
        .asset-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 🔥 4 cards per row */
            gap: 30px;
        }

        .card-actions {
            position: absolute;
            top: 8px;
            right: 8px;

        }

        .card-actions i {
            cursor: pointer;
            font-size: 21px;
        }


        .asset-card {
            position: relative;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: 0.2s;
            height: 369px;
            /* ⭐ FIXED HEIGHT */
            width: 270px;
            display: flex;
            flex-direction: column;
        }


        .asset-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
        }

        /* THUMB */
        .asset-thumb {
            height: 401px;
            /* same for all */
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .asset-thumb img,
        .asset-thumb video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #fff;
            /* 🔥 IMPORTANT */
        }


        /* ICONS */
        .asset-icon {
            font-size: 42px;
        }

        .asset-icon.video {
            color: #dc3545;
        }

        .asset-icon.code {
            color: #0d6efd;
        }

        /* BODY */
        .asset-body {
            padding: 8px;
            text-align: center;
            height: 60px;
            /* FIXED */
        }

        .asset-thumb pre {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 8px;
            font-size: 11px;
            overflow: hidden;
            white-space: pre-wrap;
        }


        .asset-name {
            font-size: 21px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ACTIONS */
        .asset-actions {
            margin-top: 6px;
            display: flex;
            justify-content: center;
            gap: 14px;
        }

        .asset-actions i {
            cursor: pointer;
            font-size: 16px;
        }

        .video-thumb {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .video-thumb video {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: #fff;
        }

        .play-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.6);
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .play-overlay i {
            color: white;
            font-size: 22px;
            margin-left: 3px;
        }
    </style>


</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>

                
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">Manage assets</h3>
                        <button class="btn btn-primary" id="addassetBtn" onclick="openAddAssetModal()"><i
                                class="fas fa-plus-circle"></i> Add
                            asset</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4 ">
                        <form method="GET">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search assets</span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Enter asset name...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                    <button class="btn btn-secondary"><i class="fas fa-times-circle"></i>
                                        Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $search = isset($_GET['search']) ? trim($_GET['search']) : "";

                    $assetQuery = "SELECT * FROM assets";

                    if ($search !== "") {
                        $assetQuery .= " AND asset_name LIKE '%" . $conn->real_escape_string($search) . "%'";
                    }

                    $assetQuery .= " ORDER BY asset_id ASC";

                    $assetResult = $conn->query($assetQuery);

                    ?>
                    <!-- asset List Display Here -->
                    <?php if ($assetResult->num_rows > 0): ?>
                        <div class="asset-gallery">

                            <?php
                            while ($row = mysqli_fetch_assoc($assetResult)) {

                                $name = htmlspecialchars($row['asset_name']);
                                $path = $row['asset_file_path'];
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                                $isImage = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                $isVideo = in_array($ext, ['mp4', 'webm', 'ogg']);
                                $isText = in_array($ext, ['txt', 'js', 'py', 'php', 'html', 'css', 'java', 'c', 'cpp']);
                                ?>

                                <div class="asset-card" data-asset-id="<?= $row['asset_id'] ?>">

                                    <!-- THUMB -->
                                    <div class="asset-thumb">

                                        <!-- IMAGE -->
                                        <?php if ($isImage): ?>
                                            <img src="<?= $path ?>" alt="<?= $name ?>">

                                            <!-- VIDEO -->
                                        <?php elseif ($isVideo): ?>
                                            <div class="video-thumb">
                                                <video src="<?= $path ?>" muted></video>
                                                <div class="play-overlay">
                                                    <i class="fas fa-play"></i>
                                                </div>
                                            </div>


                                            <!-- TEXT SOURCE CODE -->
                                        <?php elseif ($isText): ?>
                                            <pre><?= getTextPreview($path) ?></pre>

                                            <!-- OTHER FILE -->
                                        <?php else: ?>
                                            <i class="fas fa-file asset-icon code"></i>
                                        <?php endif; ?>

                                    </div>

                                    <!-- BODY -->
                                    <div class="asset-body">
                                        <div class="asset-name" title="<?= $name ?>"><?= $name ?></div>
                                    </div>

                                    <!-- 🔝 TOP RIGHT ACTIONS -->
                                    <div class="card-actions">
                                        <a href="#"
                                            onclick="editasset('<?= $row['asset_id']; ?>','<?= htmlspecialchars($row['asset_name']); ?>','<?= $row['asset_file_path']; ?>')">
                                            <i class="fas fa-edit text-primary" title="Edit asset"></i>
                                        </a>

                                        <i class="fas fa-trash text-danger" title="Delete asset"
                                            onclick="openDeleteModal('asset_delete.php?id=<?= $row['asset_id']; ?>','Are you sure you want to delete this Asset?')">
                                        </i>
                                    </div>


                                </div>

                            <?php } ?>
                        </div>


                    <?php else: ?>
                        <p>No assets found.</p>
                    <?php endif; ?>



                

                <!-- Modal -->
                <div class="modal fade text-dark" id="assetModal" tabindex="-1">
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <form method="POST" action="asset_save.php" enctype="multipart/form-data" id="assetForm"
                                onsubmit="return validateAssetForm()">

                                <div class="modal-header bg-primary">
                                    <h3 class="modal-title text-white" id="modalTitle">Add Asset
                                    </h3>
                                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                                        <i class="fas fa-times fs-4"></i>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <input type="hidden" name="assetId" id="assetId">
                                    <label class="form-label">Asset <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="assetName" id="assetName"
                                        placeholder="Enter asset name" required>
                                </div>

                                <div class="modal-body">

                                    <label class="fw-bold">Asset Type <span class="text-danger">*</span></label>
                                    <select class="form-control mb-3" id="assetType" onchange="updateAcceptType()">
                                        <option value="">-- Select Type --</option>
                                        <option value="video">Video</option>
                                        <option value="image">Image</option>
                                        <option value="srccode">File</option>
                                    </select>

                                    <!-- IMAGE UPLOAD BOX -->
                                    <div id="imageUploadBox" style="display:none;" class="mt-2">
                                        <label class="fw-semibold">Image <span class="text-danger">*</span></label>

                                        <input type="file" id="imageFile" hidden accept="image/*"
                                            onchange="previewImage(this,'imagePreview')">

                                        <div class="upload-box" onclick="document.getElementById('imageFile').click();">
                                            <img id="imagePreview" src="images/systemimages/placeholder-image.png">
                                            <!-- IMAGE REMOVE -->
                                            <span class="remove-image"
                                                onclick="removeProblemImage(event); removeExistingAsset();">
                                                <i class="fas fa-times"></i>
                                            </span>

                                        </div>
                                    </div>

                                    <!-- FILE UPLOAD BOX (Video / Source Code) -->
                                    <div id="fileUploadBox" style="display:none;" class="mt-2">
                                        <label class="fw-semibold" id="fileBoxLabel">File</label>

                                        <input type="file" id="assetFile" hidden onchange="showFileName(this)">

                                        <div class="upload-box" onclick="document.getElementById('assetFile').click();">

                                            <!-- ICON / TEXT -->
                                            <div class="file-placeholder" id="filePlaceholder">
                                                <i class="fas fa-cloud-upload-alt text-primary"></i>
                                                <p id="filePlaceholderText">Click to upload file</p>
                                            </div>

                                            <!-- 🎥 VIDEO PREVIEW -->
                                            <video id="videoPreview"
                                                style="display:none; width:100%; height:100%; object-fit:cover;" muted
                                                controls></video>

                                            <!-- 📄 TEXT PREVIEW -->
                                            <pre id="textPreview" style="display:none;"></pre>
                                            <!-- FILE/VIDEO REMOVE -->
                                            <span class="remove-image"
                                                onclick="removeProblemImage(event); removeExistingAsset();">
                                                <i class="fas fa-times"></i>
                                            </span>


                                        </div>



                                    </div>

                                </div>




                                <div class="modal-footer">
                                    <a href="manage_assets.php" class="btn btn-secondary" data-bs-dismiss="modal"
                                        id="cancelbtn">Cancel</a>
                                    <button class="btn btn-primary" type="submit" id="saveBtn">Save</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php

    function getTextPreview($filePath, $limit = 400)
    {
        if (!file_exists($filePath))
            return '';

        $content = file_get_contents($filePath);
        $content = htmlspecialchars($content);
        return substr($content, 0, $limit);
    }
    ?>

    <script>
        function validateAssetForm() {

            const name = document.getElementById("assetName").value.trim();
            const type = document.getElementById("assetType").value;

            const imageFile = document.getElementById("imageFile").files[0];
            const fileUpload = document.getElementById("assetFile").files[0];

            if (name === "") {
                showMessage("Please enter asset name", "error");
                return false;
            }

            if (type === "") {
                showMessage("Please select asset type", "error");
                return false;
            }

            if (type === "image" && !imageFile) {
                showMessage("Please upload image", "error");
                return false;
            }

            if ((type === "video" || type === "srccode") && !fileUpload) {
                showMessage("Please upload file", "error");
                return false;
            }

            return true;
        }
    </script>
    <script>
        function removeProblemImage(event) {
            event.stopPropagation(); // Prevent triggering parent click (upload)

            // IMAGE UPLOAD
            const imageBox = document.getElementById('imageUploadBox');
            const imageInput = document.getElementById('imageFile');
            const imagePreview = document.getElementById('imagePreview');

            // FILE UPLOAD (video or source code)
            const fileBox = document.getElementById('fileUploadBox');
            const fileInput = document.getElementById('assetFile');
            const videoPreview = document.getElementById('videoPreview');
            const textPreview = document.getElementById('textPreview');
            const placeholder = document.getElementById('filePlaceholder');
            const placeholderText = document.getElementById('filePlaceholderText');

            // Check which box triggered
            if (event.target.closest('#imageUploadBox')) {
                imageInput.value = ""; // clear file input
                imagePreview.src = "images/systemimages/placeholder-image.png"; // reset preview
            }
            else if (event.target.closest('#fileUploadBox')) {
                fileInput.value = ""; // clear file input

                // Reset video and text previews
                videoPreview.style.display = "none";
                videoPreview.src = "";
                textPreview.style.display = "none";
                textPreview.textContent = "";

                // Reset placeholder
                placeholder.style.display = "flex";
                placeholderText.innerText = "Click to upload file";
            }
        }

        function removeExistingAsset() {
            if (!currentAssetId) return;

            fetch('asset_delete_file.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ assetId: currentAssetId })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage("Asset deleted successfully!", "success");

                        // Get the card
                        const card = document.querySelector(`.asset-card[data-asset-id='${currentAssetId}']`);
                        if (card) {
                            const thumb = card.querySelector('.asset-thumb');
                            if (thumb) {
                                // Determine current type
                                const img = thumb.querySelector('img');
                                const video = thumb.querySelector('video');
                                const pre = thumb.querySelector('pre'); // for text/source code
                                const icon = thumb.querySelector('i');

                                if (img) {
                                    // Image: reset to placeholder image
                                    img.src = "images/systemimages/placeholder-image.png";
                                } else if (video) {
                                    // Video: remove video and show placeholder icon
                                    thumb.innerHTML = `<i class="fas fa-file asset-icon code"></i>`;
                                } else if (pre) {
                                    // Source code/text: remove pre and show placeholder icon
                                    thumb.innerHTML = `<i class="fas fa-file asset-icon code"></i>`;
                                } else if (icon) {
                                    // Already placeholder icon, nothing to do
                                } else {
                                    // Default fallback
                                    thumb.innerHTML = `<i class="fas fa-file asset-icon code"></i>`;
                                }

                            }
                        }

                        // resetAssetModal();
                        currentAssetId = null;
                    } else {
                        showMessage("Failed to delete asset", "danger");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showMessage("Failed to delete asset", "danger");
                });
        }

        function updateAcceptType() {

            document.getElementById("videoPreview").style.display = "none";
            document.getElementById("videoPreview").src = "";
            document.getElementById("textPreview").style.display = "none";


            const type = document.getElementById("assetType").value;

            const imageBox = document.getElementById("imageUploadBox");
            const fileBox = document.getElementById("fileUploadBox");

            const imageInput = document.getElementById("imageFile");
            const fileInput = document.getElementById("assetFile");

            // RESET
            imageBox.style.display = "none";
            fileBox.style.display = "none";

            imageInput.removeAttribute("name");
            fileInput.removeAttribute("name");

            if (type === "image") {
                imageBox.style.display = "block";
                imageInput.setAttribute("name", "assetFilePath");
                document.getElementById("imagePreview").src =
                    "images/systemimages/placeholder-image.png";
            }

            else if (type === "video") {
                fileBox.style.display = "block";
                fileInput.setAttribute("name", "assetFilePath");
                fileInput.accept = "video/*";
                document.getElementById("fileBoxLabel").innerHTML = 'Video <span class="text-danger">*</span>';
                document.getElementById("filePlaceholderText").innerText = "Click to upload video";
            }

            else if (type === "srccode") {
                fileBox.style.display = "block";
                fileInput.setAttribute("name", "assetFilePath");
                fileInput.accept = ".zip,.rar,.txt,.js,.py,.java,.c,.cpp";
                document.getElementById("fileBoxLabel").innerHTML = 'File <span class="text-danger">*</span>';
                document.getElementById("filePlaceholderText").innerText = "Click to upload source code";
            }
        }



        function showFileName(input) {

            if (!input.files || !input.files[0]) return;

            const file = input.files[0];
            const ext = file.name.split('.').pop().toLowerCase();

            const placeholder = document.getElementById("filePlaceholder");
            const placeholderText = document.getElementById("filePlaceholderText");

            const video = document.getElementById("videoPreview");
            const text = document.getElementById("textPreview");

            // reset
            placeholder.style.display = "none";
            video.style.display = "none";
            text.style.display = "none";

            placeholderText.innerText = file.name;

            // 🎥 VIDEO
            if (['mp4', 'webm', 'ogg'].includes(ext)) {
                video.src = URL.createObjectURL(file);
                video.style.display = "block";
            }

            // 📄 SOURCE CODE
            else if (['txt', 'js', 'py', 'php', 'html', 'css', 'java', 'c', 'cpp'].includes(ext)) {
                const reader = new FileReader();
                reader.onload = e => {
                    text.textContent = e.target.result.substring(0, 500);
                    text.style.display = "block";
                };
                reader.readAsText(file);
            }

            // 📦 OTHER FILE
            else {
                placeholder.style.display = "flex";
            }
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e =>
                    document.getElementById(previewId).src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>


    <script>
        const modalAssign = new bootstrap.Modal(document.getElementById('assetModal'));

        // ADD asset (Reset form)
        function openAddAssetModal() {

            document.getElementById('modalTitle').innerText = "Add asset";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('assetId').value = "";
            document.getElementById('assetName').value = "";
            resetAssetModal();
            modalAssign.show();
        }





        // EDIT asset (Open same modal)


        function editasset(id, name, path) {
            currentAssetId = id;

            document.getElementById('modalTitle').innerText = "Edit asset";
            document.getElementById('saveBtn').innerText = "Update";

            document.getElementById('assetId').value = id;
            document.getElementById('assetName').value = name;

            const imageBox = document.getElementById("imageUploadBox");
            const fileBox = document.getElementById("fileUploadBox");

            const placeholder = document.getElementById("filePlaceholder");
            const placeholderText = document.getElementById("filePlaceholderText");

            const video = document.getElementById("videoPreview");
            const text = document.getElementById("textPreview");

            // RESET ALL
            imageBox.style.display = "none";
            fileBox.style.display = "none";

            placeholder.style.display = "flex";
            video.style.display = "none";
            video.src = "";
            text.style.display = "none";
            text.textContent = "";

            if (!path) {
                modalAssign.show();
                return;
            }

            const ext = path.split('.').pop().toLowerCase();

            // 🖼 IMAGE
            if (['png', 'jpg', 'jpeg', 'gif', 'webp'].includes(ext)) {

                document.getElementById("assetType").value = "image";
                imageBox.style.display = "block";
                document.getElementById("imageFile").setAttribute("name", "assetFilePath");
                document.getElementById("imagePreview").src = path;
            }

            // 🎥 VIDEO
            else if (['mp4', 'webm', 'ogg'].includes(ext)) {

                document.getElementById("assetType").value = "video";
                fileBox.style.display = "block";
                document.getElementById("assetFile").setAttribute("name", "assetFilePath");

                document.getElementById("fileBoxLabel").innerText = "Video";
                placeholderText.innerText = path.split('/').pop();

                placeholder.style.display = "none";
                video.src = path;
                video.style.display = "block";
            }

            // 📄 SOURCE CODE
            else {

                document.getElementById("assetType").value = "srccode";
                fileBox.style.display = "block";
                document.getElementById("assetFile").setAttribute("name", "assetFilePath");

                document.getElementById("fileBoxLabel").innerText = "File";
                placeholderText.innerText = path.split('/').pop();

                placeholder.style.display = "none";

                fetch(path)
                    .then(res => res.text())
                    .then(data => {
                        text.textContent = data.substring(0, 500);
                        text.style.display = "block";
                    });
            }

            modalAssign.show();
        }

        function resetAssetModal() {

            document.getElementById('modalTitle').innerText = "Add asset";
            document.getElementById('saveBtn').innerText = "Save";

            document.getElementById('assetId').value = "";
            document.getElementById('assetName').value = "";

            // reset type
            document.getElementById("assetType").value = "";

            // hide upload boxes
            document.getElementById("imageUploadBox").style.display = "none";
            document.getElementById("fileUploadBox").style.display = "none";

            // reset inputs
            document.getElementById("imageFile").value = "";
            document.getElementById("assetFile").value = "";

            // reset image preview
            document.getElementById("imagePreview").src =
                "images/systemimages/placeholder-image.png";

            // reset file preview
            const placeholder = document.getElementById("filePlaceholder");
            const placeholderText = document.getElementById("filePlaceholderText");
            const video = document.getElementById("videoPreview");
            const text = document.getElementById("textPreview");

            placeholder.style.display = "flex";
            placeholderText.innerText = "Click to upload file";

            video.style.display = "none";
            video.src = "";

            text.style.display = "none";
            text.textContent = "";
        }


    </script>

    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>

</body>

</html>