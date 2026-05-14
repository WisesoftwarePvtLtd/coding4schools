<?php include 'header.php';
include 'config.php';
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
$lesson = "SELECT * FROM lessons WHERE lesson_id = $lesson_id";
$lessonresult = $conn->query($lesson);
$lessondata = $lessonresult->fetch_assoc();

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;

$practiceQuery = $conn->query("
    SELECT lp.lesson_practice_id, q.question_id, q.question_type
    FROM lesson_practices lp
    JOIN questions q ON lp.question_id = q.question_id
    WHERE lp.lesson_id = $lesson_id
    ORDER BY lp.lesson_practice_id ASC
");

$practices = [];
while ($row = $practiceQuery->fetch_assoc()) {
    $practices[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snacks Practices </title>

</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>
            <div class="main-area text-dark">

                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>

                <div class="container-fluid p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bolder"><?php echo $lessondata['lesson_title']; ?> Practices</h3>
                        <a href="lesson.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Back To Lesson
                        </a>
                    </div>
                    <div class="main-content">
                        <div class="circles-container">
                            <?php foreach ($practices as $index => $practice):
                                $btnNumber = $index + 1;
                                $type = $practice['question_type'];
                                // Choose icon based on question type
                                switch ($type) {
                                    case 'image-select':
                                        $icon = 'fas fa-hand-pointer';
                                        break;
                                    case 'match-line':
                                        $icon = 'fas fa-equals';
                                        break;
                                    case 'drag-match-text-to-image':
                                        $icon = 'fas fa-hand-rock';
                                        break;
                                    case 'order':
                                        $icon = 'fas fa-arrows-alt-h';
                                        break;
                                    default:
                                        $icon = 'fas fa-question';
                                }
                                ?>
                                <a href="lesson_practices.php?question_id=<?php echo $practice['question_id']; ?>&lesson_id=<?php echo $lesson_id; ?>" class="circle-button">
                                    <span><?php echo $btnNumber; ?></span>
                                    <i class="<?php echo $icon; ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>