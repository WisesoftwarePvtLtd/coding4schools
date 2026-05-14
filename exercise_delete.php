<?php
include 'config.php';

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    echo "Invalid exercise ID";
    exit;
}

/* =========================
   1️⃣ DELETE FILES (OPTIONAL BUT RECOMMENDED)
========================= */
$res = $conn->query(
    "SELECT instruction_image, hint_image
     FROM exercise_instruction_hints
     WHERE exercise_id = $id"
);

while ($row = $res->fetch_assoc()) {

    if (!empty($row['instruction_image'])) {
        @unlink(__DIR__ . "/uploads/" . $row['instruction_image']);
    }

    if (!empty($row['hint_image'])) {
        $images = json_decode($row['hint_image'], true);
        if (is_array($images)) {
            foreach ($images as $img) {
                @unlink(__DIR__ . "/uploads/" . $img);
            }
        }
    }
}

/* =========================
   2️⃣ DELETE FROM COMBINED TABLE
========================= */
$conn->query(
    "DELETE FROM exercise_instruction_hints
     WHERE exercise_id = $id"
);

/* =========================
   3️⃣ DELETE EXERCISE
========================= */
$conn->query(
    "DELETE FROM exercises
     WHERE exercise_id = $id"
);

echo "success";
