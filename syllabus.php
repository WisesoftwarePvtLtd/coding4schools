<?php
session_start();
// include "standard_constants.php";
include "config.php";
include "header.php";
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;


if ($page < 1)
    $page = 1;
if ($lesson_id < 1)
    die("Invalid Lesson ID");

// ==========================
// FETCH LESSON DATA
// ==========================
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
// $pdflesson = "http://localhost/School-language-learning/" . $lesson['lesson_file_path'];
$pdflesson = BASE_URL . $lesson['lesson_file_path'];

// print_r($pdflesson);die;
$course_id = $lesson['course_id'];

// Fetch course name
$sql2 = "SELECT course_title FROM courses WHERE course_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $course_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$course = $result2->fetch_assoc();
$course_name = $course['course_title'];

?>
<!DOCTYPE html>
<html>

<head>
    <title>Full Screen PDF Viewer</title>

</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>
            <div class="main-area text-dark"></div>
            <div id="lesson-overlay" class="lesson-overlay" aria-hidden="true" role="dialog"
                aria-labelledby="lesson-title">
                <div class="lesson-card" role="document">
                    <!-- TOP BAR -->
                    <div class="lesson-topbar">
                        <strong id="lesson-title" class="lesson-title"></strong>
                        
                            <a href="manage-lesson.php?course_id=<?php echo $course_id; ?>&course_name=<?php echo urlencode($course_name); ?>" 
   class="lesson-close-btn" aria-label="Close lesson">✖</a>

                    </div>

                    <!-- PDF / iframe -->
                    <iframe id="lesson-iframe" class="lesson-iframe" src="" title="Lesson content"></iframe>
                </div>
            </div>



        </div>
    </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js";
    </script>

    <script>

        let pdfLesson = "<?php echo $pdflesson; ?>";
        function showLessonPlan() {

            document.getElementById("lesson-iframe").src = pdfLesson + "#toolbar=1";

            document.getElementById("lesson-overlay").style.display = "flex";
            document.body.style.overflow = "hidden"; // Prevents background scrolling
        }

        function closeLessonPopup() {
            document.getElementById("lesson-overlay").style.display = "none";
            document.getElementById("lesson-iframe").src = ""; // Clear source
            document.body.style.overflow = "auto"; // Re-enable scrolling
        }

        window.onload = function () {

            showLessonPlan();
        };

    </script>

</body>

</html>