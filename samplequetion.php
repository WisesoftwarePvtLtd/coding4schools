<!DOCTYPE html>
<html lang="en">
<?php include 'header.php'; ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Combined Chinese Quiz</title>
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
    <script>
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
            fetch("get_questions.php")
                .then(res => res.json())
                .then(data => {
                    questions = data;
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

        const bgImages = [
            'images/que-background-1.jpg',
            'images/que-background-2.jpg',
            'images/que-background-3.jpg',
            'images/que-background-4.jpg'
        ];

        const colors = ["#f77f7f", "#6aa9ff", "#6ad46a", "#f5b84f", "#9c7cf7"];

        function getRandomBg() {
            return bgImages[Math.floor(Math.random() * bgImages.length)];
        }

        function applyRandomStyles(isTryAgain = false) {
            quizCard.style.backgroundImage = `url(${getRandomBg()})`;
            quizCard.style.backgroundSize = "cover";
            quizCard.style.backgroundPosition = "center";

            if (isTryAgain) {
                const color = colors[Math.floor(Math.random() * colors.length)];
                document.querySelectorAll(".drag-item").forEach(el => el.style.background = color);
            }
        }


        document.addEventListener("DOMContentLoaded", () => {
            applyRandomStyles();
        });


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
            qText.innerHTML = `${q.question} ${q.subQuestion ? '<div class="sub-question">' + q.subQuestion + '</div>' : ''}`;


            if (q.type === "image-select") {
                // --- DRAG-DROP IMAGE SELECT UI (Q1) ---
                submitBtn.classList.add('green-submit-btn');
                // ... (Q1 UI generation logic) ...
                const grid = document.createElement('div');
                grid.className = 'image-grid';

                q.options.forEach(opt => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'image-option';
                    optionDiv.setAttribute('data-id', opt.id);
                    optionDiv.setAttribute('data-is-dropped', 'false');
                    optionDiv.draggable = true;
                    optionDiv.appendChild(createDummyImage(opt.src, opt.label));

                    optionDiv.addEventListener('dragstart', (e) => {
                        draggedImageId = opt.id;
                        e.dataTransfer.setData('text/plain', opt.id);
                        e.dataTransfer.setDragImage(optionDiv.querySelector('img'), 40, 40);
                    });
                    grid.appendChild(optionDiv);
                });
                matchWrapper.appendChild(grid);
                const placeholder = document.createElement('div');
                placeholder.className = 'single-placeholder';
                placeholder.ondragover = (e) => e.preventDefault();
                placeholder.ondrop = dropImage;
                placeholder.ondragenter = (e) => e.preventDefault();
                const feedbackMark = document.createElement('div');
                feedbackMark.className = 'feedback-mark';
                placeholder.appendChild(feedbackMark);
                const feedbackOverlay = document.createElement('div');
                feedbackOverlay.className = 'feedback-overlay';
                placeholder.appendChild(feedbackOverlay);
                const placeholderContent = document.createElement('div');
                placeholderContent.className = 'placeholder-content';
                placeholderContent.addEventListener('dragstart', (e) => {
                    if (currentDroppedId) {
                        e.dataTransfer.setData('text/plain', currentDroppedId);
                        draggedImageId = currentDroppedId;
                    }
                });
                placeholderContent.addEventListener('dragend', (e) => {
                    if (e.dataTransfer.dropEffect !== 'none' && currentDroppedId) {
                        const isDroppedBackToOption = e.target.closest('.image-option') || e.dataTransfer.dropEffect === 'move';
                        if (!isDroppedBackToOption) {
                            removeDroppedImage(true);
                        }
                    }
                });
                placeholder.appendChild(placeholderContent);
                matchWrapper.appendChild(placeholder);


            } else if (q.type === "click-select-image") {
                // --- CLICK-TO-SELECT IMAGE UI (Q3) ---
                submitBtn.classList.add('green-submit-btn');
                // ... (Q3 UI generation logic) ...
                const grid = document.createElement('div');
                grid.className = 'click-select-grid';

                q.options.forEach(opt => {
                    const optionDiv = document.createElement('div');
                    optionDiv.className = 'select-option';
                    optionDiv.setAttribute('data-id', opt.id);
                    optionDiv.setAttribute('data-label', opt.label);

                    optionDiv.appendChild(createDummyImage(opt.src, opt.label));

                    const feedbackMark = document.createElement('div');
                    feedbackMark.className = 'feedback-mark';
                    feedbackMark.innerHTML = '';
                    optionDiv.appendChild(feedbackMark);

                    optionDiv.onclick = function () { handleImageClick(opt.id); };
                    grid.appendChild(optionDiv);
                });

                matchWrapper.appendChild(grid);


            } else if (q.type === "drag-match-text-to-image") {

                const matchWrapper = document.getElementById("answerArea");
                matchWrapper.innerHTML = "";

                // LEFT SIDE = Text Items (Chinese Words)
                const optionsContainer = document.createElement('div');
                optionsContainer.className = 'drag-match-options';

                const draggableWords = shuffleArray(q.options.map(o => o.correctWord));

                draggableWords.forEach(word => {
                    const span = document.createElement("span");
                    span.textContent = word;
                    span.draggable = true;
                    span.className = "drag-item";
                    span.dataset.word = word;
                    optionsContainer.appendChild(span);
                });

                matchWrapper.appendChild(optionsContainer);

                // RIGHT SIDE = Image Drop Areas
                const dropGrid = document.createElement('div');
                dropGrid.className = 'drag-match-grid';

                const shuffledOptions = shuffleArray(q.options);

                shuffledOptions.forEach(item => {
                    const row = document.createElement('div');
                    row.className = 'match-row';
                    row.dataset.correct = item.correctWord;

                    const dropBox = document.createElement('div');
                    dropBox.className = 'match-drop-box';
                    dropBox.ondrop = dropText;
                    dropBox.ondragover = allowDrop;
                    const feedbackMark = document.createElement('div');
                    feedbackMark.className = 'feedback-mark';
                    row.appendChild(feedbackMark);
                    const imageBox = document.createElement('div');
                    imageBox.className = 'match-image-box';

                    const img = document.createElement("img");
                    img.src = item.image;
                    img.style.width = "99px";
                    img.style.height = "120px";

                    imageBox.appendChild(img);

                    row.appendChild(dropBox);
                    row.appendChild(imageBox);
                    dropGrid.appendChild(row);
                });

                matchWrapper.appendChild(dropGrid);
                addDragEvents(document.body);
            }
            else if (q.type === "order") {

                const optionsContainer = document.createElement('div');
                optionsContainer.className = 'options-container';

                q.options.forEach(opt => {
                    const span = document.createElement("span");
                    span.textContent = opt;
                    span.draggable = true;
                    span.className = "drag-item";
                    span.id = "opt-" + opt.replace(/\s/g, '_');

                    optionsContainer.appendChild(span);
                });
                matchWrapper.appendChild(optionsContainer);

                const dropContainer = document.createElement('div');
                dropContainer.className = 'drop-target-container';

                const numBlocks = q.options.length;

                for (let i = 0; i < numBlocks; i++) {
                    const dropBox = document.createElement('div');
                    dropBox.className = 'drop-box';
                    dropBox.ondrop = dropText;
                    dropBox.ondragover = allowDrop;
                    dropContainer.appendChild(dropBox);
                }

                matchWrapper.appendChild(dropContainer);

                const topFinalFB = document.createElement("div");
                topFinalFB.id = "order-final-feedback";
                topFinalFB.className = "order-final-feedback-top";
                matchWrapper.appendChild(topFinalFB);

                addDragEvents(document.body);
            }
            else if (q.type === "match-line") {
                // --- LINE MATCH UI (Q5 / Dynamic) ---
                const matchContainer = document.createElement('div');
                matchContainer.className = 'match-container';
                matchContainer.id = 'match-container';

                canvas = document.createElement('canvas');
                canvas.id = 'match-canvas';
                matchWrapper.prepend(canvas);
                ctx = canvas.getContext('2d');

                matchContainer.addEventListener('mousedown', startDrawing);
                matchContainer.addEventListener('mousemove', drawWhileMoving);
                matchContainer.addEventListener('mouseup', stopDrawing);
                matchContainer.addEventListener('mouseleave', cancelDrawing);

                setTimeout(() => {
                    sizeCanvas();
                    window.addEventListener('resize', sizeCanvas);
                }, 50);


                // --------------------------------------
                // ✅ LEFT COLUMN (Text or Image Auto)
                // --------------------------------------
                const leftCol = document.createElement('div');
                leftCol.className = 'match-column left-column';

                q.leftOptions.forEach(opt => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'match-item-wrapper';

                    const item = document.createElement('div');
                    item.className = 'match-item left';
                    item.setAttribute('data-value', opt.label);
                    item.setAttribute('data-matched', 'false');

                    // IF LEFT HAS IMAGE
                    if (opt.image) {
                        const img = document.createElement('img');
                        img.src = opt.image;
                        img.alt = opt.label;
                        img.className = 'match-item-image';
                        item.appendChild(img);
                    } else {
                        item.textContent = opt.label;
                    }

                    wrapper.appendChild(item);
                    leftCol.appendChild(wrapper);
                });
                matchContainer.appendChild(leftCol);


                // --------------------------------------
                // ✅ DOT GROUP (NO CHANGE)
                // --------------------------------------
                const dotGroup = document.createElement('div');
                dotGroup.className = 'dot-group';

                const leftDotCol = document.createElement('div');
                leftDotCol.className = 'dot-column left';
                q.leftOptions.forEach(opt => {
                    const dot = createDot('left', opt.label);
                    leftDotCol.appendChild(dot);
                });

                const rightDotCol = document.createElement('div');
                rightDotCol.className = 'dot-column right';
                const rightOptionsShuffled = shuffleArray([...q.rightOptions]);
                rightOptionsShuffled.forEach(opt => {
                    const dot = createDot('right', opt.label);
                    rightDotCol.appendChild(dot);
                });

                dotGroup.appendChild(leftDotCol);
                dotGroup.appendChild(rightDotCol);
                matchContainer.appendChild(dotGroup);


                // --------------------------------------
                // ✅ RIGHT COLUMN (Text or Image Auto)
                // --------------------------------------
                const rightCol = document.createElement('div');
                rightCol.className = 'match-column right-column';

                rightOptionsShuffled.forEach(opt => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'match-item-wrapper';

                    const item = document.createElement('div');
                    item.className = 'match-item right';
                    item.setAttribute('data-value', opt.label);
                    item.setAttribute('data-matched', 'false');

                    // IF RIGHT HAS IMAGE
                    if (opt.image) {
                        const img = document.createElement('img');
                        img.src = opt.image;
                        img.alt = opt.label;
                        img.className = 'match-item-image';
                        item.appendChild(img);
                    } else {
                        item.textContent = opt.label;
                    }

                    wrapper.appendChild(item);
                    rightCol.appendChild(wrapper);
                });
                matchContainer.appendChild(rightCol);


                // ✅ Append to Page
                matchWrapper.appendChild(matchContainer);

            } else if (q.type === "image-drag-drop-to-name") {
                // --- ⚡ UPDATED Q6: IMAGE IN QUESTION BOX ---
                submitBtn.classList.add('green-submit-btn');

                // 1. Construct the Question Text box content
                qText.innerHTML = ""; // Clear default content
                const questionContentWrapper = document.createElement('div');
                questionContentWrapper.className = 'question-image-layout';

                // Image Area (Left side of question box)
                const imageArea = document.createElement('div');
                imageArea.className = 'question-image-area';
                if (q.main_image) {
                    imageArea.appendChild(createDummyImage(q.main_image, 'Question Image'));
                }

                // Text Content (Right side of question box)
                const textContent = document.createElement('div');
                textContent.className = 'question-text-content';
                textContent.innerHTML = `${q.question} ${q.subQuestion ? '<div class="sub-question">' + q.subQuestion + '</div>' : ''}`;

                questionContentWrapper.appendChild(imageArea);
                questionContentWrapper.appendChild(textContent);
                qText.appendChild(questionContentWrapper);


                // 2. Construct the Answer Area content (Options and Drop Box only)

                // Options (Draggable Text)
                const optionsContainer = document.createElement('div');
                optionsContainer.className = 'options-container'; // Use general options-container for center alignment

                q.options.forEach(opt => {
                    const span = document.createElement("span");
                    span.textContent = opt.label;
                    span.draggable = true;
                    span.className = "drag-item";
                    span.id = "q6-opt-" + opt.id;
                    span.setAttribute('data-id', opt.id); // Store ID for checking

                    optionsContainer.appendChild(span);
                });

                // Drop Box Container
                const dropContainer = document.createElement('div');
                dropContainer.className = 'drop-target-container';
                dropContainer.style.marginTop = '40px';

                const dropBox = document.createElement('div');
                dropBox.className = 'drop-box drop-box-for-image-name';
                dropBox.ondrop = dropText;
                dropBox.ondragover = allowDrop;
                dropContainer.appendChild(dropBox);

                const feedback = document.createElement('div');
                feedback.className = "feedback-mark";
                dropContainer.appendChild(feedback);

                matchWrapper.appendChild(optionsContainer);
                matchWrapper.appendChild(dropContainer);

                // Add drag listeners
                addDragEvents(optionsContainer);
            }

            applyRandomStyles();
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
                const selectedOption = q.options.find(opt => opt.id === draggedImageId);
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

            const selectedOption = document.querySelector(`.select-option[data-id="${id}"]`);
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
                    // Inherit background color if it was set on the draggedItem
                    newBlock.style.background = draggedItem.style.background;
                    if (originalId) { newBlock.setAttribute('data-id', originalId); }

                    target.appendChild(newBlock);
                    addDragEvents(target);
                    draggedItem = null;
                }
            }
        }

        // --- Line Match Handlers (Q5) ---
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
            if (!dot || !dot.classList.contains('left-dot') || dot.getAttribute('data-matched') === 'true') return;
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
            startDotElement.style.boxShadow = '0 0 5px rgba(0,0,0,0.2)';
            const endDot = e.target.closest('.connection-dot');
            if (endDot && endDot.classList.contains('right-dot') && endDot.getAttribute('data-matched') !== 'true') {
                const leftVal = startDotElement.getAttribute('data-value');
                const rightVal = endDot.getAttribute('data-value');

                // Add match 
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

        function loadQuestionTryAgain() {
            selectedOptionId = null;
            loadQuestion();
            applyRandomStyles(true);
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

            if (type === "click-select-image") {
                // --- Click-to-Select Image Logic (Q3) ---
                const sId = selectedOptionId;
                if (!sId) { alert("Please select an option first. 🧐"); return; }
                requiredCheck = true;
                isCorrect = (sId === q.correct_answer);

                const elementClass = '.select-option';
                const selectedElement = document.querySelector(`${elementClass}[data-id="${sId}"]`);
                document.querySelectorAll(elementClass).forEach(opt => {
                    opt.onclick = null;
                    opt.classList.remove('correct', 'wrong', 'selected');
                    opt.querySelector('.feedback-mark').innerHTML = '';
                });

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

            } else if (q.type === "image-select") {
                // --- Drag-Drop Image Select Logic (Q1) ---
                const droppedId = currentDroppedId;
                const placeholder = document.querySelector('.single-placeholder');
                const feedbackMark = placeholder.querySelector('.feedback-mark');

                feedbackMark.innerHTML = ""; // clear old

                if (!droppedId) { alert("Please drag an image into the box first. 🧐"); return; }
                requiredCheck = true;
                isCorrect = (droppedId === q.correct_answer);



                if (isCorrect) {
                    feedbackMark.innerHTML =
                        `<img src="images/372103860_CHECK_MARK_400px.gif" alt="correct">`;

                } else {
                    feedbackMark.innerHTML =
                        `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif" alt="wrong">`;
                }

            } else if (q.type === "image-drag-drop-to-name") {
                // --- IMAGE DRAG-DROP TO NAME Logic (Q6) ---
                const dropBox = document.querySelector('.drop-box-for-image-name');
                const feedback = document.querySelector('.feedback-mark');
                let allFilled = true;
                let userAnswerId = "";
                feedback.innerHTML = ""; // Clear old GIF
                if (dropBox.childElementCount === 0) {
                    allFilled = false;
                } else {
                    const droppedItem = dropBox.querySelector('.drag-item');
                    userAnswerId = droppedItem.getAttribute('data-id');
                }

                if (!allFilled) { alert("Please drag the correct name into the box first. 🧐"); return; }

                requiredCheck = true;
                isCorrect = (userAnswerId === q.correct_answer);
                // SHOW CORRECT GIF
                if (isCorrect) {
                    feedback.innerHTML =
                        `<img src="images/372103860_CHECK_MARK_400px.gif">`;
                }
                // SHOW WRONG GIF
                else {
                    feedback.innerHTML =
                        `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif">`;
                }
                // Disable drag events on all drag items after check
                document.querySelectorAll('.drag-item').forEach(item => item.draggable = false);

            } else if (q.type === "drag-match-text-to-image") {

                const matchRows = document.querySelectorAll('.match-row');
                let allFilled = true;
                let allCorrect = true;
                let feedbackMark = "";

                matchRows.forEach(row => {

                    const dropBox = row.querySelector('.match-drop-box');
                    feedbackMark = row.querySelector('.feedback-mark');
                    // clear previous
                    feedbackMark.innerHTML = "";
                    if (!dropBox.firstElementChild || dropBox.firstElementChild.classList.contains('feedback-mark')) {
                        allFilled = false;
                        return;
                    }

                    const droppedWord = dropBox.firstElementChild.textContent.trim();
                    const correctWord = row.dataset.correct.trim(); // ✅ Match this key
                    if (droppedWord != correctWord) {
                        // show correct GIF
                        allCorrect = false;

                    }


                });

                if (allCorrect == true) {
                    // show correct GIF
                    feedbackMark.innerHTML =
                        `<img src="images/372103860_CHECK_MARK_400px.gif" alt="correct">`;
                } else {
                    // wrong GIF

                    feedbackMark.innerHTML =
                        `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif" alt="wrong">`;
                }


                if (!allFilled) {
                    alert("Please fill all the drop boxes first. 🧐");
                    return;
                }

                requiredCheck = true;
                isCorrect = allCorrect;
            }
            else if (q.type === "order") {

                const dropContainer = document.querySelector('.drop-target-container');
                let userAnswer = "";
                let allFilled = true;

                const dropBoxes = Array.from(dropContainer.children);
                userAnswer = dropBoxes.map(box => {
                    if (box.childElementCount === 0) { allFilled = false; return ""; }
                    return box.textContent.trim();
                }).join('');

                if (!allFilled) { alert("Please fill all the boxes first. 🧐"); return; }

                requiredCheck = true;
                isCorrect = (userAnswer === q.correct_answer);

                const finalFB = document.getElementById("order-final-feedback");

                finalFB.innerHTML = isCorrect
                    ? `<img src="images/372103860_CHECK_MARK_400px.gif" class="final-mark">`
                    : `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif" class="final-mark">`;

            }
            else if (q.type === "match-line") {
                // --- Line Match Logic (Q5) ---
                requiredCheck = true;
                let allCorrect = true;
                matchedPairs.forEach(pair => {
                    const leftVal = pair[0];
                    const rightVal = pair[1];
                    if (q.correctPairs[leftVal] !== rightVal) { allCorrect = false; }
                });
                isCorrect = (matchedPairs.length === q.leftOptions.length) && allCorrect;
                let matchContainer = document.querySelector(".match-container");
                let matchWrapper = document.getElementById("match-wrapper");

                let fb = document.getElementById("matchline-final-feedback");
                if (!fb) {
                    fb = document.createElement("div");
                    fb.id = "matchline-final-feedback";
                    fb.className = "final-feedback";

                    if (matchContainer) {
                        matchContainer.after(fb);   // ⭐ EXACTLY HERE
                    } else if (matchWrapper) {
                        matchWrapper.prepend(fb);
                    } else {
                        document.getElementById("submitBtn").after(fb);
                    }
                }

                fb.innerHTML = isCorrect
                    ? `<img src="images/372103860_CHECK_MARK_400px.gif">`
                    : `<img src="images/NoWatermarkCROSS_MARK_400pxTransparent.gif">`;
            }


            // Final result logic (Buttons)
            if (requiredCheck) {
                if (isCorrect) {
                    feedbackMsg.style.color = '#28a745';
                    feedbackMsg.innerHTML = '<button class="next-btn" onclick="nextQuestion()">Next Practice »</button>';
                    submitBtn.disabled = true;
                } else {
                    feedbackMsg.style.color = '#e64a19';
                    feedbackMsg.innerHTML = `<button class="try-again-btn" onclick="loadQuestionTryAgain()">Try Again ↻</button>`;


                    if (q.type === "click-select-image" || q.type === "image-drag-drop-to-name") {
                        submitBtn.disabled = true;
                    }
                }
            }
        }

        function nextQuestion() {
            currentQ++;
            if (currentQ >= questions.length) {
                alert("Quiz Completed! Resetting to start. 🥳");
                currentQ = 0;
            }
            loadQuestion();
        }

        // ===== Initialize =====
        loadQuestion();
    </script>
</body>

</html>