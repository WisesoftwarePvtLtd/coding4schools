<?php
session_start();
include 'header.php';
include 'config.php';

/* =========================
   GET EXERCISE
========================= */

$userRole = $_SESSION['LoggedInUserRoles'] ?? '';
$exercise_id = intval($_GET['exercise_id'] ?? 0);
$lesson_id = intval($_GET['lesson_id'] ?? 0);
$course_id = 0;

$stmt = $conn->prepare("
    SELECT course_id 
    FROM lessons 
    WHERE lesson_id = ?
");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $course_id = $row['course_id'];
}

$stmt->close();

if ($exercise_id <= 0) {
    die("Invalid Exercise");
}

/* =========================
   FETCH LESSON GAME FILE
========================= */
$lessonStmt = $conn->prepare("
    SELECT teacher_game_file, student_game_file
    FROM lessons
    WHERE lesson_id = ?
");
$lessonStmt->bind_param("i", $lesson_id);
$lessonStmt->execute();
$lessonData = $lessonStmt->get_result()->fetch_assoc();
$lessonStmt->close();

$teacherGame = $lessonData['teacher_game_file'] ?? '';
$studentGame = $lessonData['student_game_file'] ?? '';

/* =========================
   ROLE BASED FILE
========================= */
$gameFile = '';

// check roles from array
if (!empty($userRole['teacher'])) {
    $gameFile = $teacherGame;

} elseif (!empty($userRole['siteadmin'])) {
    // admin ko bhi teacher jaisa treat karna hai
    $gameFile = $teacherGame;

} else {
    $gameFile = $studentGame;
}

/* =========================
   FETCH EXERCISE + EDITOR
========================= */
$stmt = $conn->prepare("
    SELECT 
        e.exercise_id,
        e.exercise_name,
        ed.editor_url,
        e.exercise_discription,
        e.sprite_image,
        e.instruction_guideline,
        cc.code,
        e.exercise_sort_order,
        ed.editor_name
    FROM exercises e
    JOIN editors ed ON ed.editor_id = e.editor_id
    JOIN course_code cc ON cc.exercise_id = e.exercise_id
    WHERE e.exercise_id = ?
");
$stmt->bind_param("i", $exercise_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    die("Exercise not found");
}

$exercise = $res->fetch_assoc();
$stmt->close();
/* =========================
   FETCH INSTRUCTIONS
========================= */
$instructions = [];
$stmt = $conn->prepare("
    SELECT 
        id,
        instruction_text,
        instruction_image,
        hint_text,
        hint_image  
    FROM exercise_instruction_hints
    WHERE exercise_id = ?
    ORDER BY instruction_sort_order ASC   
");
$stmt->bind_param("i", $exercise_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    $instructions[] = $row;
}
$stmt->close();


/* =========================
   FETCH PREV & NEXT EXERCISE
========================= */

// PREVIOUS
$prev_exercise = null;
$stmt = $conn->prepare("
    SELECT exercise_id,exercise_sort_order 
    FROM exercises 
    WHERE exercise_sort_order < ? 
    AND lesson_id = ?
    ORDER BY exercise_sort_order DESC
    LIMIT 1
");
$stmt->bind_param("ii", $exercise['exercise_sort_order'], $lesson_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $prev_exercise = $res->fetch_assoc()['exercise_id'];
}
$stmt->close();

// NEXT
$next_exercise = null;
$stmt = $conn->prepare("
    SELECT  exercise_id,exercise_sort_order 
    FROM exercises 
    WHERE exercise_sort_order > ? 
    AND lesson_id = ?
    ORDER BY exercise_sort_order ASC 
    LIMIT 1
");
$stmt->bind_param("ii", $exercise['exercise_sort_order'], $lesson_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $next_exercise = $res->fetch_assoc()['exercise_id'];
}
$stmt->close();

?>
<?php
$allowedEditors = [
    "Microbit Programming",
    "Microbit JavaScript",
    "3D Drawing"

];

function renderInstruction($code)
{
    $links = [];

    // Step 1: Extract allowed <a> tags
    $code = preg_replace_callback('/<a\s+href="([^"]*)"[^>]*>(.*?)<\/a>/i', function ($matches) use (&$links) {

        $text = trim($matches[2]);

        if (strtolower($text) == 'click here') {
            $placeholder = "###LINK" . count($links) . "###";

            $links[$placeholder] = [
                'href' => $matches[1],
                'text' => $text
            ];

            return $placeholder;
        } else {
            return htmlspecialchars($matches[0]);
        }

    }, $code);

    // Step 2: Escape everything
    $code = htmlspecialchars($code);

    // Step 3: Restore only allowed links
    foreach ($links as $ph => $link) {
        $realLink = '<a href="' . htmlspecialchars($link['href']) . '" target="_blank" >'
            . htmlspecialchars($link['text']) . '</a>';

        $code = str_replace($ph, $realLink, $code);
    }

    return $code;
}

/* =========================
   FETCH QUIZ COUNT
========================= */

$quizCount = 0;
$singleQuizId = null;

$stmt = $conn->prepare("
    SELECT quiz_id 
    FROM quiz 
    WHERE lesson_id = ?
");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$res = $stmt->get_result();

$quizCount = $res->num_rows;

if ($quizCount === 1) {
    $row = $res->fetch_assoc();
    $singleQuizId = $row['quiz_id'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($exercise['exercise_name']) ?></title>
    <script>
        const courseId = "<?= $course_id ?>";
        const lessonId = "<?= $lesson_id ?>";
        const quizCount = <?= $quizCount ?>;
        const singleQuizId = "<?= $singleQuizId ?>";

        // function openQuiz() {
        //     // showLoader();
        //     window.location.href = "manage_quiz.php?course_id=" + courseId + "&lesson_id=" + lessonId;
        // }
        function openQuiz() {

            if (quizCount === 1) {
                // ✅ Direct attempt page
                window.location.href = "quiz_attempt.php?quiz_id="
                    + singleQuizId +
                    "&course_id=" + courseId +
                    "&lesson_id=" + lessonId;

            } else {
                // ✅ Multiple quizzes → list page
                window.location.href = "manage_quiz.php?course_id="
                    + courseId +
                    "&lesson_id=" + lessonId;
            }
        }
    </script>
    <style>
        .exercisetitle {
            background: #1da1f2;
            color: white;
            text-align: center;
            padding: 16px;
        }

        .exercise-name {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .exercise-description {
            font-size: 15px;
            font-weight: normal;
            line-height: 1.6;
            opacity: 0.95;
        }

        .editor-wrapper {
            height: 700px;
            border-bottom: 2px solid #ddd;
            margin-bottom: 40px;
        }

        .editor-wrapper iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* .content {
            display: flex;
            padding: 20px;
            gap: 20px;
            
        } */
        /* 
        .instructions {
            width: 20%;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        } */

        .instructions li {
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 6px;
            color: #1abc9c;
        }

        .instructions li.active {
            background: #d1ecf1;
            color: #010a0c !important;
        }

        #instructionList {
            text-align: left;
            list-style-position: inside;
            padding-left: 0;
        }

        #instructionList li {
            text-align: left;
        }

        /* .exercise-visual {
            position: relative;
            width: 80%;
            height: 100%;

            background: #fff;
            border-radius: 12px;
            min-height: 287px;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            overflow: visible;
        } */

        /* .exercise-img {
            width: 200px;
            height: 200px;
            display: block;

            object-fit: contain;
            
        } */

        /* .hint-btn {
            position: absolute;
            left: 25px;
            top: 55%;
            transform: translateY(-50%);
            background: #1abc9c;
            color: #fff;
            padding: 6px 18px;
            border-radius: 18px;
            cursor: pointer;
            font-weight: bold;
            z-index: 3;
        } */

        /* .hint-overlay {
            margin-left: 321px;
            margin-top: 52px;
        } */

        /* ONE HINT ROW */
        .hint-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
        }

        /* TEXT */
        .hint-text {
            background: #d1ecf1;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 18px;
            max-width: 100%;

            overflow: hidden;
            white-space: pre-wrap;
            word-break: break-word;
        }

        .hint-img {
            max-width: 100%;
            /* 🔥 fixed width */
            /* height: 500px; */
            /* 🔥 fixed height */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 8px;

        }

        .hint-img img {
            max-width: 100%;
            /* height: 100%; */
            object-fit: contain;
            /* 🔥 important */
            border-radius: 6px;
        }

        .exercise-actions {
            margin: 25px 0;
            display: flex;
            width: 78%;
            margin-left: auto;
        }

        .top-actions {
            margin: 25px 0;
            display: flex;
            gap: 20px;
        }

        .lesson-btn {
            background: #e74c3c;
            color: #fff;
            padding: 10px 22px;
            border-radius: 20px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .editor-btn {
            background: #2ecc71;
            color: #fff;
            padding: 10px 22px;
            border-radius: 20px;
            border: none;
            font-weight: bold;
            cursor: pointer;
        }

        .editoropen-btn {
            background: #2ecc71;
            color: #fff;
            padding: 10px 22px;
            border-radius: 20px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            margin-right: 10px;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .left-btns {
            margin-left: 10px;
            display: flex;
            gap: 10px;
            /* buttons ke beech space */
        }

        .right-btn {
            /* optional */
        }

        .sprite-box {
            /* position: absolute;
            top: 15px;
            left: 10px; */
            width: 90%;

        }


        .nav-arrows {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            display: flex;
        }

        .nav-left {
            margin-left: 20px;
        }

        .nav-right {
            margin-left: auto;
            /* 🔥 THIS IS THE KEY */
            margin-right: 20px;
        }


        .nav-left,
        .nav-right {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .next-prev-btn {


            width: 36px;
            height: 36px;

            background-color: #e74c3c;
            color: white;

        }

        .go-to-quiz-btn {


            width: 120px;
            height: 36px;

            background-color: #e74c3c;
            color: white;

        }


        .hint-container {
            display: flex;
            align-items: center;
            /* justify-content: center; */
            gap: 10px;
            /* margin-top: 100%; */
            z-index: 3;
        }

        .hint-btn {
            background: #1abc9c;
            color: #fff;
            border-radius: 20px;
            padding: 6px 16px;
            font-weight: bold;
            z-index: 3;
        }

        .hint-outside-arrow {
            font-size: 22px;
            color: #1abc9c;
            display: none;
        }

        .content {
            padding: 20px;
            
        }

        /* LEFT PANEL */
        .instructions {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
            padding: 15px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .instruction-text {
            font-size: 16px;
        }

        .instruction-item {
            color: #1abc9c;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .exercise-img {
            max-width: 100%;
            border-radius: 8px;
        }

        /* RIGHT PANEL */
        /* .exercise-visual {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
        } */

        .exercise-visual {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);

            display: flex;
            flex-direction: column;
            min-height: 500px;
            /* 🔥 important */
        }

        .exercise-content {
            flex-grow: 1;
        }

        .bottom-actions {
            margin-top: auto;
            padding-top: 15px;
        }

        /* HINT BOX */
        .hint-overlay {
            min-height: 80px;
            /* background: #f8f9fa; */
            border-radius: 10px;
            padding: 10px;
        }
        .next-right {
    margin-left: auto;
}
    </style>
    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                showMessage("Copied: " + text, "success");
            });
        }
    </script>
    <script>
        function loadAssets(type) {
            fetch("asset_list.php?type=" + type)
                .then(res => res.text())
                .then(html => {
                    document.getElementById(type + "Tab").innerHTML = html;
                });
        }
    </script>
    <script>
        function insertAssetLink(url) {
            const textarea = document.getElementById("instruction_text");

            let text = textarea.value.trim();
            if (text.length > 0 && !text.endsWith(" ")) {
                text += " ";
            }

            // ✅ anchor link insert karo
            text += '<a href="' + url + '" target="_blank">click here</a>';

            textarea.value = text;

            // close asset modal
            const modalEl = document.getElementById("assetModal");
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // 🔥 FIX scroll
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");
            document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
        }
    </script>

</head>


<body>
    <!-- DUSTY HOURGLASS LOADER -->
    <div id="page-loader" class="loader-overlay">
        <div class="hourglass"></div>
    </div>
    <div class="top-actions" style="margin-top: 97px;">

        <div class="left-btns">
            <button class="lesson-btn btn" onclick="goBack()">
                Back To Lesson
            </button>

            <button class="editor-btn btn" onclick="scrollToInstructions()">
                Go To Instructions
            </button>
        </div>

        <?php if (in_array($exercise['editor_name'], $allowedEditors)): ?>
            <div class="right-btn">
                <button class="editoropen-btn btn"
                    onclick="openEditor('<?= htmlspecialchars($exercise['editor_url']); ?>')">
                    Open Editor
                </button>
            </div>
        <?php endif; ?>

    </div>
    <div class="exercisetitle">
        <h3 style="text-align:center;">Exercise <?= htmlspecialchars($exercise['exercise_sort_order']) ?></h3>
        <div class="exercise-name">
            <?= htmlspecialchars($exercise['exercise_name']) ?>
        </div>

        <?php if (!empty($exercise['exercise_discription'])): ?>
            <div class="exercise-description">
                <strong>Description :</strong>
                <?= nl2br(htmlspecialchars($exercise['exercise_discription'])) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="editor-wrapper"
        style="<?= in_array($exercise['editor_name'], $allowedEditors) ? 'display:none;' : '' ?>">
        <div id="globalMsg" class="global-msg"></div>


        <!-- <iframe id="editorFrame" src="<?= htmlspecialchars($exercise['editor_url']) ?>"></iframe> -->
        <?php if ($exercise['editor_name'] === "Scratch") {

            $projectUrl = BASE_URL . "" . $gameFile;
            $encodedUrl = rawurlencode($projectUrl); ?>
            <iframe id="scratchFrame" src="makeymakey/build/index.html?project_url=<?= $encodedUrl ?>" width="100%"
                height="800" style="border:none;"></iframe>

        <?php } else { ?>
            <?php if (!in_array($exercise['editor_name'], $allowedEditors)): ?>
                <iframe id="editorFrame" src="<?= htmlspecialchars($exercise['editor_url']) ?>?exercise_id=<?= $exercise_id ?>">
                </iframe>
            <?php endif; ?>
        <?php } ?>


    </div>

    <div class="row content">

        <!-- LEFT: INSTRUCTIONS -->
        <div class="col-md-4 col-lg-3 instructions">
            <h3 class="text-center mb-3">Instructions</h3>

            <?php if (!empty($exercise['instruction_guideline'])): ?>
                <p class="instruction-text">
                    <?= nl2br(htmlspecialchars($exercise['instruction_guideline'])) ?>
                </p>
            <?php endif; ?>

            <ol id="instructionList">
                <?php foreach ($instructions as $inst): ?>

                    <?php if (!empty($inst['instruction_image'])): ?>
                        <img src="uploads/<?= $inst['instruction_image'] ?>" class="exercise-img mb-2">
                    <?php endif; ?>

                    <li class="instruction-item">


                        <?= renderInstruction($inst['instruction_text']) ?>


                    </li>

                <?php endforeach; ?>
            </ol>
        </div>


        <!-- RIGHT: VISUAL / HINT -->
        <div class="col-md-8 col-lg-9 exercise-visual">
            <div class="exercise-content">

                <div class="row">

                    <!-- LEFT PART (SPRITE + HINT BTN) -->
                    <div class="col-md-2 text-center">
                        <?php if (!empty($exercise['sprite_image'])): ?>

                            <div class="sprite-box mb-3">

                                <img src="uploads/<?= htmlspecialchars($exercise['sprite_image']) ?>" class="img-fluid">

                            </div>
                        <?php endif; ?>
                        <div class="hint-container d-flex  align-items-center gap-2">
                            <span class="hint-outside-arrow" id="hintArrow">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                            <button class="hint-btn btn" onclick="nextHint()">Hint</button>
                        </div>


                    </div>

                    <!-- RIGHT PART (HINT TEXT + NAV) -->
                    <div class="col-md-10 position-relative mt-1">
                        <div class="hint-overlay mb-3" id="hintTooltip"></div>
                    </div>
                </div>
            </div>
            <div class="bottom-actions d-flex justify-content-between w-100 mt-3">
                <?php if ($prev_exercise): ?>
                    <button class="next-prev-btn btn" onclick="openExercise(<?= $prev_exercise ?>)">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                <?php endif; ?>

                <!-- <?php if ($next_exercise): ?>
                        <button id="nextArrow" class="next-prev-btn btn" onclick="openExercise(<?= $next_exercise ?>)"
                            style="display:none;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    <?php endif; ?> -->
                <?php if ($next_exercise): ?>

                    <!-- NORMAL NEXT BUTTON -->
                    <button id="nextArrow" class="next-prev-btn btn next-right" onclick="openExercise(<?= $next_exercise ?>)"
                        style="display:none;">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                <?php else: ?>

                    <!-- ✅ QUIZ BUTTON -->
                    <button id="quizBtn" class="go-to-quiz-btn btn ms-auto" onclick="openQuiz()" style="display:none;">
                        Go To Quiz
                    </button>

                <?php endif; ?>

            </div>
        </div>
    </div>




    <div class="exercise-actions" style="gap:23px">
        <button class="editor-btn btn"
            onclick="document.querySelector('.editor-wrapper').scrollIntoView({behavior:'smooth'})">
            Go To Editor
        </button>
        <button type="button" class="editor-btn btn" data-bs-toggle="modal" data-bs-target="#assetModal">
            Import Asset
        </button>
    </div>


    <script>


        function openEditor(url) {
            window.open(url, "_blank"); // new tab me open hoga
        }

        function openExercise(id) {
            // showLoader();
            window.location.href = "exercise_view.php?lesson_id=<?= $lesson_id ?>&exercise_id=" + id;
        }
    </script>

    <script>
        window.onload = function () {

            loadAssets('image');

            var iframe = document.querySelector(".editor-wrapper iframe");
            var loaderHidden = false;

            function safeHideLoader() {
                if (!loaderHidden) {
                    hideLoader();
                    loaderHidden = true;
                }
            }

            // ✅ iframe exists
            if (iframe) {

                // iframe onload
                iframe.onload = function () {
                    safeHideLoader();
                };

                // 🔥 fallback: iframe already loaded / event miss
                setTimeout(function () {
                    safeHideLoader();
                }, 2000);

            } else {
                // no iframe
                safeHideLoader();
            }
        };
    </script>


    <script>

        function goBack() {
            window.location.href = "lesson.php?lesson_id=<?= $lesson_id ?>";
        }

        function scrollToInstructions() {
            const element = document.querySelector(".content");
            element.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }

        function scrollToEditor() {
            const element = document.querySelector(".editor-wrapper");
            element.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }

    </script>



    <script>
        let hintIndex = 0;
        const instructions = <?= json_encode($instructions); ?>;
        console.log(instructions);
        function escapeHtml(text) {
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
        }

        function nextHint() {

            if (hintIndex >= instructions.length) {
                return; // ❌ Stop completely
            }

            // 🔊 PLAY SOUND
            const sound = document.getElementById("hintSound");
            sound.currentTime = 0;
            sound.play().catch(() => { });

            document.getElementById("hintArrow").style.display = "inline";

            const tooltip = document.getElementById("hintTooltip");
            const listItems = document.querySelectorAll("#instructionList li");

            tooltip.style.display = "block";

            const data = instructions[hintIndex];

            let html = "";

            if (data.hint_text) {
                html += `
            <pre class="hint-text text-black">${escapeHtml(data.hint_text)}</pre>
        `;
            }

            if (data.hint_image) {
                let images = data.hint_image.split(",");

                images.forEach(img => {
                    html += `
                <div class="hint-img">
                    <img src="uploads/${img.trim()}">
                </div>
            `;
                });
            }

            tooltip.innerHTML = html;

            if (listItems[hintIndex]) {
                listItems[hintIndex].classList.add("active");
            }

            hintIndex++;

            // ✅ If this was last hint
            if (hintIndex === instructions.length) {

                // const nextArrow = document.getElementById("nextArrow");
                // if (nextArrow) {
                //     nextArrow.style.display = "inline-block";
                // }
                const nextArrow = document.getElementById("nextArrow");
                const quizBtn = document.getElementById("quizBtn");

                if (nextArrow) {
                    nextArrow.style.display = "inline-block";
                }

                if (quizBtn) {
                    quizBtn.style.display = "inline-block";
                }

                // 🔒 Disable hint button
                document.querySelector(".hint-btn").style.opacity = "0.5";
                document.querySelector(".hint-btn").style.pointerEvents = "none";
            }
        }

        function openAssetFromInstruction() {
            loadAssets('image');
            const assetModal = new bootstrap.Modal(
                document.getElementById("assetModal"),
                { backdrop: true, focus: true }
            );
            assetModal.show();
        }

    </script>



    <audio id="hintSound" preload="auto">
        <source src="system/sound/hint.mp3" type="audio/mpeg">
    </audio>

    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>
    <div class="modal fade" id="assetModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Import Asset</h5>
                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                        <i class="fas fa-times fs-4"></i>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- TABS -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#imageTab"
                                onclick="loadAssets('image')">
                                Images
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#videoTab"
                                onclick="loadAssets('video')">
                                Videos
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#codeTab"
                                onclick="loadAssets('code')">
                                File
                            </button>
                        </li>
                    </ul>


                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="imageTab"></div>
                        <div class="tab-pane fade" id="videoTab"></div>
                        <div class="tab-pane fade" id="codeTab"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>


</body>

</html>