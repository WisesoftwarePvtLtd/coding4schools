<?php
session_start();
include 'header.php';
include 'config.php';
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';
$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$userType = $_SESSION['LoggedInUserType'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">


<head>
  <meta charset="UTF-8">
  <title>Manage Question Bank</title>
  <style>
    .image-upload-box {
      width: 180px;
      height: 140px;
      border: 2px dashed #ccc;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8f9fa;
    }

    .image-upload-box img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .square-cross {
      width: 42px;
      height: 42px;
      min-width: 42px;
      /* flex ke wajah se shrink na ho */
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      /* Bootstrap padding remove */
    }

    .modal-open {
      padding-right: 0 !important;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function startQuestionSubmit() {

      // STEP 1: Basic validation
      if (!validateBasicFields()) return;

      // 🔥 Agar user ne "Add Anyway" choose kiya hai
      if (document.getElementById("allow_duplicate").value == "1") {
        finalSubmit();   // ❌ duplicate check skip
        return;
      }

      // STEP 2: Duplicate check
      checkQuestionDuplicate();
    }
  </script>
  <script>
    function validateBasicFields() {

      const course = courseSelect.value;
      const lesson = lessonSelect.value;

      const question = question_text.value.trim();

      if (!course) {
        showMessage("Please select course", "error");
        return false;
      }

      if (!lesson) {
        showMessage("Please select Lesson", "error");
        return false;
      }


      if (!question) {
        showMessage("Please enter Question Text", "error");
        return false;
      }

      return true;
    }
  </script>

  <script>
    function checkQuestionDuplicate() {

      fetch('check_question_duplicate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          question_text: question_text.value.trim(),
          course_id: courseSelect.value,
          lesson_id: lessonSelect.value
        })
      })
        .then(res => res.json())
        .then(data => {

          if (data.duplicate) {

            const modal = new bootstrap.Modal(
              document.getElementById('duplicateQuestionModal')
            );
            modal.show();

            // ❌ CHANGE QUESTION
            document.getElementById('changeTitleBtn').onclick = () => {
              question_text.value = "";
              question_text.focus();
              allow_duplicate.value = 0;
              modal.hide();
            };

            // ✅ ADD ANYWAY
            document.getElementById('addAnywayBtn').onclick = () => {
              allow_duplicate.value = 1;
              modal.hide();
              finalSubmit();
            };

          } else {
            allow_duplicate.value = 0;
            finalSubmit();
          }

        });
    }
  </script>



</head>

<body>

  <div class="container-fluid p-3" style="padding: 30px;">
    <div class="layout-row">

      <!-- Sidebar -->
      <div class="sidebar" id="sidebar">
        <?php include 'menus.php'; ?>
      </div>

      <!-- Main Content -->
      <div class="main-area text-dark">

        <!-- GLOBAL MESSAGE BOX -->
        <div id="globalMsg" class="global-msg"></div>

        
          <h3 class="fw-bolder mt-3">Add New Question</h3>



          <form action="question_save.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="school_id" value="<?= ($userType == SITEADMIN) ? 0 : $userSchoolId ?>">
            <!-- course -->
            <div class="mb-3">
              <label class="fw-semibold">Select course <span class="text-danger">*</span></label>
              <select name="course_id" class="form-control" id="courseSelect" onchange="loadLessons()">
                <option value="">Select course</option>

                <?php

                if ($userType == SITEADMIN) {

                  // ✅ Site Admin → ALL courses
                  $stmt = $conn->prepare("
                      SELECT course_id, course_title
                      FROM courses
                      ORDER BY course_title ASC
                  ");

                } else {
                  $stmt = $conn->prepare("
                      SELECT DISTINCT b.course_id, b.course_title
                      FROM section_courses sc
                      JOIN courses b ON b.course_id = sc.course_id
                      JOIN grades g ON g.grade_id = sc.grade_id
                      WHERE g.school_id = ?
                      ORDER BY b.course_title ASC
                  ");

                  $stmt->bind_param("i", $userSchoolId);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                while ($bk = $result->fetch_assoc()) {
                  echo "<option value='{$bk['course_id']}'>" . htmlspecialchars($bk['course_title']) . "</option>";
                }

                $stmt->close();
                ?>
              </select>

            </div>

            <!-- Lesson -->
            <div class="mb-3">
              <label class="fw-semibold">Select Lesson <span class="text-danger">*</span></label>
              <select name="lesson_id" class="form-control" id="lessonSelect">
                <option value="">Select Lesson</option>
              </select>
            </div>
            <input type="hidden" name="allow_duplicate" id="allow_duplicate" value="0">


            <!-- Question Text -->
            <div class="mb-3">
              <label>Question Text <span class="text-danger">*</span></label>
              <textarea name="question_text" id="question_text" class="form-control" rows="5"></textarea>
            </div>
            <div class="mb-3">
              <label>Question Image</label>

              <input type="file" name="mainImage" id="mainImage" hidden
                onchange="previewImage(this,'mainImagePreview')">

              <div class="image-upload-box" onclick="document.getElementById('mainImage').click();">
                <img id="mainImagePreview" src="images/systemimages/placeholder-image.png">
                <span class="remove-image" onclick="removeProblemImage(event)">
                  <i class="fas fa-times"></i>
                </span>
              </div>

            </div>

            <!-- Correct Answer Hidden (set by radio) -->
            <input type="hidden" name="correct_answer" id="correct_answer">
            <input type="hidden" name="user_id" id="user_id" value="<?= $user_id ?>">

            <!-- Image-select / click-select / drag-drop options -->
            <div id="optionsSection" class="question-section">
              <h5>Options</h5>
              <div id="options">
                <div class="option border p-3 mb-2 rounded" style="display: flex; gap: 23px;">
                  <div class="form-check float-end">
                    <input class="form-check-input correct-radio" type="radio" name="correct_option" value="0">
                    <label class="form-check-label">Correct</label>
                  </div>
                  <input type="text" name="options[0][label]" placeholder="Option text" class="form-control mb-2">
                  <!-- <input type="file" name="options[0][image]" class="form-control"> -->

                  <input type="file" name="options[0][image]" id="optionImage0" accept="image/*" hidden
                    onchange="previewImage(this,'previewImage0')">

                  <!-- IMAGE BOX -->
                  <div class="image-upload-box" onclick="document.getElementById('optionImage0').click();">
                    <img id="previewImage0" src="images/systemimages/placeholder-image.png">
                    <span class="remove-image" onclick="removeOptionImage(event,'optionImage0','previewImage0')">
                      <i class="fas fa-times"></i>
                    </span>
                  </div>

                </div>
              </div>
              <button type="button" id="addOptionBtn" class="btn btn-secondary  mb-3" onclick="addOption()"><i
                  class="fas fa-plus"></i>
                Add Another
                Option</button>
            </div>

            <div class="text-end mt-4">
              <a href="manage_question_bank.php" class="btn btn-primary mt-3">Back To
                Manage Question Bank</a>
              <button type="button" class="btn btn-primary mt-3" onclick="startQuestionSubmit()">Save Question</button>
            </div>
          </form>
        
      </div> <!-- Main Area -->

    </div>
  </div>

  <script>
    function previewImage(input, previewId) {
      const preview = document.getElementById(previewId);

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function removeProblemImage(e) {
      e.stopPropagation(); // ✅ upload click stop

      document.getElementById("mainImagePreview").src = "images/systemimages/placeholder-image.png";
      document.getElementById("mainImage").value = "";

      // ❌ icon hide again
      document
        .querySelector(".image-upload-box")
        .classList.remove("mainImagePreview");
    }

    function removeOptionImage(e, inputId, previewId) {

      e.stopPropagation(); // upload click stop

      document.getElementById(previewId).src =
        "images/systemimages/placeholder-image.png";

      document.getElementById(inputId).value = "";
    }
  </script>

  <script>
    function removeBlock(el) {
      el.closest(".border").remove();
    }
  </script>

  <!-- ✅ MODAL ALWAYS AT BODY END -->
  <!-- ✅ MODAL ALWAYS AT BODY END -->
  <div class="modal fade" id="duplicateQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Duplicate Question</h5>
          <button type="button" data-bs-dismiss="modal" class="closeicon">
            <i class="fas fa-times fs-4"></i>
          </button>
        </div>

        <div class="modal-body">
          <p>
            Question already exists.<br>
            Do you want to add anyway?
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="changeTitleBtn">
            Change Title
          </button>
          <button type="button" class="btn btn-primary" id="addAnywayBtn">
            Add Anyway
          </button>
        </div>

      </div>
    </div>
  </div>

  <script>


    let optionIndex = 1; // pehla option already hai (0)

    function addOption() {

      const newOption = document.createElement("div");
      newOption.className = "option border p-3 mb-2 rounded";
      newOption.style.display = "flex";
      newOption.style.gap = "23px";

      newOption.innerHTML = `
      <div class="form-check float-end">
        <input class="form-check-input correct-radio"
               type="radio"
               name="correct_option"
               value="${optionIndex}">
        <label class="form-check-label">Correct</label>
      </div>

      <input type="text"
             name="options[${optionIndex}][label]"
             placeholder="Option text"
             class="form-control mb-2">

      <input type="file"
             name="options[${optionIndex}][image]"
             id="optionImage${optionIndex}"
             accept="image/*"
             hidden
             onchange="previewImage(this,'previewImage${optionIndex}')">

      <div class="image-upload-box"
           onclick="document.getElementById('optionImage${optionIndex}').click();">
        <img id="previewImage${optionIndex}"
             src="images/systemimages/placeholder-image.png">
              <span class="remove-image"
        onclick="removeOptionImage(event,'optionImage${optionIndex}','previewImage${optionIndex}')">
    <i class="fas fa-times"></i>
  </span>
      </div>

      <button type="button"
              class="btn btn-danger square-cross"
              onclick="removeBlock(this)">
        <i class="fas fa-times text-white"></i>
      </button>
    `;

      document.getElementById("options").appendChild(newOption);
      optionIndex++;
    }

    // Function to load lessons
    function loadLessons() {
      let courseId = document.getElementById("courseSelect").value;

      if (courseId === "") {
        document.getElementById("lessonSelect").innerHTML = '<option value="">Select Lesson</option>';
        return;
      }

      fetch("lesson_get.php?course_id=" + courseId)
        .then(response => response.json())
        .then(data => {
          let lessonSelect = document.getElementById("lessonSelect");
          lessonSelect.innerHTML = '<option value="">Select Lesson</option>';

          data.forEach(item => {
            lessonSelect.innerHTML += `<option value="${item.lesson_id}">${item.lesson_title}</option>`;
          });
        })
        .catch(error => console.error("Error loading lessons:", error));
    }

  </script>

  <script>
    function finalSubmit() {

      // 🔹 sirf image options validate honge
      if (!validateImageOptions()) {
        return; // ❌ validation fail → stop
      }

      // ✅ validation pass → submit
      document.querySelector("form").submit();
    }
  </script>


  <script>
    function validateImageOptions() {
      const options = document.querySelectorAll("#options .option");
      let correct = document.querySelector(".correct-radio:checked");

      if (options.length < 2) {
        showMessage("Please add at least 2 options", "error");
        return false;
      }

      if (!correct) {
        showMessage("Please select correct option", "error");
        return false;
      }


      return true;
    }

  </script>


  <script>
    document.addEventListener("DOMContentLoaded", function () {
      let msg = "<?php echo $_SESSION['msg'] ?? ''; ?>";
      let type = "<?php echo $_SESSION['transaction_status'] ?? 'success'; ?>";

      if (msg.trim() !== "") {
        showMessage(msg, type);
      }
    });
  </script>

  <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>