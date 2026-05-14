<?php
include 'config.php';

$lesson_id = intval($_POST['lesson_id'] ?? 0);
$sql = "
    SELECT exercise_id, exercise_name
    FROM exercises
    WHERE lesson_id = ?
";

$params = [$lesson_id];
$types  = "i";

if ($search !== '') {
    $sql .= " AND exercise_name LIKE ?";
    $params[] = "%$search%";
    $types   .= "s";
}

$sql .= " ORDER BY exercise_id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='text-muted'>No exercises found</div>";
    exit;
}

/* =========================
   OUTPUT HTML
while ($row = $result->fetch_assoc()) {
    $id   = $row['exercise_id'];
    $name = htmlspecialchars($row['exercise_name']);
    ?>
    
    <div class="exercise-row">
        <div class="exercise-title">
            <?= $name ?>
        </div>

        <div class="exercise-actions">
            <i class="fas fa-list manage"
               title="Manage Instructions"
               onclick="window.location.href='manage_instruction.php?exercise_id=<?= $id ?>'"></i>

            <i class="fas fa-edit edit"
               title="Edit Exercise"
               onclick="window.location.href='edit_exercise.php?exercise_id=<?= $id ?>'"></i>

            <i class="fas fa-trash delete"
               title="Delete Exercise"
               onclick="deleteExercise(<?= $id ?>)"></i>
        </div>
    </div>

<?php
}
$stmt->close();
$name = trim($_POST['name'] ?? '');

$sql = "
SELECT exercise_id, exercise_name
FROM exercises
WHERE lesson_id = ?
AND exercise_name LIKE ?
ORDER BY exercise_id DESC
";

$stmt = $conn->prepare($sql);
$like = "%$name%";
$stmt->bind_param("is", $lesson_id, $like);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "<div class='text-muted'>No exercises found.</div>";
    exit;
}

while ($row = $res->fetch_assoc()) {
?>
<div class="exercise-row">
    <div class="exercise-title">
        <?= htmlspecialchars($row['exercise_name']) ?>
    </div>

    <div class="exercise-actions">

        <i class="fas fa-list manage"
           title="Manage Instructions"
           onclick="window.location.href=
           'manage_instruction.php?exercise_id=<?= $row['exercise_id'] ?>'">
        </i>

        <i class="fas fa-edit edit"
           title="Edit Exercise"
           onclick="window.location.href=
           'edit_exercise.php?exercise_id=<?= $row['exercise_id'] ?>'">
        </i>

        <i class="fas fa-trash delete"
           title="Delete Exercise"
           onclick="deleteExercise(<?= $row['exercise_id'] ?>)">
        </i>

    </div>
</div>
<?php } ?>
