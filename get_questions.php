<?php


$connect = new mysqli("localhost", "root", "", "chineseschools");
$connect->set_charset("utf8mb4");

$questions = [];
$qResult = $connect->query("SELECT * FROM questions ORDER BY question_id ASC");

while ($q = $qResult->fetch_assoc()) {
    $qid = $q['question_id'];
    $type = $q['question_type'];
    $correct_answer = $q['correct_answer'];
    $options = [];

    // Fetch options based on question type
    switch ($type) {
        case 'image-select':
        case 'click-select-image':
            $optQuery = "SELECT option_key AS id, label, image, is_correct FROM options WHERE question_id = $qid";
            $optResult = $connect->query($optQuery);
            while ($row = $optResult->fetch_assoc()) {
                // ✅ which option is correct?
                if ($row['is_correct'] == 1) {
                    $correct_answer = $row['id']; // <-- yahi correct answer set karo
                }

                $options[] = [
                    "id" => $row['id'],
                    "label" => $row['label'],
                    "src" => $row['image'] ? $row['image'] : '',
                    "is_correct" => (bool) $row['is_correct']
                ];
            }
            break;

        case 'drag-match-text-to-image':
            $mResult = $connect->query("SELECT item_key, image, correct_word FROM match_items WHERE question_id = $qid");
            $draggableOptions = [];
            while ($row = $mResult->fetch_assoc()) {
                $draggableOptions[] = $row['correct_word'];
                $options[] = [
                    "id" => $row['item_key'],
                    "image" => $row['image'],
                    "correctWord" => $row['correct_word']
                ];
            }
            break;

        case 'order':
            $oResult = $connect->query("SELECT item_text FROM order_items WHERE question_id = $qid ORDER BY item_order ASC");
            while ($row = $oResult->fetch_assoc()) {
                $options[] = $row['item_text'];
            }
            break;

        // case 'match-line':
        //     $mlResult = $connect->query("SELECT left_label, right_label, right_image FROM match_line_pairs WHERE question_id = $qid");
        //     while ($row = $mlResult->fetch_assoc()) {
        //         $options[] = [
        //             "left" => $row['left_label'],
        //             "rightLabel" => $row['right_label'],
        //             "rightImage" => 'uploads/' . $row['right_image']
        //         ];
        //     }
        //     break;
        case 'match-line':
            $leftOptions = [];
            $rightOptions = [];
            $correctPairs = [];

            $mlResult = $connect->query("SELECT left_label, left_image, right_label, right_image FROM match_line_pairs WHERE question_id = $qid");
            while ($row = $mlResult->fetch_assoc()) {

                // LEFT side (Text + Image)
                $leftOptions[] = [
                    "label" => $row['left_label'],
                    "image" => !empty($row['left_image']) ?  $row['left_image'] : null
                ];

                // RIGHT side (Text + Image)
                $rightOptions[] = [
                    "label" => $row['right_label'],
                    "image" => !empty($row['right_image']) ?  $row['right_image'] : null
                ];

                // Correct Pair
                $correctPairs[$row['left_label']] = $row['right_label'];
            }

            break;




        case 'image-drag-drop-to-name':
            $imgMain = $q['main_image'] ? $q['main_image'] : null;
            $dragResult = $connect->query("SELECT drag_id, drag_label, is_correct FROM drag_options WHERE question_id = $qid");
            while ($row = $dragResult->fetch_assoc()) {
                if ($row['is_correct'] == 1) {
                    $correct_answer = $row['drag_id']; // <-- yahi correct answer set karo
                }
                $options[] = [
                    "id" => $row['drag_id'],
                    "label" => $row['drag_label']
                ];
            }
            break;
    }

   if ($type == 'match-line') {
    $questions[] = [
        "id" => $qid,
        "type" => $type,
        "question" => $q['question_text'],
        "correct_answer" => "",
        "main_image" => null,
        "leftOptions" => $leftOptions,
        "rightOptions" => $rightOptions,
        "correctPairs" => $correctPairs
    ];
} else {
    // baki questions ka normal format
    $questions[] = [
        "id" => $qid,
        "type" => $type,
        "question" => $q['question_text'],
        "correct_answer" => $correct_answer,
        "main_image" => $q['main_image'] ? 'uploads/' . $q['main_image'] : null,
        "options" => $options,
    ];
}

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($questions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);




// header("Content-Type: application/json; charset=utf-8");

// $connect = new mysqli("localhost", "root", "", "chineseschool");
// $connect->set_charset("utf8mb4");

// $questions = [];
// $qResult = $connect->query("SELECT * FROM questions ORDER BY id ASC");

// while ($q = $qResult->fetch_assoc()) {
//     $qid = $q['id'];
//     $type = $q['type'];

//     $question = [
//         "type" => $type,
//         "question" => $q['question_text'],
//         "subQuestion" => $q['sub_question'],
//         "answer" => $q['correct_answer']
//     ];

//     switch ($type) {
//         case "image-select":
//         case "click-select-image":
//         case "image-drag-drop-to-name":
//             $options = [];
//             $res = $connect->query("SELECT * FROM options WHERE question_id = $qid");
//             while ($row = $res->fetch_assoc()) {
//                 $options[] = [
//                     "id" => $row['option_id'],
//                     "label" => $row['label'],
//                     "src" => $row['image_src']
//                 ];
//             }
//             $question["options"] = $options;
//             break;

//         case "drag-match-text-to-image":
//             $drags = [];
//             $matches = [];
//             $res = $connect->query("SELECT * FROM drag_match_items WHERE question_id = $qid");
//             while ($row = $res->fetch_assoc()) {
//                 $drags[] = $row['draggable_text'];
//                 $matches[] = [
//                     "id" => $row['match_id'],
//                     "image" => $row['image_src'],
//                     "correctWord" => $row['correct_word']
//                 ];
//             }
//             $question["draggableOptions"] = $drags;
//             $question["matchItems"] = $matches;
//             break;

//         case "match-line":
//             $left = [];
//             $right = [];
//             $pairs = [];
//             $res = $connect->query("SELECT * FROM match_line_pairs WHERE question_id = $qid");
//             while ($row = $res->fetch_assoc()) {
//                 $left[] = ["label" => $row['left_label']];
//                 $right[] = ["label" => $row['right_label'], "image" => $row['right_image']];
//                 $pairs[$row['left_label']] = $row['right_label'];
//             }
//             $question["leftOptions"] = $left;
//             $question["rightOptions"] = $right;
//             $question["correctPairs"] = $pairs;
//             break;

//         case "order":
//             $orderOpts = [];
//             $res = $connect->query("SELECT * FROM order_options WHERE question_id = $qid");
//             while ($row = $res->fetch_assoc()) {
//                 $orderOpts[] = $row['option_label'];
//             }
//             $question["options"] = $orderOpts;
//             break;
//     }

//     $questions[] = $question;
// }

// echo json_encode($questions, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>