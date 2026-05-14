<style>
    .asset-gallery {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        /* ✅ 4 per row */
        gap: 20px;
    }

    /* CARD */
    .asset-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        transition: 0.2s;
        display: flex;
        flex-direction: column;
    }

    .asset-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
    }

    /* THUMB */
    .asset-thumb {
        height: 160px;
        background: #f3f3f3;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .asset-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* ICON */
    .asset-icon {
        font-size: 36px;
        color: #777;
    }

    .asset-icon.video {
        color: #dc3545;
    }

    .asset-icon.code {
        color: #0d6efd;
    }

    /* BODY */
    .asset-name {
        padding: 8px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }

    /* ACTIONS */
    .asset-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding-bottom: 10px;
    }

    .video-thumb {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .asset-thumb img,
    .asset-thumb video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #fff;
        /* 🔥 IMPORTANT */
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

    .code-preview{
    font-size:11px;
    padding:8px;
    margin:0;
    width:100%;
    height:100%;
    overflow:hidden;
    white-space:pre-wrap;   /* line wrap */
    word-break:break-word;  /* long words break */
    background:#f8f9fa;
    font-family:monospace;
}
</style>
<?php
session_start();
include 'config.php';
include_once 'standard_constants.php';
$type = $_GET['type'] ?? '';

$imageExt = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
$videoExt = ['mp4', 'webm', 'ogg'];

$result = mysqli_query($conn, "SELECT * FROM assets ORDER BY asset_id DESC"); ?>
<div class="row g-4">
    <?php while ($row = mysqli_fetch_assoc($result)) {

        $name = $row['asset_name'];
        $path = BASE_URL . $row['asset_file_path'];
        if (!$path)
            continue;

        $imageExt = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        $videoExt = ['mp4', 'webm', 'ogg'];
         $textExt = ['js', 'py', 'php', 'html','java', 'c', 'cpp','gltf','glb'];

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $isImage = in_array($ext, $imageExt);
        $isVideo = in_array($ext, $videoExt);
        $isText = in_array($ext, $textExt);
        // $isOther = !$isImage && !$isVideo && !$isText;
        $isOther = !$isImage && !$isVideo;






        // ✅ TAB FILTER LOGIC
        if ($type === 'image' && !$isImage)
            continue;
        if ($type === 'video' && !$isVideo)
            continue;
        if ($type === 'code' && ($isImage || $isVideo))
            continue;

        ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">

            <div class="asset-card">

                <div class="asset-thumb">

                    <!-- 🖼 IMAGE -->
                    <?php if ($isImage) { ?>

                        <img src="<?= htmlspecialchars($path) ?>">

                        <!-- 🎥 VIDEO (REAL PREVIEW) -->
                    <?php } elseif ($isVideo) { ?>

                        <div class="video-thumb">
                            <video src="<?= htmlspecialchars($path) ?>" muted preload="metadata"></video>

                            <div class="play-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>

                        <!-- 📄 File / TEXT FILE -->
                    <?php } elseif ($isText) { ?>

    <pre class="code-preview">
        <?= htmlspecialchars(substr(@file_get_contents($path), 0, 400)) ?>
    </pre>

<?php } else { ?>

    <div class="asset-icon code">
        <i class="fas fa-file"></i>
        <div style="font-size:12px;"><?= strtoupper($ext) ?></div>
    </div>

<?php } ?>

                </div>


                <div class="asset-name"><?= htmlspecialchars($name) ?></div>

                <div class="asset-actions">
                    <button type="button" class="btn  btn-primary" onclick="copyText('<?= $path ?>')" title="Copy link">
                        <i class="fas fa-copy"></i>
                    </button>
                     <?php if (userHasPermission(ASSETS_USE_BUTTON)) { ?>
                    <button type="button" class="btn  btn-primary"
                        onclick="insertAssetLink('<?= htmlspecialchars($path) ?>')" title="Insert Asset Link">
                        Use
                    </button>
                    <?php } ?>

                </div>

            </div>
        </div>
    <?php } ?>
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

function userHasPermission($permissionId)
{
    return isset($_SESSION['UserPermissions'][$permissionId]);
}
?>