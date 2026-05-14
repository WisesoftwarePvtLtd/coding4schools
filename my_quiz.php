<?php
session_start();
include 'header.php';
include 'config.php';

$user_id = $_SESSION['LoggedInUserId'] ?? 0;

if ($user_id <= 0) {
    die("Invalid User");
}

/* ===============================
   FETCH USER QUIZ ATTEMPTS
================================ */
$sql = "
    SELECT 
        qa.quiz_attempt_id,
        q.quiz_title,
        qa.started_at,
        qa.completed_at,
        qa.percentage,
        qa.status,
        ue.unlockquizgrade
    FROM quiz_attempt qa
    JOIN quiz q ON q.quiz_id = qa.quiz_id
    JOIN unlock_quiz ue ON ue.quiz_id = q.quiz_id
    WHERE qa.user_id = ?
    AND ue.is_unlocked = 1
    ORDER BY qa.quiz_attempt_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultquiz = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Quiz Attempts</title>



</head>

<body>
    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">
                <div class="container-fluid p-4">

                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder">My Quiz Attempts</h3>
                    </div>


                    <?php if ($resultquiz->num_rows > 0): ?>
                        <?php while ($row = $resultquiz->fetch_assoc()): ?>

                            <?php
                            $percentage = (float) $row['percentage'];
                            $resultText = ($percentage >= 50) ? "Pass" : "Fail";
                            $resultClass = ($percentage >= 50) ? "text-success" : "text-danger";
                            ?>
                             <?php if ($row['unlockquizgrade'] == 1) { ?>
                            <div class="grade-box mb-3">
                                <div class="grade-row d-flex justify-content-between align-items-center">

                                    <!-- LEFT INFO -->
                                    <div>
                                        <strong class="text-primary"><i class="fas fa-book text-primary"></i></strong>

                                        <strong><?= htmlspecialchars($row['quiz_title']) ?></strong>


                                        <div class="exam-card-details">
                                            <strong>Start:</strong>
                                            <?= date("d M Y h:i A", strtotime($row['started_at'])) ?>
                                            <strong>End:</strong>
                                            <?= $row['completed_at']
                                                ? date("d M Y h:i A", strtotime($row['completed_at']))
                                                : '-' ?>
                                            <p class="mb-0">
                                                <strong>Percentage:</strong> <?= $percentage ?>
                                                |
                                                <span class="<?= $resultClass ?> fw-bolder ">
                                                    <?= $resultText ?>
                                                </span>
                                            </p>
                                        </div>



                                    </div>

                                    <!-- RIGHT ICON -->
                                    <div>
                                       
                                        <a class="view-btn" href="my_quiz_result.php?attempt_id=<?= $row['quiz_attempt_id'] ?>"
                                            title="View Result">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    
                                    </div>

                                </div>
                            </div>
                            <?php }?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center">No quiz attempts found.</div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

</body>

</html>