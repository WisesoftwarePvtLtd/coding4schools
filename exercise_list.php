<?php
include 'config.php';
include 'header.php';

/* =========================
   VALIDATE LESSON
========================= */
$lesson_id = intval($_GET['lesson_id'] ?? 0);
if ($lesson_id <= 0) {
    die("Invalid Lesson");
}

/* =========================
   FETCH EXERCISES
========================= */
$q = $conn->prepare("
    SELECT exercise_id, exercise_name
    FROM exercises
    WHERE lesson_id = ?
    ORDER BY exercise_id ASC
");
$q->bind_param("i", $lesson_id);
$q->execute();
$res = $q->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Exercises</title>

<style>
.exercise-card {
    padding:15px;
    margin-bottom:12px;
    background:#ffffff;
    border-radius:8px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    cursor:pointer;
    font-weight:600;
}
.exercise-card:hover {
    background:#e3f2fd;
}
</style>
</head>

<body>
<div class="container-fluid p-4">

<h3 class="fw-bold mb-3">Exercises</h3>

<?php if ($res->num_rows === 0) { ?>
    <p class="text-muted">No exercises available.</p>
<?php } ?>

<?php while ($ex = $res->fetch_assoc()) { ?>
    <div class="exercise-card"
         onclick="window.location.href='exercise_view.php?exercise_id=<?= $ex['exercise_id']; ?>'">
        <?= htmlspecialchars($ex['exercise_name']); ?>
    </div>
<?php } ?>

</div>
</body>
</html>




