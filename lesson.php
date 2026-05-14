<?php
session_start();
include "config.php";
include "header.php";
//include 'standard_constants.php';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

$problemQ = $conn->prepare("
    SELECT *
    FROM problem
    WHERE lesson_id = ?
    ORDER BY problem_id ASC
");
$problemQ->bind_param("i", $lesson_id);
$problemQ->execute();

$problemResult = $problemQ->get_result(); // ✅ ADD THIS

$problems = [];


if ($page < 1)
    $page = 1;
if ($lesson_id < 1)
    die("Invalid Lesson ID");

// ==========================
// FETCH LESSON DATA
// ==========================
$sql = "";
$sql = "SELECT * FROM lessons WHERE lesson_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Lesson not found!");
}

$lesson = $result->fetch_assoc();
// print_r($lesson);die;
// Dynamic File Paths
$pdfSummary = BASE_URL . $lesson['lesson_summary_path'];
$pdflesson = BASE_URL . $lesson['lesson_file_path'];
$pdfLessonplan = BASE_URL . $lesson['lesson_plan_path'];
$pdfLessonVideo = BASE_URL . $lesson['lesson_video_path'];

$lesson_type = $lesson['lesson_type'];

$course_id = $lesson['course_id'];
$lesson_guideline = trim($lesson['lesson_guideline'] ?? '');
$lesson_guideline_image = $lesson['lesson_guideline_image'] ?? '';


// Fetch course name
$sql2 = "SELECT course_title FROM courses WHERE course_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $course_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$course = $result2->fetch_assoc();
$course_name = $course['course_title'];
// ==========================
// FETCH AUDIO FILES FROM TABLE
// ==========================
$audio_sql = "SELECT * FROM lesson_audio WHERE lesson_id = ? ORDER BY lesson_audio_id  ASC";
$audio_stmt = $conn->prepare($audio_sql);
$audio_stmt->bind_param("i", $lesson_id);
$audio_stmt->execute();
$audio_result = $audio_stmt->get_result();

$hasLesson = !empty($lesson['lesson_file_path']);
$hasLessonPlan = !empty($lesson['lesson_plan_path']);
$hasPresentation = !empty($lesson['lesson_summary_path']);
//$hasWorkcourse = !empty($lesson['lesson_video_path']);
$hasAudio = ($audio_result->num_rows > 0);

$practiceCount = 0;

$ps = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM lesson_practices 
    WHERE lesson_id = ?
");
$ps->bind_param("i", $lesson_id);
$ps->execute();
$pr = $ps->get_result()->fetch_assoc();

$practiceCount = (int) $pr['total'];

$quizCount = 0;

$qs = $conn->prepare("
    SELECT COUNT(*) AS total 
    FROM quiz 
    WHERE lesson_id = ?
");
$qs->bind_param("i", $lesson_id);
$qs->execute();
$qr = $qs->get_result()->fetch_assoc();

$quizCount = (int) ($qr['total'] ?? 0);
?>

<?php
// check if exercises exist for this lesson
$exerciseQ = $conn->prepare(" SELECT exercise_id, exercise_name, exercise_sort_order  FROM exercises  WHERE lesson_id = ? ORDER BY exercise_sort_order ASC");
$exerciseQ->bind_param("i", $lesson_id);
$exerciseQ->execute();
$exerciseResult = $exerciseQ->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Final Working PDF UI</title>
    <style>
        .lesson-guideline-box {
            margin-left: 14%;
        }

        .lesson-guideline-text {
            font-size: 18px;
        }

        .view-pdf-btn {
            margin-left: 10px;
            padding: 6px 12px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .view-pdf-btn:hover {
            background: #27ae60;
        }
    </style>
</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">
            <!-- DUSTY HOURGLASS LOADER -->
            <div id="page-loader" class="loader-overlay">
                <div class="hourglass"></div>
            </div>
            <!-- Sidebar -->

            <div class="main-area text-dark">
                <div class="text-right">
                    <h3 class="fw-bolder text-center">
                        <?= "Lesson " . htmlspecialchars($lesson['lesson_sort_order']) . " | " . htmlspecialchars($lesson['lesson_title']); ?>
                    </h3>
                    <a href="manage-lesson.php?course_id=<?php echo $course_id; ?>&course_name=<?php echo urlencode($course_name); ?>"
                        class="btn btn-primary" style="width: 7%;  margin-top: -95px;"><i class="fas fa-arrow-left"></i>
                        Back</a>
                    <?php if (!empty($lesson['editor_url'])): ?>
                        <button class="btn btn-primary"
                            onclick="openEditor('<?= htmlspecialchars($lesson['editor_url']); ?>')"
                            style="width: 8%; margin-top: -95px;">
                            Open Editor
                        </button>
                    <?php endif; ?>
                </div>

                <!-- VIDEO HOLDER (TOP) -->
                <div id="video-wrapper" style="display:none;text-align: center;">
                    <video id="lesson-video" controls preload="metadata"
                        style="width:65%; max-height:450px; background:#000; border-radius:12px; object-fit:contain;">
                    </video>
                </div>

                <!-- ============================= -->
                <!--   PRESENTATION UI (Default)   -->
                <!-- ============================= -->
                <div id="presentation-wrapper">

                    <!-- LEFT thumbnail -->
                    <div class="thumb-box">
                        <div>Previous</div>
                        <canvas id="thumb-prev"></canvas>
                    </div>

                    <!-- MAIN CANVAS -->
                    <div id="presentation-main">
                        <canvas id="main-canvas"></canvas>

                        <div class="nav-buttons">
                            <a id="btn-prev"></a>
                            <span id="page-info" style="margin-right: 20px;"></span>
                            <button type="button" class="btn btn-primary" onclick="openCurrentPDF()">
                                <i class="fas fa-expand" style="margin-right: 4px;"></i> Full Screen
                            </button>
                            <a id="btn-next"></a>
                        </div>
                    </div>

                    <!-- RIGHT thumbnail -->
                    <div class="thumb-box">
                        <div>Next</div>
                        <canvas id="thumb-next"></canvas>
                    </div>

                </div>

                <!-- <?php if (!empty($lesson_guideline)): ?>
                    <div class="lesson-guideline-box">
                       
                        <div class="lesson-guideline-text">
                            <?= nl2br(htmlspecialchars($lesson_guideline)) ?>
                        </div>
                    </div>
                <?php endif; ?> -->
                <?php if (!empty($lesson_guideline) || !empty($lesson_guideline_image)): ?>
                    <div class="lesson-guideline-box">

                        <?php if (!empty($lesson_guideline)): ?>
                            <div class="lesson-guideline-text">
                                <?= nl2br(htmlspecialchars($lesson_guideline)) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($lesson_guideline_image)): ?>
                            <div class="lesson-guideline-image ">
                                <img src="<?= htmlspecialchars($lesson_guideline_image) ?>" alt="Lesson Guideline"
                                    style="max-width:100%; margin-bottom:10px;">
                            </div>
                        <?php endif; ?>

                    </div>
                <?php endif; ?>

                <!-- ============================= -->
                <!--          CIRCLE MENU          -->
                <!-- ============================= -->
                <?php
                $hasExercises = ($exerciseResult->num_rows > 0);


                if ($hasExercises || $quizCount > 0):
                    ?>
                    <h3 class="fw-bolder mt-4" style="margin-left: 5%;">Exercises And Quiz </h3>
                <?php endif; ?>
                <div class="menu-bar">

                    <?php if ($hasLesson): ?>
                        <div class="circle" id="circle-lesson" onclick="setActiveCircle(this); showLesson()"
                            style="background-color: #84a105;">
                            Lesson <br>
                            <i class="fas fa-file-alt"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasLessonPlan): ?>
                        <div class="circle" onclick="setActiveCircle(this); showLessonPlan()"
                            style="background-color: #ae0ea1;">
                            Lesson <br>Plan <br>
                            <i class="fas fa-file-alt"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasPresentation): ?>
                        <div class="circle" onclick="setActiveCircle(this); showPresentation()"
                            style="background-color: #02615f;">
                            Lesson Summary <br>
                            <i class="fas fa-desktop"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($hasAudio): ?>
                        <div class="circle" onclick="setActiveCircle(this); showAudio()">
                            Audio
                            <i class="fas fa-volume-up"></i>
                        </div>
                    <?php endif; ?>



                    <?php if ($exerciseResult->num_rows > 0): ?>
                        <?php $exNo = 1; ?>
                        <?php while ($ex = $exerciseResult->fetch_assoc()): ?>
                            <div class="circle" onclick="setActiveCircle(this); showExercise(<?php echo $ex['exercise_id']; ?>)"
                                style="background-color: #057bc0;">
                                <?php echo $exNo; ?><br>
                                <i class="fas fa-puzzle-piece"></i>
                            </div>
                            <?php $exNo++; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <?php if ($problemResult->num_rows > 0): ?>
                        <?php $pNo = 1; ?>
                        <?php while ($p = $problemResult->fetch_assoc()): ?>

                            <div class="circle" onclick="setActiveCircle(this); showProblem(<?php echo $p['problem_id']; ?>)"
                                style="background-color: #0bae72;">
                                Problem <?php echo $pNo; ?><br>
                                <i class="fas fa-bug"></i>
                            </div>

                            <?php $pNo++; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>





                    <!-- Practice + Quiz always visible -->
                    <?php if ($lesson_type !== 'culturalActivity'): ?>

                        <?php if ($practiceCount > 0): ?>
                            <div class="circle" onclick="setActiveCircle(this); showPractice()"
                                style="background-color: #05a19f;">
                                Practice
                                <i class="fas fa-pencil-alt"></i>
                            </div>
                        <?php endif; ?>

                        <?php if ($quizCount > 0): ?>
                            <div class="circle" onclick="setActiveCircle(this); showQuiz()" style="background-color: #cc6b09;">
                                Quiz<br>
                                <i class="fas fa-question-circle"></i>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>


                </div>
                <!-- ============================= -->
                <!--   CONTENT BELOW MENU          -->
                <!-- ============================= -->
                <div id="dynamic-content">Select an option above</div>

                <!-- HTML -->
                <div id="lesson-overlay" class="lesson-overlay" aria-hidden="true" role="dialog"
                    aria-labelledby="lesson-title">
                    <div class="lesson-card" role="document">
                        <!-- TOP BAR -->
                        <div class="lesson-topbar">
                            <strong id="lesson-title" class="lesson-title"></strong>
                            <button class="lesson-close-btn" onclick="closeLessonPopup()"
                                aria-label="Close lesson">✖</button>
                        </div>

                        <!-- PDF / iframe -->
                        <iframe id="lesson-iframe" class="lesson-iframe" src="" title="Lesson content"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $audioHTML = "";

    if ($audio_result->num_rows > 0) {

        $counter = 1;

        while ($a = $audio_result->fetch_assoc()) {

            $filePath = $a['audio_file_path'];
            $fileNo = str_pad($counter, 2, "0", STR_PAD_LEFT);

            $audioHTML .= '
            <div class="audio-block">
                <div class="audio-label">' . $fileNo . '</div>
                <div class="audio-item">
                    <audio controls src="' . $filePath . '"></audio>
                </div>
            </div>
        ';

            $counter++;
        }
    } else {
        $audioHTML = "<p style='text-align:center; color:red; width:100%;'>No audio uploaded.</p>";
    }

    $audioHTML_safe = json_encode($audioHTML);
    ?>

    <!-- PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js";
        function openEditor(url) {

            showLoader(); // 🔥 loader ON

            const newTab = window.open(url, "_blank");

            // ❗ fallback (max wait 3 sec)
            setTimeout(() => {
                hideLoader();
            }, 3000);

        }

    </script>


    <script>
        /* =====================================================
           GLOBAL STATE
        ===================================================== */
        let currentPage = <?php echo (int) $page; ?>;
        let pdflesson = <?php echo json_encode($pdflesson ?? ""); ?>;
        let pdfLessonplan = <?php echo json_encode($pdfLessonplan ?? ""); ?>;
        let pdfpresentation = <?php echo json_encode($pdfSummary ?? ""); ?>;
        let pdfLessonVideo = <?php echo json_encode($pdfLessonVideo ?? ""); ?>;
        let audioHTML = <?php echo json_encode($audioHTML ?? ""); ?>;

        let problemPDFs = <?php echo json_encode($problems); ?>;

        let pdfDoc = null;
        let audioVisible = false;
        let currentPDF = "";

        /* =====================================================
           UTILS
        ===================================================== */
        function $(id) {
            return document.getElementById(id);
        }

        function setActiveCircle(el) {
            document.querySelectorAll(".circle").forEach(c => c.classList.remove("active"));
            if (el) el.classList.add("active");
        }

        /* =====================================================
           PDF RENDERING
        ===================================================== */
        function renderPage(pageNum) {
            if (!pdfDoc) return;

            if (pageNum < 1) pageNum = 1;
            if (pageNum > pdfDoc.numPages) pageNum = pdfDoc.numPages;

            currentPage = pageNum;
            $("page-info").innerText = `Page ${currentPage} / ${pdfDoc.numPages}`;

            pdfDoc.getPage(currentPage).then(page => {
                drawPage(page, "main-canvas", 1.3);
            });

            if (currentPage > 1) {
                pdfDoc.getPage(currentPage - 1).then(p => drawPage(p, "thumb-prev", 0.3));
            } else {
                clearCanvas("thumb-prev");
            }

            if (currentPage < pdfDoc.numPages) {
                pdfDoc.getPage(currentPage + 1).then(p => drawPage(p, "thumb-next", 0.3));
            } else {
                clearCanvas("thumb-next");
            }
        }

        function drawPage(page, canvasId, scale) {
            const canvas = $(canvasId);
            if (!canvas) return;

            const ctx = canvas.getContext("2d");
            const viewport = page.getViewport({ scale });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            page.render({ canvasContext: ctx, viewport });
        }

        function clearCanvas(id) {
            const c = $(id);
            if (!c) return;
            c.getContext("2d").clearRect(0, 0, c.width, c.height);
        }

        function loadPDF(url) {
            showLoader();
            $("presentation-wrapper").style.display = "flex";
            if (!url || !url.endsWith(".pdf")) {
                $("dynamic-content").innerHTML =
                    "<p style='color:red;text-align:center'>PDF not available</p>";
                hideLoader();
                return;
            }

            $("presentation-wrapper").style.display = "flex";
            $("dynamic-content").innerHTML = "";

            pdfjsLib.getDocument(url).promise
                .then(pdf => {
                    pdfDoc = pdf;
                    renderPage(1);
                    hideLoader();
                })
                .catch(err => {
                    console.error("PDF load error:", err);
                    $("dynamic-content").innerHTML =
                        "<p style='color:red;text-align:center'>Failed to load PDF</p>";
                    hideLoader();
                });
        }

        /* =====================================================
           MENU ACTIONS
        ===================================================== */


        function showLesson() {
            showLoader();
            // sab kuch hide se start karo
            $("presentation-wrapper").style.display = "none";
            $("dynamic-content").innerHTML = "";

            // thumbnails hide
            document.querySelectorAll(".thumb-box").forEach(el => {
                el.style.display = "none";
            });

            /* ==========================
               VIDEO HANDLE
            ========================== */
            const videoWrapper = $("video-wrapper");
            const videoEl = $("lesson-video");

            let hasVideo = false;

            if (pdfLessonVideo) {
                const vExt = pdfLessonVideo.split('.').pop().toLowerCase();
                if (["mp4", "webm", "ogg", "m4v"].includes(vExt)) {
                    videoWrapper.style.display = "block";
                    videoEl.src = pdfLessonVideo;
                    videoEl.onloadeddata = function () {
                        hideLoader();
                    };
                    hasVideo = true;
                }
            }

            if (!hasVideo) {
                videoWrapper.style.display = "none";
                videoEl.src = "";
            }

            /* ==========================
               PDF HANDLE
            ========================== */
            if (pdflesson && pdflesson.endsWith(".pdf")) {
                currentPDF = pdflesson;

                // abhi hi PDF UI dikhao
                $("presentation-wrapper").style.display = "flex";

                // thumbnails show
                document.querySelectorAll(".thumb-box").forEach(el => {
                    el.style.display = "block";
                });

                // presentation main reset
                $("presentation-main").innerHTML = `
            <canvas id="main-canvas"></canvas>
            <div class="nav-buttons">
                <a id="btn-prev"></a>
                <span id="page-info" style="margin-right: 20px;"></span>
                <button type="button" class="btn btn-primary" onclick="openCurrentPDF()">
    <i class="fas fa-expand" style="margin-right: 4px;"></i>   Full Screen
</button>
                <a id="btn-next"></a>
            </div>
        `;

                loadPDF(pdflesson);
            }

            // 🔥 FINAL SAFETY
            if (!hasVideo && (!pdflesson || !pdflesson.endsWith(".pdf"))) {
                hideLoader();
            }

            // ❌ AGAR NA VIDEO HAI NA PDF → KUCH BHI NA DIKHAO
        }


        function showPresentation() {
            showLoader();
            currentPDF = pdfpresentation;
            if (!pdfpresentation || !pdfpresentation.endsWith(".pdf")) {
                $("dynamic-content").innerHTML =
                    "<p style='color:red;text-align:center'>No Presentation Available</p>";
                hideLoader(); // 🔥 IMPORTANT
                return;
            }
            // ✅ thumbnails wapas lao
            document.querySelectorAll(".thumb-box").forEach(el => {
                el.style.display = "block";
            });

            // ❌ video hide
            $("video-wrapper").style.display = "none";
            $("lesson-video").src = "";

            // ✅ presentation main reset (IMPORTANT)
            $("presentation-main").innerHTML = `
                <canvas id="main-canvas"></canvas>
                <div class="nav-buttons">
                    <a id="btn-prev"></a>
                    <span id="page-info" style="margin-right: 20px;"></span>
                    <button type="button" class="btn btn-primary" onclick="openCurrentPDF()">
    <i class="fas fa-expand" style="margin-right: 4px;"></i>   Full Screen
</button>
                    <a id="btn-next"></a>
                </div>
            `;

            // ❌ dynamic content clear
            $("dynamic-content").innerHTML = "";

            // ✅ load summary pdf
            loadPDF(pdfpresentation);
        }


        function showProblem(problemId) {

            window.location.href =
                "problem_assignment.php?problem_id=" + problemId;

        }

        function showLessonPlan() {
            showLoader();
            // Set PDF in iframe
            if (!pdfLessonplan) {

                hideLoader(); // 🔥 IMPORTANT
                return;
            }
            currentPDF = pdfLessonplan;
            document.getElementById("lesson-iframe").src = pdfLessonplan + "#toolbar=1";

            // Show popup with black overlay
            document.getElementById("lesson-overlay").style.display = "flex";
            document.body.style.overflow = "hidden";
            document.getElementById("lesson-iframe").onload = function () {
                hideLoader();
            };
        }


        function closeLessonPopup() {
            $("lesson-overlay").style.display = "none";
            $("lesson-iframe").src = "";
            document.body.style.overflow = "auto";
        }

        function showAudio() {
            showLoader();
            const container = $("dynamic-content");

            if (audioVisible) {
                container.innerHTML = "";
                audioVisible = false;
                return;
            }

            audioVisible = true;
            container.innerHTML = `
        <h2 style="text-align:center;margin-bottom:20px">Audio Files</h2>
        <div class="audio-grid">${audioHTML}</div>
    `;
            hideLoader();
        }

        function showPractice() {
            window.location.href = "lesson_practices.php?lesson_id=<?php echo $lesson_id; ?>";
        }

        function showQuiz() {
            window.location.href =
                "manage_quiz.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson_id; ?>";
        }


        function showExercise(exerciseId) {
            window.location.href =
                "exercise_view.php?lesson_id=<?= $lesson_id ?>&exercise_id=" + exerciseId;
        }



        /* =====================================================
        EVENTS
        ===================================================== */
        window.onload = function () {
            hideLoader();
            // hide overlay
            if ($("lesson-overlay")) {
                $("lesson-overlay").style.display = "none";
                $("lesson-iframe").src = "";
            }

            // thumbnails navigation (NO addEventListener)
            if ($("thumb-prev")) {
                $("thumb-prev").onclick = function () {
                    renderPage(currentPage - 1);
                };
            }

            if ($("thumb-next")) {
                $("thumb-next").onclick = function () {
                    renderPage(currentPage + 1);
                };
            }

            // auto-load lesson
            if (pdflesson) {
                showLesson();
                if ($("circle-lesson")) {
                    setActiveCircle($("circle-lesson"));
                }
            }

            // ESC key close popup
            document.onkeydown = function (e) {
                e = e || window.event;
                if (e.key === "Escape") {
                    closeLessonPopup();
                }
            };
        };


        function openCurrentPDF() {
            if (!currentPDF) {
                alert("PDF not available");
                return;
            }

            window.open(currentPDF, "_blank");
        }
    </script>
</body>

</html>