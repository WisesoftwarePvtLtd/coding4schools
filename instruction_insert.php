<?php
include 'config.php';

function renderInstruction($code){
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

$instruction_id = intval($_POST['instruction_id'] ?? 0);
$exercise_id = intval($_POST['exercise_id'] ?? 0);
$instruction_text = trim($_POST['instruction_text'] ?? '');
$hint_text = trim($_POST['hint_text'] ?? '');
$instruction_sort_order = intval($_POST['instruction_sort_order'] ?? 0);
if (!$instruction_text) {
    echo json_encode(["status" => "error", "message" => "Instruction text required"]);
    exit;
}



/* ---------- IMAGE HANDLING ---------- */
function uploadImage($field)
{
    if (!empty($_FILES[$field]['name'])) {
        $name = time() . "_" . basename($_FILES[$field]['name']);
        move_uploaded_file($_FILES[$field]['tmp_name'], "uploads/" . $name);
        return $name;
    }
    return null;
}

$instruction_image = uploadImage('instruction_image');
$hint_image = uploadImage('hint_image');

/* ---------- UPDATE ---------- */
if ($instruction_id > 0) {

    // $intructionsql = "UPDATE exercise_instruction_hints
    //         SET instruction_text = ?, hint_text = ?";
    // $params = [$instruction_text, $hint_text];
    // $types = "ss";

    /* =========================
   DUPLICATE SORT ORDER CHECK (UPDATE)
========================= */
    $checkSort = $conn->prepare("
    SELECT id 
    FROM exercise_instruction_hints 
    WHERE exercise_id = ? 
      AND instruction_sort_order = ?
      AND id != ?
    LIMIT 1
");
    $checkSort->bind_param("iii", $exercise_id, $instruction_sort_order, $instruction_id);
    $checkSort->execute();
    $checkSort->store_result();

    if ($checkSort->num_rows > 0) {
        echo json_encode([
            "status" => "duplicate_sort_order",
            "message" => "This instruction order already exists"
        ]);
        exit;
    }
    $checkSort->close();

    $intructionsql = "UPDATE exercise_instruction_hints
        SET instruction_text = ?, hint_text = ?, instruction_sort_order = ?";

    $params = [$instruction_text, $hint_text, $instruction_sort_order];
    $types = "ssi";

    if ($instruction_image) {
        $intructionsql .= ", instruction_image = ?";
        $params[] = $instruction_image;
        $types .= "s";
    }

    if ($hint_image) {
        $intructionsql .= ", hint_image = ?";
        $params[] = $hint_image;
        $types .= "s";
    }



    $intructionsql .= " WHERE id = ?";
    $params[] = $instruction_id;
    $types .= "i";

    $stmt = $conn->prepare($intructionsql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $id = $instruction_id;
}

/* ---------- INSERT ---------- */ else {

    /* =========================
       DUPLICATE SORT ORDER CHECK (ADD)
    ========================= */
    $checkSort = $conn->prepare("
    SELECT id 
    FROM exercise_instruction_hints 
    WHERE exercise_id = ? 
      AND instruction_sort_order = ?
    LIMIT 1
");
    $checkSort->bind_param("ii", $exercise_id, $instruction_sort_order);
    $checkSort->execute();
    $checkSort->store_result();

    if ($checkSort->num_rows > 0) {
        echo json_encode([
            "status" => "duplicate_sort_order",
            "message" => "This instruction order already exists"
        ]);
        exit;
    }
    $checkSort->close();

    $stmt = $conn->prepare("
    INSERT INTO exercise_instruction_hints
    (exercise_id, instruction_text, instruction_image, hint_text, hint_image, instruction_sort_order)
    VALUES (?, ?, ?, ?, ?, ?)
");

    $stmt->bind_param(
        "issssi",
        $exercise_id,
        $instruction_text,
        $instruction_image,
        $hint_text,
        $hint_image,
        $instruction_sort_order
    );
    $stmt->execute();

    $id = $stmt->insert_id;
}

/* ---------- RESPONSE ---------- */
echo json_encode([
    "status" => "success",
    "instruction" => [
        "id" => $id,
        "instruction_text" => renderInstruction($instruction_text),
        "instruction_image" => $instruction_image,
        "hint_text" => $hint_text,
        "hint_image" => $hint_image,
        "instruction_sort_order" => $instruction_sort_order
    ]
]);
