<?php
session_start();
include 'config.php';

$user_id = intval($_POST['user_id']);
$exercise_id = intval($_POST['exercise_id'] ?? 0);
$exercise_sort_order = intval($_POST['exercise_sort_order'] ?? 0);
$title = trim($_POST['title']);
$lesson_id = intval($_POST['lesson_id']);
$editor_id = intval($_POST['editor_id'] ?? 0);
$course = $_POST['course'];
$type = $_POST['type'];
$exercise_discription = $_POST['exercise_discription'];
$instruction_guideline = $_POST['instruction_guideline'] ?? null;

$code = json_encode(
    json_decode(urldecode($_POST['answer']), associative: true),
    JSON_UNESCAPED_SLASHES
);

$data = json_decode($code, true);

/* ===============================
   SCRATCH ONLY FILE SAVE
================================ */

if ($type === "Scratch" || $type === "Makey Makey" && isset($data['code']) && $data['code']) {

    if (str_contains($data['code'], 'base64,')) {

        $parts = explode(',', $data['code']);

        if (isset($parts[1])) {

            $binary = base64_decode($parts[1]);

            if (!is_dir("projects")) {
                mkdir("projects", 0777, true);
            }

            $fileName = "projects/project_" . time() . ".sb3";

            file_put_contents($fileName, $binary);

            // ✅ Only Scratch → file path save
            $code = $fileName;
        }
    }
}
function uploadImage($field)
{
    if (!empty($_FILES[$field]['name'])) {
        $name = time() . "_" . basename($_FILES[$field]['name']);
        move_uploaded_file($_FILES[$field]['tmp_name'], "uploads/" . $name);
        return $name;
    }
    return null;
}

$sprite_image = uploadImage('sprite_image');


if ($exercise_id <= 0) {
    // 🔎 Duplicate check (ADD)

    if ($title !== '') {
        $check = $conn->prepare("
    SELECT exercise_id 
    FROM exercises 
    WHERE lesson_id = ? AND exercise_name = ?
");
        $check->bind_param("is", $lesson_id, $title);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            echo json_encode([
                "status" => "duplicate",
                "message" => "Exercise name already exists in this lesson"
            ]);
            exit;
        }
    }

    /* =========================
   DUPLICATE SORT ORDER CHECK (ADD)
========================= */
    $checkSort = $conn->prepare("
    SELECT exercise_id 
    FROM exercises 
    WHERE lesson_id = ? 
      AND exercise_sort_order = ?
    LIMIT 1
");
    $checkSort->bind_param("ii", $lesson_id, $exercise_sort_order);
    $checkSort->execute();
    $checkSort->store_result();

    if ($checkSort->num_rows > 0) {
        echo json_encode([
            "status" => "duplicate_sort_order",
            "message" => "This exercise order is already used in this lesson"
        ]);
        exit;
    }
    $checkSort->close();

    $exercisesql = $conn->prepare(
        "INSERT INTO exercises 
        (lesson_id, editor_id,user_id, exercise_name, exercise_sort_order, exercise_discription, sprite_image, instruction_guideline)
        VALUES (?, ?, ?,?, ?, ?, ?, ?)"
    );

    $exercisesql->bind_param(
        "iiisssss",
        $lesson_id,
        $editor_id,
        $user_id,
        $title,
        $exercise_sort_order,
        $exercise_discription,
        $sprite_image,
        $instruction_guideline
    );

    $exercisesql->execute();
    $exercise_id = $conn->insert_id;

    $exercisesqlcode = $conn->prepare(
        "INSERT INTO course_code
     (user_id,exercise_id,course_name,editor_type,code)
     VALUES (?,?,?,?,?)"
    );
    $exercisesqlcode->bind_param(
        "iisss",
        $user_id,
        $exercise_id,
        $course,
        $type,
        $code
    );
    $exercisesqlcode->execute();


    echo json_encode([
        "status" => "success",
        "exercise_id" => $exercise_id
    ]);
} else {
    /* ======================================================
       UPDATE MODE
    ====================================================== */
    if ($title !== '') {
    // 🔎 Duplicate check (UPDATE)
    $check = $conn->prepare("
    SELECT exercise_id 
    FROM exercises 
    WHERE lesson_id = ? 
    AND exercise_name = ? 
    AND exercise_id != ?
");
    $check->bind_param("isi", $lesson_id, $title, $exercise_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "status" => "duplicate",
            "message" => "Exercise name already exists in this lesson"
        ]);
        exit;
    }
    }

    /* =========================
   DUPLICATE SORT ORDER CHECK (UPDATE)
========================= */
    $checkSort = $conn->prepare("
    SELECT exercise_id 
    FROM exercises 
    WHERE lesson_id = ? 
      AND exercise_sort_order = ?
      AND exercise_id != ?
    LIMIT 1
");
    $checkSort->bind_param("iii", $lesson_id, $exercise_sort_order, $exercise_id);
    $checkSort->execute();
    $checkSort->store_result();

    if ($checkSort->num_rows > 0) {
        echo json_encode([
            "status" => "duplicate_sort_order",
            "message" => "This exercise order is already used in this lesson"
        ]);
        exit;
    }
    $checkSort->close();

    // update exercises
    if ($sprite_image) {

        $exercisesql = $conn->prepare(
            "UPDATE exercises
             SET lesson_id=?, editor_id=?,user_id=?, exercise_name=?, 
                 exercise_sort_order=?, exercise_discription=?, 
                 sprite_image=?, instruction_guideline=?
             WHERE exercise_id=?"
        );

        $exercisesql->bind_param(
            "iiisssssi",
            $lesson_id,
            $editor_id,
            $user_id,
            $title,
            $exercise_sort_order,
            $exercise_discription,
            $sprite_image,
            $instruction_guideline,
            $exercise_id
        );

    } else {

        // 🔥 If no new image → keep old image
        $exercisesql = $conn->prepare(
            "UPDATE exercises
             SET lesson_id=?, editor_id=?,user_id=?, exercise_name=?, 
                 exercise_sort_order=?, exercise_discription=?, instruction_guideline=?

             WHERE exercise_id=?"
        );

        $exercisesql->bind_param(
            "iiissssi",
            $lesson_id,
            $editor_id,
            $user_id,
            $title,
            $exercise_sort_order,
            $exercise_discription,
            $instruction_guideline,
            $exercise_id
        );
    }

    $exercisesql->execute();

    // update course_code
    $exercisesqlcode = $conn->prepare(
        "UPDATE course_code
     SET course_name = ?, editor_type = ?, code = ?
     WHERE exercise_id = ?"
    );
    $exercisesqlcode->bind_param(
        "sssi",
        $course,
        $type,
        $code,
        $exercise_id
    );
    $exercisesqlcode->execute();

    echo json_encode([
        "status" => "success",
        "mode" => "update",
        "exercise_id" => $exercise_id
    ]);
}