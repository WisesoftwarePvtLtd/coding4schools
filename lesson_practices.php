<!DOCTYPE html>
<html lang="en">
<?php include 'header.php';
$question_id = isset($_GET['question_id']) ? intval($_GET['question_id']) : 0;
$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Combined Chinese Quiz</title>
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

        .option-image {
            max-width: 100%;
            height: 80px;
            object-fit: contain;
        }

        .option-text {
            margin-top: 8px;
            font-weight: bold;
            font-size: 16px;
        }

        .select-option.selected {
            border: 2px solid #2196f3;
        }

        .select-option.correct {
            border: 2px solid #28a745;
        }

        .select-option.wrong {
            border: 2px solid #e64a19;
        }

        .main-question {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .question-image-wrapper {
            margin: 15px 0;
            text-align: center;
        }

        .question-image {
            max-width: 300px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .sub-question {
            margin-top: 10px;
            font-size: 16px;
            color: #555;
        }

        .back-btn-wrapper {
            position: fixed;
            top: 19%;
            left: 4%;
            z-index: 999;
        }

        pre {
    white-space: pre-wrap;   /* wrap enable */
    word-break: break-word;  /* long words break */
    overflow-wrap: break-word;
    margin: 0;
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

<body class="text-center">
    <div class="quiz-card text-center" id="quizCard">
        <div id="questionText">Loading...</div>
        <div class="answers" id="answerArea">
            <div id="match-wrapper" class="match-wrapper">
            </div>
        </div>
        <!-- <button id="submitBtn">Check</button><div id="feedbackMessage" class="feedback"></div> -->
        <div class="action-row">
            <button id="submitBtn">Check</button>
            <div id="feedbackMessage" class="feedback"></div>
        </div>


    </div>

    <div id="quiz-container"></div>
    <div class="back-btn-wrapper">
        <a href="lesson.php?lesson_id=<?php echo $lesson_id; ?>" class="btn btn-primary">
            ← Back To Lesson
        </a>
    </div>

    <!-- Custom Alert Popup -->
    <div id="customAlert" class="custom-alert-overlay">
        <div class="custom-alert-box">
            <div id="customAlertMsg" class="custom-alert-message"></div>
            <button onclick="closeCustomAlert()" class="custom-alert-btn">OK</button>
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
        let questions = [];
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
            fetch("get_lesson_practices.php?lesson_id=<?php echo $lesson_id; ?>")
                .then(res => res.json())
                .then(data => {
                    questions = shuffleArray(data);
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


        let currentQ = 0;
        const quizCard = document.getElementById("quizCard");

        // const bgImages = [
        //     'images/que-background-1.jpg',
        //     'images/que-background-2.jpg',
        //     'images/que-background-3.jpg',
        //     'images/que-background-4.jpg'
        // ];

        const colors = ["#f77f7f", "#6aa9ff", "#6ad46a", "#f5b84f", "#9c7cf7"];

        // function getRandomBg() {
        //     return bgImages[Math.floor(Math.random() * bgImages.length)];
        // }

        // function applyRandomStyles(isTryAgain = false) {
        //     quizCard.style.backgroundImage = `url(${getRandomBg()})`;
        //     quizCard.style.backgroundSize = "cover";
        //     quizCard.style.backgroundPosition = "center";

        //     if (isTryAgain) {
        //         const color = colors[Math.floor(Math.random() * colors.length)];
        //         document.querySelectorAll(".drag-item").forEach(el => el.style.background = color);
        //     }
        // }


        // document.addEventListener("DOMContentLoaded", () => {
        //     applyRandomStyles();
        // });


        // ===== Load Question =====
        function loadQuestion() {
            const q = questions[currentQ];
            const qText = document.getElementById("questionText");
            const aArea = document.getElementById("answerArea");
            const submitBtn = document.getElementById("submitBtn");
            const feedbackMsg = document.getElementById("feedbackMessage");

            // Clean up previous state
            aArea.innerHTML = "";
            matchedPairs = [];
            selectedOptionId = null;
            draggedImageId = null;
            currentDroppedId = null;
            isDrawing = false;
            startDotElement = null;
            window.removeEventListener('resize', sizeCanvas);
            feedbackMsg.innerHTML = "";

            // Reset button
            submitBtn.textContent = "Check";
            submitBtn.disabled = false;
            submitBtn.classList.remove('green-submit-btn');

            const matchWrapper = document.createElement('div');
            matchWrapper.id = 'match-wrapper';
            matchWrapper.className = 'match-wrapper';
            aArea.appendChild(matchWrapper);

            // Set default question text
            // qText.innerHTML = `${q.question} ${q.subQuestion ? '<div class="sub-question">' + q.subQuestion + '</div>' : ''}`;

            // Build question HTML safely
            qText.innerHTML = ""; // Clear default content

            const questionContentWrapper = document.createElement('div');
            questionContentWrapper.className = 'question-image-layout';

            // 🔹 Only create image area if image exists
            if (q.main_image && q.main_image.trim() !== "") {

                const imageArea = document.createElement('div');
                imageArea.className = 'question-image-area';

                imageArea.appendChild(
                    createDummyImage(q.main_image, 'Question Image')
                );

                questionContentWrapper.appendChild(imageArea);
            }

            // 🔹 Always create text content
            const textContent = document.createElement('div');
            textContent.className = 'question-text-content';
            textContent.innerHTML = `
    <div class="main-question">
    <pre>${escapeHTML(q.question || "")}</pre>
  </div>
    ${q.subQuestion && q.subQuestion.trim() !== ""
                    ? '<div class="sub-question">' + q.subQuestion + '</div>'
                    : ''
                }
`;

            questionContentWrapper.appendChild(textContent);

            qText.appendChild(questionContentWrapper);

            // --- CLICK-TO-SELECT IMAGE UI (Q3) ---
            submitBtn.classList.add('green-submit-btn');
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
            const q = questions[currentQ];
            feedbackOverlay?.classList.remove('show');

            if (currentDroppedId) {
                removeDroppedImage();
            }

            if (draggedImageId) {
                const selectedOption = q.options.find(opt => opt.option_id === draggedImageId);
                if (selectedOption) {
                    dropContent.innerHTML = '';
                    const img = createDummyImage(selectedOption.src, selectedOption.label, true);
                    dropContent.appendChild(img);

                    currentDroppedId = draggedImageId;

                    const sourceElement = document.querySelector(`.image-option[data-id="${draggedImageId}"]`);
                    if (sourceElement) {
                        sourceElement.style.visibility = 'hidden';
                        sourceElement.setAttribute('data-is-dropped', 'true');
                    }
                }
            }
            draggedImageId = null;
        }

        // --- Click Select Image Handler (Q3) ---
        function handleImageClick(id) {
            const allOptions = document.querySelectorAll('.select-option');
            allOptions.forEach(opt => {
                opt.classList.remove('selected');
                opt.classList.remove('correct', 'wrong');
                opt.querySelector('.feedback-mark').innerHTML = '';
            });

            // const selectedOption = document.querySelector(`.select-option[data-id="${id}"]`);
            const selectedOption = document.querySelector(`.select-option[data-option-id="${id}"]`);
            if (selectedOption) {
                selectedOption.classList.add('selected');
                selectedOptionId = id;
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
        function dropText(e) {
            e.preventDefault();
            const target = e.currentTarget;

            // Only allow dropping if the box is empty
            if ((target.className.includes("drop-box") || target.className.includes("match-drop-box")) && target.childElementCount === 0) {
                if (draggedItem) {
                    const originalText = draggedItem.textContent;
                    const originalId = draggedItem.getAttribute('data-id');

                    const isFromInitialOptions = draggedItem.parentNode &&
                        (draggedItem.parentNode.className.includes('options-container') ||
                            draggedItem.parentNode.className.includes('drag-match-options'));

                    if (isFromInitialOptions) {
                        draggedItem.style.visibility = 'hidden';
                    } else {
                        // Dragged from another box, so remove it from there
                        draggedItem.remove();
                    }

                    const newBlock = document.createElement('span');
                    newBlock.textContent = originalText;
                    newBlock.className = 'drag-item';
                    newBlock.draggable = true;

                    if (originalId) {
                        newBlock.setAttribute('data-id', originalId);

                        // ⭐ IMPORTANT: Set dataset on drop box too
                        target.dataset.id = originalId;
                    }

                    target.appendChild(newBlock);
                    addDragEvents(target);
                    draggedItem = null;
                }
            }
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


        function loadQuestionTryAgain() {
            selectedOptionId = null;
            loadQuestion();
            // applyRandomStyles(true);
        }

        // ===== Submit Button Logic =====
        document.getElementById("submitBtn").onclick = function () {
            const q = questions[currentQ];
            const feedbackMsg = document.getElementById("feedbackMessage");
            const submitBtn = document.getElementById("submitBtn");
            feedbackMsg.innerHTML = "";

            let isCorrect = false;
            let requiredCheck = false;
            let type = q.type;


            // --- Click-to-Select Image Logic (Q3) ---
            const sId = selectedOptionId;
            if (!sId) { showCustomAlert("Please select an option first. 🧐"); return; }
            requiredCheck = true;
            isCorrect = (sId === q.correct_answer);

            const elementClass = '.select-option';
            // const selectedElement = document.querySelector(`${elementClass}[data-id="${sId}"]`);
            const selectedElement = document.querySelector(`${elementClass}[data-option-id="${sId}"]`);
            document.querySelectorAll(elementClass).forEach(opt => {
                opt.onclick = null;
                opt.classList.remove('correct', 'wrong', 'selected');
                opt.querySelector('.feedback-mark').innerHTML = '';
            });

            // 🔊 play sound
            // const sound = new Audio(
            //     isCorrect ? "sound/rightanswersound.mp3" : "sound/wronganswersound.mp3"
            // );
            // sound.play();
            if (isCorrect) {
                const sound = new Audio("sound/rightanswersound.mp3");
                sound.volume = 0.1;
                sound.play();
            }

            if (isCorrect) {
                selectedElement.classList.add('correct');
                selectedElement.querySelector('.feedback-mark').innerHTML = `<img src="images/372103860_CHECK_MARK_400px.gif" class="feedback-icon" alt="Correct" >`;
            } else {
                selectedElement.classList.add('wrong');
                selectedElement.querySelector('.feedback-mark').innerHTML = `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif" class="feedback-icon" alt="Wrong" >`;
            }

            if (!isCorrect) {
                submitBtn.disabled = true;
                document.querySelectorAll(elementClass).forEach(opt => {
                    const optId = opt.getAttribute('data-id');
                    opt.onclick = function () { handleImageClick(optId); };
                });
            }



            if (requiredCheck) {
                if (isCorrect) {
                    feedbackMsg.style.color = '#28a745';
                    feedbackMsg.innerHTML = '<button class="next-btn" onclick="nextQuestion()">Next Practice »</button>';
                    submitBtn.disabled = true;
                } else {
                    feedbackMsg.style.color = '#e64a19';
                    feedbackMsg.innerHTML = `<button class="try-again-btn" onclick="loadQuestionTryAgain()">Try Again ↻</button>`;



                    submitBtn.disabled = true;

                }
            }




            // Final result logic (Buttons)

        }

        function nextQuestion() {
            currentQ++;

            if (currentQ >= questions.length) {

                showCustomAlert("🎉 Practice Completed!", 2000, function () {
                    window.location.href = "lesson.php?lesson_id=<?php echo $lesson_id; ?>";
                });

                return;
            }

            loadQuestion();
        }



        // ===== Initialize =====
        // loadQuestion();
    </script>
</body>

</html>