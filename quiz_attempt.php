<!DOCTYPE html>
<html lang="en">
<?php
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
?>
<?php
include "config.php";

$quiz_title = "";

if ($quiz_id > 0) {
  $result = $conn->query("SELECT quiz_title FROM quiz WHERE quiz_id = $quiz_id");
  if ($row = $result->fetch_assoc()) {
    $quiz_title = $row['quiz_title'];
  }
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Advanced Combined Chinese Quiz</title>
  <style>
    /* --- Quiz Card Container --- */
    .quiz-card {
      width: 100%;
      max-width: 750px;
      min-height: 300px;
      /* overflow: hidden; */
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      padding: 30px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: absolute;
      top: 80px;
      left: 50%;
      transform: translate(-50%);
      background-size: cover;
      background-position: center;
      transition: background 0.5s;
    }

    /* --- Question Text Box --- */
    #questionText {
      /* background: rgba(255, 255, 255, 0.85);
      border-radius: 10px; */
      padding: 15px 20px;
      color: #333;

      font-size: 1.1em;
      /* margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      text-align: left; */
    }

    #questionText pre {
      white-space: pre-wrap;
      /* wrap karega */
      word-break: break-word;
      /* long words bhi break honge */
      margin: 0;
      font-family: inherit;
    }

    .main-question {
      font-size: 22px;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      color: #222;
    }

    .sub-question {
      font-weight: normal;
      margin-top: 5px;
      color: #555;
    }

    /* --- Answer Area Container --- */
    .answers {
      flex-grow: 1;
      /* overflow-y: auto; */
      /* margin-bottom: 30px; */
      padding: 20px;
      /* text-align: center;
      position: relative;
      background: rgba(255, 255, 255, 0.85);
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
    }

    /* --- Submit Button --- */
    #submitBtn {
      padding: 15px 30px;
      border: none;
      background: #fd5f00;
      color: white;
      border-radius: 15px;
      cursor: pointer;
      font-weight: bold;
      font-size: 1.2em;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      width: 46%;
    }

    .green-submit-btn {
      background: #90c883 !important;
      color: #5d4037 !important;
    }

    #submitBtn:hover {
      opacity: 0.9;
    }

    /* --- Feedback Message Area --- */
    #feedbackMessage {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }

    #feedbackMessage a {
      cursor: pointer;
      text-decoration: underline;
      margin-left: 5px;
    }

    /* ---------------------------------- DRAG/DROP & LINE MATCH STYLES (COMMON) ---------------------------------- */
    .drag-item {
      display: inline-block;
      padding: 12px 18px;
      margin: 5px;
      background: #f77f7f;
      color: white;
      border-radius: 10px;
      cursor: grab;
      font-size: 1.1em;
      font-weight: bold;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
      transition: background 0.5s ease;
    }

    .options-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .drop-box {
      width: 20%;
      height: 60px;
      border: 2px dashed #ccc;
      border-radius: 8px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
      flex-shrink: 0;
    }

    .drop-box>.drag-item {
      margin: 0;
      box-shadow: none;
    }

    /* ---------------------------------- Q6: IMAGE IN QUESTION BOX LAYOUT ---------------------------------- */
    .question-image-layout {
      display: flex;

      gap: 15px;
      padding: 5px;
    }

    .question-image-area {
      flex-shrink: 0;
      width: 100%;
      height: auto;
      /* border-radius: 8px; */
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin-top: 23px;
    }

    .question-image-area img {
      max-width: 100%;
      height: auto;
      /* object-fit: cover; */
    }

    .question-text-content {
      flex-grow: 1;
      text-align: left;
    }

    .drop-box-for-image-name {
      /* Styles for the large drop box in Q6 */
      width: 250px;
      height: 65px;
      border: 3px dashed #b2b2b2;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.7);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
    }

    .drop-box-for-image-name>.drag-item {
      margin: 0;
      box-shadow: none;
    }

    /* ---------------------------------- Q1, Q3, Q2, Q4, Q5 STYLES (UNCHANGED) ---------------------------------- */

    /* ... (Q1 styles: .image-grid, .single-placeholder, .feedback-overlay) ... */
    .image-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      justify-items: center;
      margin-bottom: 30px;
    }

    .image-option {
      background: #f7f7f7;
      border-radius: 10px;
      padding: 10px;
      cursor: grab;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 142px;
      height: 105px;
    }

    .image-option img {
      width: 80px;
      height: 80px;
      object-fit: contain;
      pointer-events: none;
    }

    .single-placeholder {
      width: 180px;
      height: 120px;
      border: 3px dashed #b2b2b2;
      border-radius: 10px;
      margin: 20px auto 25px auto;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: rgba(255, 255, 255, 0.5);
      position: relative;
    }

    .feedback-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: none;
      align-items: center;
      justify-content: center;
      background: rgba(255, 0, 0, 0.2);
      border-radius: 10px;
      pointer-events: none;
    }

    .feedback-overlay.show {
      display: flex;
    }

    .feedback-overlay::after {
      content: '❌';
      font-size: 5em;
      color: #d63031;
      text-shadow: 0 0 5px white;
    }

    .placeholder-content img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      cursor: grab;
    }


    /* ... (Q3 styles: .click-select-grid, .select-option) ... */
    .click-select-grid {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .select-option {
      background: #f7f7f7;
      border-radius: 10px;
      padding: 12px;
      cursor: pointer;
      font-size: 22px;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      /* box-shadow: 0 4px 10px rgba(0,0,0,0.1); */
      position: relative;
      transition: 0.2s;
      border: 2px solid transparent;
      text-align: left;
    }



    .option-letter {
      font-weight: bold;
      margin-right: 8px;
    }

    .select-option img {
      max-width: 100%;
      height: auto;
      object-fit: contain;
      display: block;
    }

    .select-option.selected {
      border-color: #3498db;
      box-shadow: 0 0 15px rgba(52, 152, 219, 0.8);
      transform: scale(1.05);
    }

    .select-option .feedback-mark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 4em;
      font-weight: bolder;
      pointer-events: none;
      display: none;
      z-index: 10;
    }

    .select-option.correct .feedback-mark {
      content: '✅';
      color: #27ae60;
      text-shadow: 0 0 8px white;
      display: block;
    }

    .select-option.wrong .feedback-mark {
      content: '❌';
      color: #e74c3c;
      text-shadow: 0 0 8px white;
      display: block;
    }

    .select-option.wrong.selected {
      background-color: #fcebeb;
      border-color: #e74c3c;
    }


    /* ... (Q2, Q4 styles: .drag-match-options, .drag-match-grid, .drop-target-container) ... */
    .drag-match-options {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 25px;
    }

    .drag-match-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px 10px;
      padding: 10px;
      margin-top: 10px;
    }

    .match-row {
      display: flex;
      align-items: center;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .match-drop-box {
      width: 55%;
      height: 80px;
      border: 3px dashed #b2b2b2;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 5px;
      background-color: #f0f8ff;
    }

    .drop-target-container {
      display: flex;
      gap: 10px;
      justify-content: center;
      margin-top: 20px;
    }


    /* ... (Q5 styles: .match-wrapper, .match-container, .connection-dot, etc.) ... */
    .match-wrapper {
      position: relative;
      width: 100%;
      margin: 0 auto;
      padding: 5px 0;
    }

    #match-canvas {
      position: absolute;
      top: 0;
      left: 0;
      pointer-events: none;
      z-index: 1;
    }

    .match-container {
      display: grid;
      grid-template-columns: 1fr 205px 1fr;
      align-items: stretch;
      width: 100%;
      position: relative;
      z-index: 20;
      padding: 10px 0;
    }

    .match-column {
      display: flex;
      flex-direction: column;
      gap: 25px;
    }

    .match-item-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 90px;
      width: 152px;
      position: relative;
      overflow: hidden;
      margin-left: 36px;
    }

    .match-item {
      padding: 8px 10px;
      border-radius: 6px;
      cursor: pointer;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-sizing: border-box;
      user-select: none;
      width: 100%;
      font-size: 1em;
      background: #f3f3f3;
      color: #333;
    }

    .match-item.right {
      background: #e0f0ff;
    }

    .match-item.matched {
      opacity: 0.5;
      cursor: default;
    }

    .dot-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      justify-items: center;
      padding: 0 10px;
    }

    .dot-column {
      display: flex;
      flex-direction: column;
      gap: 118px;
    }

    .connection-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      cursor: pointer;
      position: relative;
      z-index: 30;
      transform: translateY(18px);
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }

    .left-dot {
      background: #3498db;
      margin-right: auto;
    }

    .right-dot {
      background: #27ae60;
      margin-left: auto;
    }

    .match-item-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .match-image-box {
      width: 45%;
      height: 80px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .match-image-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .action-row {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-top: 15px;
    }

    #feedbackMessage {
      margin-top: 0;
      font-weight: bold;
      font-size: 1.1em;
      color: #d32f2f;
    }

    .try-again-btn {
      background: #ec5526d9;
      border: none;
      padding: 15px 30px;
      border-radius: 15px;
      color: white;
      font-size: 1.1rem;
      cursor: pointer;
      margin-left: 10px;
      transition: 0.25s;
      width: 100%;
    }

    .try-again-btn:hover {
      background: #c43a13;
    }

    .nav-actions {
      width: 95%;
      display: flex;
      justify-content: flex-end;
      /* 👉 right side */
      margin-top: 43%;
    }


    .next-btn {
      background: #28a745;
      border: none;
      padding: 15px 30px;
      border-radius: 15px;
      color: white;
      font-size: 1.1rem;
      cursor: pointer;
      margin-left: 10px;
      transition: 0.25s;
    }

    .next-btn:hover {
      background: #218838;
    }

    .closeicon {
      border: none;
      background: none;
      color: white;
    }

    .feedback-mark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
    }

    .feedback-mark img {
      width: 157px;
      height: auto;
    }

    .match-drop-box {
      position: relative;
    }

    .order-final-feedback-top {
      text-align: center;
      margin-bottom: 10px;
    }

    .order-final-feedback-top .final-mark {
      width: 157px;
      height: auto;
      margin-top: -117px;
    }

    #matchline-final-feedback {
      position: absolute;
      top: 88px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 999;
    }

    #matchline-final-feedback img {
      width: 157px;
      ;
      height: auto;
    }

    .exam-box {
      width: 95%;
      height: 95vh;
      margin: 10px auto;
      background: #fff;
      padding: 20px;
      position: relative;
    }

    .question-nav {
      position: absolute;
      right: 200px;
      top: 20%;

      display: grid;
      grid-template-rows: repeat(10, 40px);
      /* ✅ 10 vertically */
      grid-auto-flow: column;
      /* ✅ fill column-wise */
      gap: 8px;
    }

    .question-nav div {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #ddd;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-weight: 500;
    }

    .question-nav .active {
      background: #28a745;
      color: #fff;
    }


    .actions {
      position: relative;

      width: 46%;
      left: 27%;
    }

    .option {
      padding: 10px;
      border: 1px solid #ccc;
      margin: 8px 0;
    }
  </style>

  <style>
    .custom-alert-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
    }

    .custom-alert-box {
      background: #fff;
      padding: 25px 30px;
      border-radius: 10px;
      max-width: 350px;
      width: 90%;
      text-align: center;
      animation: popupScale 0.25s ease;
    }

    .custom-alert-message {
      font-size: 16px;
      margin-bottom: 20px;
      color: #333;
    }

    .custom-alert-btn {
      background: #fd5f00;
      border: none;
      padding: 8px 20px;
      color: #fff;
      border-radius: 6px;
      cursor: pointer;
    }

    .custom-alert-btn:hover {
      background: #fd5f00;
    }

    @keyframes popupScale {
      from {
        transform: scale(0.8);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>
  <style>
    .start-screen {
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      overflow: hidden;
    }

    /* 🔥 Background Layer */
    .start-screen::before {
      content: "";
      position: absolute;
      inset: 0;

      background: url('images/quiz_background.jpeg');
      background-size: cover;
      background-position: center;

      filter: blur(8px);
      /* 👉 blur control yaha */
      transform: scale(1.1);
      /* 👉 edges cut na ho */

      z-index: -1;
    }

    /* OPTIONAL DARK OVERLAY */
    .start-screen::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      /* readability ke liye */
      z-index: -1;
    }

    /* CARD */
    .start-card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      padding: 40px 50px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      color: #fff;
      animation: fadeIn 0.6s ease;
      width: 30%;
      height: 30%;
    }

    .title {
      font-size: 35px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .subtitle {
      font-size: 20px;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    /* BUTTON */
    .start-btn {
      padding: 14px 32px;
      font-size: 22px;
      width: 50%;
      border: none;
      border-radius: 50px;
      background: linear-gradient(135deg, #28a745, #20c997);
      color: #fff;
      cursor: pointer;
      transition: 0.3s ease;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .start-btn:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    /* ANIMATION */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .quiz-container {
      position: relative;
      min-height: 100vh;
      padding: 40px;

      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;

      overflow: hidden;
    }

    /* 🔥 IMAGE LAYER */
    .quiz-container::before {
      content: "";
      position: absolute;
      inset: 0;

      background: url('images/quiz_background.jpeg');
      background-size: cover;
      background-position: center;

      filter: blur(8px);
      transform: scale(1.1);

      z-index: -2;
    }

    /* 🔥 DARK OVERLAY */
    .quiz-container::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: -1;
    }
  </style>
  <script>
    function showCustomAlert(message, autoCloseTime = 2000, callback = null) {
      const alertBox = document.getElementById("customAlert");
      const alertMsg = document.getElementById("customAlertMsg");

      alertMsg.innerHTML = message;
      alertBox.style.display = "flex";

      // Auto close after time
      if (autoCloseTime > 0) {
        setTimeout(() => {
          alertBox.style.display = "none";

          // Redirect / next action
          if (typeof callback === "function") {
            callback();
          }
        }, autoCloseTime);
      }
    }

    function closeCustomAlert() {
      document.getElementById("customAlert").style.display = "none";
    }

  </script>
</head>

<body oncontextmenu="return false" oncopy="return false" onpaste="return false" oncut="return false"
  onselectstart="return false">
  <div id="startScreen" class="start-screen">
    <div class="start-card">
      <h2 class="title">Ready for Quiz?</h2>
      <p class="subtitle">Test your knowledge and have fun 🚀</p>

      <button onclick="startquiz()" class="start-btn">
        ▶ Start Quiz
      </button>
    </div>
  </div>

  <h3 style="
    position: fixed;
    top: 30px;
    left: 50%;
    transform: translateX(-50%);
    margin: 0;
    font-size: 30px;
    font-weight: bold;
">
    <?php echo htmlspecialchars($quiz_title); ?>
  </h3>


  <div class="quiz-card text-center" id="quizCard" style="display:none;">


    <div id="questionText">Loading...</div>
    <div class="answers" id="answerArea">
      <div id="match-wrapper" class="match-wrapper">
      </div>

    </div>

    <div class="actions text-center">
      <button id="submitBtn" onclick="prevQ()">Previous</button>
      <button id="submitBtn" onclick="nextQ()">Next</button>
    </div>

  </div>




  <div class="question-nav" id="qNav"></div>

  <div class="nav-actions">
    <button onclick="submitexam()" class="next-btn">
      Submit Test
    </button>
  </div>





  <div id="resumeOverlay" style="
    display:none;
    position:fixed;
    top:0;left:0;
    width:100%;height:100%;
    background:rgba(0,0,0,0.9);
    color:#fff;
    z-index:9999;
    align-items:center;
    justify-content:center;
    flex-direction:column;
">
    <h2>Resume quiz</h2>
    <p>Click below to continue the quiz in fullscreen mode.</p>
    <button onclick="resumeQuiz()" style="
        padding:12px 25px;
        font-size:16px;
        cursor:pointer;
    ">
      Resume quiz
    </button>
  </div>

  <!-- Custom Alert Popup -->
  <div id="customAlert" class="custom-alert-overlay">
    <div class="custom-alert-box">
      <div id="customAlertMsg" class="custom-alert-message"></div>
      <button onclick="closeCustomAlert()" class="custom-alert-btn">OK</button>
    </div>
  </div>
  </div>

  <script>

    function escapeHTML(str) {
      return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
    }

    // Function to create dummy image elements
    function createDummyImage(src, alt, draggable = false) {
      const img = document.createElement('img');
      img.src = src;
      img.alt = alt;
      img.draggable = draggable;
      return img;
    }

    // Global state
    let draggedImageId = null; // For Q1
    let currentDroppedId = null; // For Q1
    let selectedOptionId = null; // For Q3
    let draggedItem = null; // For Q2, Q4, Q6

    // Line Match State (Q5)
    let matchedPairs = [];
    let isDrawing = false;
    let startDotElement = null;
    let canvas, ctx;


    // ===== Fetch Questions from PHP =====



    document.addEventListener("DOMContentLoaded", () => {
      fetch("get_attempt_quiz.php?quiz_id=<?php echo $quiz_id; ?>")
        .then(res => res.json())
        .then(data => {
          questions = shuffleArray(data);
          current = 0;
          console.log("Loaded questions:", questions);
          loadQuestion();
        })
        .catch(err => console.error("Error fetching questions:", err));
    });

    function normalize(val) {
      // make comparisons robust: convert to string, trim and lowercase
      if (typeof val === "undefined" || val === null) return "";
      return String(val).trim().toLowerCase();
    }


    let questions = [];   // 🔥 FIX 1
    let current = 0;
    const quizCard = document.getElementById("quizCard");



    document.addEventListener("DOMContentLoaded", () => {
      // applyRandomStyles();
    });


    // ===== Load Question =====
    async function loadQuestion() {
      const q = questions[current];
      const qText = document.getElementById("questionText");
      const aArea = document.getElementById("answerArea");
      currentQuestionId = q.id;
      questionType = q.type;

      // Clean up previous state
      aArea.innerHTML = "";
      matchedPairs = [];
      selectedOptionId = null;
      draggedImageId = null;
      currentDroppedId = null;
      isDrawing = false;
      startDotElement = null;
      window.removeEventListener('resize', sizeCanvas);
      // feedbackMsg.innerHTML = "";



      const matchWrapper = document.createElement('div');
      matchWrapper.id = 'match-wrapper';
      matchWrapper.className = 'match-wrapper';
      aArea.appendChild(matchWrapper);

      // Set default question text
      // qText.innerHTML = `${q.question} ${q.subQuestion ? '<div class="sub-question">' + q.subQuestion + '</div>' : ''}`;

      qText.innerHTML = ""; // clear

      const wrapper = document.createElement('div');
      wrapper.className = 'question-wrapper';

      // 🔹 TEXT FIRST
      const textContent = document.createElement('div');

      textContent.innerHTML = `
  <div class="main-question">
    <pre>${escapeHTML(q.question || "")}</pre>
  </div>

  ${q.subQuestion && q.subQuestion.trim() !== ""
          ? '<div class="sub-question"><pre>' + q.subQuestion + '</pre></div>'
          : ''
        }
`;

      wrapper.appendChild(textContent);

      // 🔹 IMAGE AFTER TEXT (👇 niche aayegi)
      if (q.main_image && q.main_image.trim() !== "") {

        const imageArea = document.createElement('div');
        imageArea.className = 'question-image-area';

        imageArea.appendChild(
          createDummyImage(q.main_image, 'Question Image')
        );

        wrapper.appendChild(imageArea);
      }

      qText.appendChild(wrapper);

      // --- CLICK-TO-SELECT IMAGE UI (Q3) ---
      // submitBtn.classList.add('green-submit-btn');
      // ... (Q3 UI generation logic) ...
      const grid = document.createElement('div');
      grid.className = 'click-select-grid';
      q.options.forEach((opt, index) => {

        const optionDiv = document.createElement('div');
        optionDiv.className = 'select-option';
        optionDiv.setAttribute('data-option-id', opt.option_id);
        optionDiv.setAttribute('data-id', opt.id);
        optionDiv.setAttribute('data-label', opt.label);

        // 🔹 Alphabet Generate
        const alphabet = String.fromCharCode(65 + index); // A,B,C,D

        // 🔹 Alphabet Label Add
        const letterSpan = document.createElement("span");
        letterSpan.className = "option-letter";
        letterSpan.innerText = alphabet + ". ";
        optionDiv.appendChild(letterSpan);

        // 🔹 Image
        if (opt.src && opt.src.trim() !== "") {
          const img = createDummyImage(opt.src, opt.label);
          img.classList.add("option-image");
          optionDiv.appendChild(img);
        }

        // 🔹 Text
        if (opt.label && opt.label.trim() !== "") {
          const textSpan = document.createElement("span");
          textSpan.className = "option-text";
          textSpan.innerText = opt.label;
          optionDiv.appendChild(textSpan);
        }

        const feedbackMark = document.createElement('div');
        feedbackMark.className = 'feedback-mark';
        optionDiv.appendChild(feedbackMark);

        optionDiv.onclick = function () { handleImageClick(opt.option_id); };

        grid.appendChild(optionDiv);
      });
      matchWrapper.appendChild(grid);




      // applyRandomStyles();
      renderNav();

      // fetch saved answer from DB
      const saved = await getSavedAnswer(currentQuestionId);
      if (saved && saved.answer) {
        restoreAnswer(currentQuestionId, saved.answer, saved.question_type);
      }

    }

    function restoreAnswer(questionId, answer, questionType) {
      const saved = answer;
      if (!saved) return;

      // ✅ get question object safely
      const q = questions.find(q => q.id == questionId);
      if (!q) return;

      console.log("Restoring", questionId, questionType, saved);


      // =====================
      // CLICK SELECT IMAGE ✅
      // =====================


      const allOptions = document.querySelectorAll('.select-option');

      allOptions.forEach(opt => {
        opt.classList.remove('selected', 'correct', 'wrong');
        const mark = opt.querySelector('.feedback-mark');
        if (mark) mark.innerHTML = '';
      });

      const selectedOption = document.querySelector(
        `.select-option[data-option-id="${saved}"]`
      );

      if (selectedOption) {
        selectedOption.classList.add('selected');
        selectedOptionId = saved; // 🔥 important
      }





    }



    // --- Drag-Drop Image Handler (Q1) ---
    function removeDroppedImage(fromPlaceholder = false) {
      const dropContent = document.querySelector('.single-placeholder .placeholder-content');
      const sourceElement = document.querySelector(`.image-option[data-id="${currentDroppedId}"]`);
      const feedbackOverlay = document.querySelector('.single-placeholder .feedback-overlay');

      if (sourceElement) {
        sourceElement.style.visibility = 'visible';
        sourceElement.setAttribute('data-is-dropped', 'false');
      }
      dropContent.innerHTML = '';
      currentDroppedId = null;
      if (feedbackOverlay) feedbackOverlay.classList.remove('show');
      if (fromPlaceholder) draggedImageId = null;
    }

    function dropImage(e) {
      e.preventDefault();
      const dropBox = e.currentTarget;
      const dropContent = dropBox.querySelector('.placeholder-content');
      const feedbackOverlay = dropBox.querySelector('.feedback-overlay');
      const q = questions[current];
      feedbackOverlay?.classList.remove('show');

      if (currentDroppedId) {
        removeDroppedImage();
      }

      if (draggedImageId) {
        const selectedOption = q.options.find(opt => opt.option_id === draggedImageId);
        if (selectedOption) {
          dropContent.innerHTML = '';
          const img = createDummyImage(selectedOption.src, selectedOption.label, true);

          img.setAttribute("data-option-id", selectedOption.option_id);
          dropContent.appendChild(img);
          currentDroppedId = selectedOption.option_id;

          const sourceElement = document.querySelector(`.image-option[data-id="${draggedImageId}"]`);
          if (sourceElement) {
            sourceElement.style.visibility = 'hidden';
            sourceElement.setAttribute('data-is-dropped', 'true');
          }
        }
      }
      draggedImageId = null;
    }


    function handleImageClick(optionId) {

      const allOptions = document.querySelectorAll('.select-option');
      allOptions.forEach(opt => {
        opt.classList.remove('selected', 'correct', 'wrong');
        opt.querySelector('.feedback-mark').innerHTML = '';
      });

      const selectedOption = document.querySelector(
        `.select-option[data-option-id="${optionId}"]`
      );

      if (selectedOption) {
        selectedOption.classList.add('selected');
        selectedOptionId = optionId; // 🔥 DB option_id
      }
    }


    // --- Drag/Drop Text Handlers (Q2, Q4, Q6) ---
    function addDragEvents(container) {
      container.querySelectorAll('.drag-item').forEach(item => {
        item.draggable = true;
        item.addEventListener('dragstart', e => {
          draggedItem = e.target;
          e.dataTransfer.setData('text/plain', e.target.textContent);
          if (e.target.hasAttribute('data-id')) {
            e.dataTransfer.setData('data-id', e.target.getAttribute('data-id'));
          }
          setTimeout(() => e.target.style.opacity = '0.5', 0);
        });

        item.addEventListener('dragend', e => {
          e.target.style.opacity = '1';
        });
      });
    }

    function allowDrop(e) { e.preventDefault(); }



    // --- Line Match Handlers (Q5) ---

    function dropText(e) {
      e.preventDefault();
      const target = e.currentTarget;

      if (!draggedItem) return;

      // allow only on drop boxes
      if (
        !target.classList.contains("drop-box") &&
        !target.classList.contains("match-drop-box")
      ) return;

      const existingItem = target.querySelector(".drag-item");

      const sourceParent = draggedItem.parentElement;

      // 🔁 SWAP CASE
      if (existingItem && existingItem !== draggedItem) {
        sourceParent.appendChild(existingItem);
        target.appendChild(draggedItem);
      }
      // ➡️ EMPTY BOX CASE
      else if (!existingItem) {
        target.appendChild(draggedItem);
      }

      draggedItem.style.visibility = "visible";
      draggedItem = null;


    }


    function shuffleArray(array) {
      for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
      }
      return array;
    }

    function createDot(column, value) {
      const dot = document.createElement('div');
      dot.className = `connection-dot ${column}-dot`;
      dot.setAttribute('data-value', value);
      dot.setAttribute('data-column', column);
      return dot;
    }
    function getRelativeMousePos(e) {
      const wrapper = document.getElementById('match-wrapper');
      const rect = wrapper.getBoundingClientRect();
      return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }
    function getDotCenter(el) {
      const wrapper = document.getElementById('match-wrapper');
      const wrapperRect = wrapper.getBoundingClientRect();
      const rect = el.getBoundingClientRect();
      // Calculate center relative to the wrapper
      return { x: rect.left + rect.width / 2 - wrapperRect.left, y: rect.top + rect.height / 2 - wrapperRect.top };
    }
    function sizeCanvas() {
      const wrapper = document.getElementById('match-wrapper');
      if (!canvas || !wrapper) return;
      canvas.width = wrapper.offsetWidth;
      canvas.height = wrapper.offsetHeight;
      canvas.style.position = 'absolute';
      canvas.style.top = '0';
      canvas.style.left = '0';
      canvas.style.zIndex = '0';
      drawLines();
    }


    function startDrawing(e) {
      const dot = e.target.closest('.connection-dot');
      if (!dot || !dot.classList.contains('left-dot')) return;

      // If left dot already matched, unmatch it (so user can redraw)
      if (dot.getAttribute('data-matched') === 'true') {
        const leftVal = dot.getAttribute('data-value');

        // Find existing pair and remove it
        const idx = matchedPairs.findIndex(pair => pair[0] === leftVal);
        if (idx !== -1) {
          const oldRightVal = matchedPairs[idx][1];

          // Unmatch right dot & right item
          document.querySelector(`.right-dot[data-value="${oldRightVal}"]`)?.setAttribute('data-matched', 'false');
          document.querySelector(`.match-item.right[data-value="${oldRightVal}"]`)?.classList.remove('matched');

          // Remove the pair
          matchedPairs.splice(idx, 1);
        }

        // Unmatch left dot & left item
        dot.setAttribute('data-matched', 'false');
        document.querySelector(`.match-item.left[data-value="${leftVal}"]`)?.classList.remove('matched');

        // Redraw lines
        drawLines();
      }

      // Start drawing now
      isDrawing = true;
      startDotElement = dot;
    }

    function drawWhileMoving(e) {
      if (!isDrawing || !startDotElement) return;
      const mousePos = getRelativeMousePos(e);
      drawLines(mousePos);
    }


    function stopDrawing(e) {
      if (!isDrawing) return;
      isDrawing = false;
      if (!startDotElement) return;

      const endDot = e.target.closest('.connection-dot');

      if (endDot && endDot.classList.contains('right-dot')) {

        const leftVal = startDotElement.getAttribute('data-value');
        const rightVal = endDot.getAttribute('data-value');

        // 🔥 1) Remove old pair for LEFT dot (if exists)
        let idx = matchedPairs.findIndex(pair => pair[0] === leftVal);
        if (idx !== -1) {
          const oldRightVal = matchedPairs[idx][1];

          document.querySelector(`.right-dot[data-value="${oldRightVal}"]`)?.setAttribute('data-matched', 'false');
          document.querySelector(`.match-item.right[data-value="${oldRightVal}"]`)?.classList.remove('matched');

          matchedPairs.splice(idx, 1);
        }

        // 🔥 2) Remove old pair for RIGHT dot (if exists)
        idx = matchedPairs.findIndex(pair => pair[1] === rightVal);
        if (idx !== -1) {
          const oldLeftVal = matchedPairs[idx][0];

          document.querySelector(`.left-dot[data-value="${oldLeftVal}"]`)?.setAttribute('data-matched', 'false');
          document.querySelector(`.match-item.left[data-value="${oldLeftVal}"]`)?.classList.remove('matched');

          matchedPairs.splice(idx, 1);
        }

        // 🔥 3) Now add new match
        startDotElement.setAttribute('data-matched', 'true');
        endDot.setAttribute('data-matched', 'true');

        document.querySelector(`.match-item.left[data-value="${leftVal}"]`)?.classList.add('matched');
        document.querySelector(`.match-item.right[data-value="${rightVal}"]`)?.classList.add('matched');

        matchedPairs.push([leftVal, rightVal]);
      }

      startDotElement = null;
      drawLines();
    }


    function cancelDrawing() {
      if (isDrawing) {
        isDrawing = false;
        if (startDotElement) startDotElement.style.boxShadow = '0 0 5px rgba(0,0,0,0.2)';
        startDotElement = null;
        ctx.setLineDash([]);
        drawLines();
      }
    }
    function drawLines(currentMousePos = null) {
      if (!ctx) return;
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      matchedPairs.forEach(pair => {
        const leftDot = document.querySelector(`.left-dot[data-value="${pair[0]}"]`);
        const rightDot = document.querySelector(`.right-dot[data-value="${pair[1]}"]`);
        if (!leftDot || !rightDot) return;
        const start = getDotCenter(leftDot);
        const end = getDotCenter(rightDot);
        ctx.strokeStyle = '#e64a19';
        ctx.lineWidth = 3;
        ctx.setLineDash([]);
        ctx.beginPath();
        ctx.moveTo(start.x, start.y);
        ctx.lineTo(end.x, end.y);
        ctx.stroke();
      });
      if (isDrawing && startDotElement && currentMousePos) {
        const start = getDotCenter(startDotElement);
        ctx.strokeStyle = '#e64a19';
        ctx.lineWidth = 3;
        ctx.setLineDash([5, 5]);
        ctx.beginPath();
        ctx.moveTo(start.x, start.y);
        ctx.lineTo(currentMousePos.x, currentMousePos.y);
        ctx.stroke();
        ctx.setLineDash([]);
      }
    }


    function collectCurrentAnswer() {

      const q = questions[current];
      if (!q) return null;




      /* ---------------------------------
         CLICK SELECT IMAGE
         → already ID
      --------------------------------- */

      return selectedOptionId ?? null;







      return null;
    }


    /* -----------------------------
NAVIGATION
----------------------------- */

    function renderNav() {
      let nav = document.getElementById("qNav");
      nav.innerHTML = "";

      questions.forEach((q, i) => {
        let d = document.createElement("div");
        d.innerText = i + 1;

        if (i === current) d.classList.add("active");

        d.onclick = () => {
          // ✅ save current answer before moving
          const answer = collectCurrentAnswer();
          if (answer !== null && attemptId) {
            saveAnswer(currentQuestionId, answer);
          }

          // ✅ move to clicked question
          current = i;
          loadQuestion();
        };

        nav.appendChild(d);
      });
    }


    function nextQ() {

      const answer = collectCurrentAnswer();

      if (answer !== null && attemptId) {
        saveAnswer(currentQuestionId, answer);
      }

      if (current < questions.length - 1) {
        current++;
        loadQuestion();
      }
    }


    // function prevQ() {

    //   const answer = collectCurrentAnswer();

    //   if (answer !== null && attemptId) {
    //     saveAnswer(currentQuestionId, answer);
    //   }

    //   if (current > 0) {
    //     current--;
    //     loadQuestion();
    //   }
    // }


    async function prevQ() {

      const answer = collectCurrentAnswer();

      if (answer !== null && attemptId) {
        await saveAnswer(currentQuestionId, answer);
      }

      if (current > 0) {
        current--;
        await loadQuestion();   // call after saving

        // Now fetch saved answer from DB
        const saved = await getSavedAnswer(currentQuestionId);
        console.log("Restoring answer for QID", currentQuestionId, saved);
        if (saved && saved.answer) {
          restoreAnswer(currentQuestionId, saved.answer, saved.question_type);
        }
      }
    }


  </script>

  <script>

    /* ----------------------------
       FULLSCREEN HELPER
    ----------------------------- */
    function enterFullScreen() {
      const el = document.documentElement;
      if (el.requestFullscreen) el.requestFullscreen();
      else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
      else if (el.msRequestFullscreen) el.msRequestFullscreen();
    }

    let examLocked = false;
    let fsPaused = false;

    /* ----------------------------
       FULLSCREEN EXIT DETECT
    ----------------------------- */
    document.addEventListener("fullscreenchange", () => {

      if (!examLocked) return;

      if (!document.fullscreenElement && !fsPaused) {
        fsPaused = true;
        showResumeOverlay();
      }
    });

    /* ----------------------------
       TAB / WINDOW SWITCH
    ----------------------------- */
    document.addEventListener("visibilitychange", () => {

      if (!examLocked) return;

      if (document.hidden && !fsPaused) {
        fsPaused = true;
        showResumeOverlay();
      }
    });

    /* ----------------------------
       BLOCK KEYS (BEST EFFORT)
    ----------------------------- */
    document.addEventListener("keydown", e => {

      if (
        e.key === "F12" ||
        e.key === "Escape" ||
        (e.ctrlKey && ["c", "v", "x", "u", "s", "p", "a", "w"].includes(e.key.toLowerCase()))
      ) {
        e.preventDefault();
        return false;
      }
    });

    /* ----------------------------
       LOCK UI
    ----------------------------- */
    function lockExamUI() {
      examLocked = true;
      document.body.style.userSelect = "none";
      document.oncontextmenu = () => false;
    }

    /* ----------------------------
       START QUIZ
    ----------------------------- */

    let attemptId = null;
    function startquiz() {

      //  const startedAt = new Date().toISOString().slice(0, 19).replace('T', ' ');
      const now = new Date();
      const startedAt =
        now.getFullYear() + "-" +
        String(now.getMonth() + 1).padStart(2, "0") + "-" +
        String(now.getDate()).padStart(2, "0") + " " +
        String(now.getHours()).padStart(2, "0") + ":" +
        String(now.getMinutes()).padStart(2, "0") + ":" +
        String(now.getSeconds()).padStart(2, "0");

      fetch("quiz_attempt_save.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "quiz_id=<?php echo $quiz_id; ?>" +
          "&started_at=" + encodeURIComponent(startedAt)
      })
        .then(r => r.json())
        .then(d => {

          attemptId = d.attempt_id;
          quizId = d.quiz_id;
          enterFullScreen();
          lockExamUI();

          document.getElementById("startScreen").style.display = "none";
          document.getElementById("quizCard").style.display = "flex";
          document.getElementById("qNav").style.display = "grid";
        });
    }

    let answers = {};
    function saveAnswer(questionId, answerData) {
      answers[questionId] = answerData;

      fetch("quiz_attempt_answer_save.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body:
          "attempt_id=" + attemptId +
          "&question_id=" + questionId +
          "&question_type=" + questionType +
          "&answer=" + encodeURIComponent(answerData)
      });
    }

    function getSavedAnswer(questionId) {
      return fetch("quiz_attempt_answer_get.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body:
          "attempt_id=" + attemptId +
          "&question_id=" + questionId
      })
        .then(res => res.json());
    }




    /* ----------------------------
       RESUME OVERLAY
    ----------------------------- */
    function showResumeOverlay() {
      document.getElementById("resumeOverlay").style.display = "flex";
    }

    /* ----------------------------
       RESUME EXAM (USER CLICK)
    ----------------------------- */
    function resumeQuiz() {
      document.getElementById("resumeOverlay").style.display = "none";
      fsPaused = false;
      enterFullScreen();   // ✅ works because user clicked
    }


    function submitexam() {
      // Save last answer if any
      const answer = collectCurrentAnswer();
      if (answer !== null && attemptId) {
        saveAnswer(currentQuestionId, answer);
      }

      // ⏰ current datetime (MySQL compatible)
      const now = new Date();
      const currentTime =
        now.getFullYear() + "-" +
        String(now.getMonth() + 1).padStart(2, "0") + "-" +
        String(now.getDate()).padStart(2, "0") + " " +
        String(now.getHours()).padStart(2, "0") + ":" +
        String(now.getMinutes()).padStart(2, "0") + ":" +
        String(now.getSeconds()).padStart(2, "0");

      fetch("submit_quiz.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "attempt_id=" + attemptId + "&quiz_id=" + <?php echo $quiz_id; ?> +
          "&completed_at=" + encodeURIComponent(currentTime)
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "completed") {
            // alert(
            //     "✅ Quiz Completed!\n" +
            //     "Correct: " + data.correct + "/" + data.total + "\n" +
            //     "Percentage: " + data.percentage + "%"
            // );
            showCustomAlert("🎉 Quiz Completed!", 2000, function () {

              window.location.href = "manage_quiz.php?lesson_id=<?php echo $lesson_id; ?>&course_id=<?php echo $course_id; ?>";
            });
          } else {
            //           alert(
            // "❌ Error: " + (data.msg ?? "Something went wrong") + "\n" +
            //         "Quiz ID: " + (data.quiz_id ?? "N/A") + "\n" +
            //         "Attempt ID: " + (data.attempt_id ?? "N/A")
            //         );
            showCustomAlert(
              "❌ Error Occurred!<br>" +
              "Message: " + (data.msg ?? "Something went wrong"),
              3000
            );
          }
        })
        .catch(err => {
          console.error(err);
          showCustomAlert("❌ Server Error");
        });
    }

  </script>


</body>

</html>