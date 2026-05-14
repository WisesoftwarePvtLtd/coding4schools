<?php
session_start();
include "config.php";
include "header.php";

$problem_id = intval($_GET['problem_id'] ?? 0);
$problem_id = intval($_GET['problem_id'] ?? 0);


if ($problem_id <= 0) {
    die("Invalid Problem");
}

$sql = "SELECT * FROM problem WHERE problem_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $problem_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("Problem not found");
}

$problem = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Assignment</title>

    <style>
        .assignment-card {
            background: #f5f5f5;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .top-buttons {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .btn-blue {
            background: #2f7be6;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 20px;
        }

        .btn-green {
            background: #22b573;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 20px;
        }
    </style>

</head>

<body>

    <div class="container-fluid" style="padding:30px">

        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include "menus.php"; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area">
                <div id="page-loader" class="loader-overlay">
                    <div class="hourglass"></div>
                </div>
                <div id="globalMsg" class="global-msg"></div>
                <div class="container-fluid p-4">

                    <div class="d-flex justify-content-end align-items-center" style="gap: 9px;">
                        
                        <a href="lesson.php?lesson_id=<?= $problem['lesson_id']; ?>" class="btn btn-primary">Back To
                            Lesson</a>

                            <button class="btn btn-primary" onclick="openEditor('<?= $problem['editor_url']; ?>')">
                            Open Editor
                        </button>
                    </div>

                    <div class="assignment-card">

                        <h4 class="text-center">ASSIGNMENT</h4>

                        <h5 class="text-center mb-3">
                            <?php echo htmlspecialchars($problem['problem_name']); ?>
                        </h5>

                        <div style="font-size:16px;line-height:1.7">

                            <?php echo nl2br(htmlspecialchars($problem['problem_text'])); ?>

                        </div>

                        <?php if (!empty($problem['problem_image'])): ?>

                            <div style="margin-top:20px">

                                <img src="uploads/<?php echo $problem['problem_image']; ?>"
                                    style="width:300px;border-radius:8px">

                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            </div>

        </div>

    </div>
    <script>
        function hideLoader() {
            document.getElementById("page-loader").style.display = "none";
        }

        function showLoader() {
            document.getElementById("page-loader").style.display = "flex";
        }

        // 🔥 PAGE LOAD COMPLETE
        window.addEventListener("load", function () {
            hideLoader();
        });
    </script>

    <script>

        function openEditor(url) {

            showLoader(); // 🔥 loader ON

            const newTab = window.open(url, "_blank");

            // ❗ fallback (max wait 3 sec)
            setTimeout(() => {
                hideLoader();
            }, 3000);

        }

    </script>



</body>

</html>