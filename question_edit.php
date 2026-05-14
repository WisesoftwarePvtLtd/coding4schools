<?php
session_start();
include 'header.php';
include "config.php";
$userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';
$question_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$userType = $_SESSION['LoggedInUserType'] ?? '';


if ($question_id == 0) {
  die("Invalid question ID");
}

// Fetch main question
$q = $conn->query("SELECT * FROM questions WHERE question_id = $question_id");
if (!$q || $q->num_rows == 0) {
  die("Question not found");
}
$question = $q->fetch_assoc();

$options = [];

// IMAGE-SELECT + CLICK-SELECT

$res = $conn->query("SELECT * FROM options WHERE question_id = $question_id ");
while ($row = $res->fetch_assoc())
  $options[] = $row;
?>
<!DOCTYPE html>
<html>

<head>
  <title>Edit Question</title>
  <style>
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
  </style>

  <script>
    function startEditQuestionSubmit() {

      // STEP 1: Basic validation
      if (!validateBasicFields()) return;

      // 🔥 Agar user ne "Add Anyway" choose kiya hai
      if (document.getElementById("allow_duplicate").value == "1") {
        finalSubmit();   // ❌ duplicate check skip
        return;
      }

      // STEP 2: Duplicate check
      checkEditQuestionDuplicate();
    }
  </script>
  <script>
    function validateBasicFields() {

      const course = courseSelect.value;
      const lesson = lessonSelect.value;

      const questionText = document.getElementById("question_text").value.trim();

      if (!course) {
        showMessage("Please select a course", "error");
        return false;
      }

      if (!lesson) {
        showMessage("Please select a Lesson", "error");
        return false;
      }

      if (!questionText) {
        showMessage("Please enter Question Text", "error");
        return false;
      }

      return true;
    }
  </script>
  <script>
    function checkEditQuestionDuplicate() {
      const text = document.getElementById("question_text").value.trim();

      fetch('check_question_duplicate.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          question_text: text,
          course_id: document.getElementById('courseSelect').value,
          lesson_id: document.getElementById('lessonSelect').value,
          question_id: <?= isset($question['question_id']) ? (int) $question['question_id'] : 0 ?>
        })
      })
        .then(res => res.json())
        .then(data => {

          if (data.duplicate) {

            const modal = new bootstrap.Modal(
              document.getElementById('duplicateEditQuestionModal')
            );
            modal.show();

            // ❌ CHANGE QUESTION
            document.getElementById('changeTitleBtneq').onclick = () => {
              question_text.value = "";
              question_text.focus();
              allow_duplicate.value = 0;
              modal.hide();
            };

            // ✅ ADD ANYWAY
            document.getElementById('addAnywayBtneq').onclick = () => {
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

       
          <h3 class="fw-bolder mt-3">Edit Question</h3>



          <form action="question_update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="question_id" value="<?= $question_id ?>">
            <input type="hidden" name="school_id" value="<?= ($userType == SITEADMIN) ? 0 : $userSchoolId ?>">


            <!-- course & Lesson -->
            <div class="mb-3">
              <label class="fw-semibold">course <span class="text-danger">*</span></label>
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
                      SELECT DISTINCT c.course_id, c.course_title
                      FROM section_courses sc
                      JOIN courses c ON c.course_id = sc.course_id
                      JOIN grades g ON g.grade_id = sc.grade_id
                      WHERE g.school_id = ?
                      ORDER BY c.course_title ASC
                  ");

                $stmt->bind_param("i", $userSchoolId);
                }
                $stmt->execute();
                $result = $stmt->get_result();

                while ($bk = $result->fetch_assoc()) {
                  $selected = ($bk['course_id'] == $question['course_id']) ? "selected" : "";
                  echo "<option value='{$bk['course_id']}' $selected>" . htmlspecialchars($bk['course_title']) . "</option>";
                }

                $stmt->close();
                ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="fw-semibold">Select Lesson <span class="text-danger">*</span></label>
              <select name="lesson_id" class="form-control" id="lessonSelect">
                <option value="">Select Lesson</option>
              </select>

            </div>
            <input type="hidden" id="allow_duplicate" name="allow_duplicate" value="0">


            <!-- Question Text -->
            <div class="mb-3">
              <label>Question Text <span class="text-danger">*</span></label>
              <textarea type="text" name="question_text" id="question_text" class="form-control"
                rows="5"><?= htmlspecialchars($question['question_text']) ?></textarea>
            </div>
            <div class="mb-3">
              <label>Question Image</label>

              <!-- FLAG : EXISTING IMAGE -->
              <input type="hidden" id="existingMainImage" value="<?= !empty($question['main_image']) ? 1 : 0 ?>">

              <!-- FILE INPUT (HIDDEN) -->
              <input type="file" name="mainImage" id="mainImage" hidden accept="image/*"
                onchange="previewImage(this,'mainImagePreview')">

              <!-- IMAGE UPLOAD PLACEHOLDER -->
              <div class="image-upload-box mb-3" onclick="document.getElementById('mainImage').click();">

                <img id="mainImagePreview" src="<?= !empty($question['main_image'])
                  ? $question['main_image']
                  : 'images/systemimages/placeholder-image.png' ?>" alt="Main Image">
                <span class="remove-image" onclick="removeProblemImage(event, <?= $question_id ?>)">
                  <i class="fas fa-times"></i>
                </span>
              </div>
            </div>

            <!-- Correct Answer Hidden -->
            <input type="hidden" name="correct_answer" id="correct_answer"
              value="<?= htmlspecialchars($question['correct_answer']) ?>">


            <div id="optionsSection" class="question-section" style="<?= count($options) > 0 ? '' : 'display:none;' ?>">
              <h5>Options</h5>
              <div id="options">
                <?php foreach ($options as $i => $opt): ?>
                  <div class="option border p-3 mb-2 rounded" style="display: flex; gap: 23px;">
                    <div class="form-check float-end">

                      <input class="form-check-input correct-radio" type="radio" name="correct_option"
                        value="<?= $opt['id'] ?>" <?= ($opt['is_correct'] == 1) ? 'checked' : '' ?> style="top: 7px;">
                      <label class="form-check-label">Correct</label>
                    </div>
                    <input type="hidden" name="options[<?= $i ?>][id]" value="<?= htmlspecialchars($opt['id']) ?>">
                    <input type="text" name="options[<?= $i ?>][label]" placeholder="Option text"
                      class="form-control mb-2" value="<?= htmlspecialchars($opt['label']) ?>">



                    <input type="hidden" name="options[<?= $i ?>][existingImage]"
                      value="<?= !empty($opt['image']) ? 1 : 0 ?>">
                    <input type="hidden" name="options[<?= $i ?>][removeImage]" id="removeImage<?= $i ?>" value="0">

                    <!-- FILE INPUT (HIDDEN) -->
                    <input type="file" name="options[<?= $i ?>][image]" id="optionImage<?= $i ?>" hidden accept="image/*"
                      onchange="previewImage(this,'optionImagePreview<?= $i ?>')">

                    <!-- IMAGE PLACEHOLDER BOX -->
                    <div class="image-upload-box mb-2" onclick="document.getElementById('optionImage<?= $i ?>').click();">

                      <img id="optionImagePreview<?= $i ?>" src="<?= !empty($opt['image'])
                          ? $opt['image']
                          : 'images/systemimages/placeholder-image.png' ?>" alt="Option Image">
                      <span class="remove-image"
                        onclick="removeOptionImage(event,<?= $opt['id'] ?>,'optionImagePreview<?= $i ?>')">
                        <i class="fas fa-times"></i>
                      </span>
                    </div>


                    <!-- Delete -->
                    <i class="btn btn-danger remove-option square-cross" style="cursor:pointer;" title="Remove"
                      onclick="openDeleteModal('delete_question_option.php?id=<?= htmlspecialchars($opt['id']) ?>&type=<?= $question['question_type'] ?>&question_id=<?= $question['question_id'] ?>', 'Are you sure you want to remove this option?')"><i
                        class="fas fa-times text-white"></i>
                    </i>
                  </div>
                <?php endforeach; ?>
              </div>

            </div>
            <div class="text-end">
              <button type="button" id="addOptionBtn" class="btn btn-secondary mb-3" onclick="addOption()">
                <i class="fas fa-plus"></i> Add Another Option
              </button>
            </div>




            <div class="text-end mt-4">
              <a href="manage_question_bank.php" class="btn btn-primary mt-3">Back To
                Manage Question Bank</a>
              <button type="button" class="btn btn-primary mt-3" onclick="return startEditQuestionSubmit()">Update
                Question</button>
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

    function removeProblemImage(e, questionId) {

      e.stopPropagation();

      fetch("question_image_delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + questionId
      })
        .then(res => res.text())
        .then(res => {

          res = res.trim();

          if (res === "success") {

            document.getElementById("mainImagePreview").src =
              "images/systemimages/placeholder-image.png";

            showMessage("Image removed successfully", "success");

          } else {

            showMessage("Failed to remove image", "danger");

          }

        });

    }

    function removeOptionImage(e, optionId, previewId) {

      e.stopPropagation();

      fetch("option_image_delete.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + optionId
      })
        .then(res => res.text())
        .then(res => {

          res = res.trim();

          if (res === "success") {

            document.getElementById(previewId).src =
              "images/systemimages/placeholder-image.png";

            showMessage("Image removed successfully", "success");

          } else {

            showMessage("Failed to remove image", "danger");

          }

        });

    }

  </script>
  <script>
    function removeBlock(el) {
      el.closest(".border").remove();
    }
  </script>
  <script>

    let optionIndex = <?= count($options) ?>;

    function addOption() {

      const optionsDiv = document.getElementById("options");
      const section = document.getElementById("optionsSection");

      section.style.display = "block"; // 🔥 important

      const div = document.createElement("div");
      div.className = "option border p-3 mb-2 rounded";

      div.innerHTML = `
    <div class="row align-items-center g-3">

      <div class="form-check float-end">
        <input class="form-check-input correct-radio"
               type="radio"
               name="correct_option"
               value="${optionIndex}"
               onchange="setCorrectAnswer(this)" style="left:24px;bottom:9px;">
               <label class="form-check-label">Correct</label>
      </div>

      <div class="col">
        <input type="text"
               name="options[${optionIndex}][label]"
               placeholder="Option text"
               class="form-control">
      </div>

      <div class="col-auto">
        <input type="file"
               name="options[${optionIndex}][image]"
               id="optionImage${optionIndex}"
               hidden
               accept="image/*"
               onchange="previewImage(this,'optionImagePreview${optionIndex}')">

        <div class="image-upload-box"
             onclick="document.getElementById('optionImage${optionIndex}').click();">
          <img id="optionImagePreview${optionIndex}"
               src="images/systemimages/placeholder-image.png">
               <span class="remove-image"
        onclick="removeOptionImage(event,'optionImage${optionIndex}','optionImagePreview${optionIndex}')">
    <i class="fas fa-times"></i>
  </span>
        </div>
      </div>

      <div class="col-auto">
        <button type="button"
                class="btn btn-danger square-cross"
                onclick="this.closest('.option').remove()">
          <i class="fas fa-times text-white"></i>
        </button>
      </div>

    </div>
  `;

      optionsDiv.appendChild(div);
      optionIndex++;
    }



    function setCorrectAnswer(radio) {
      document.getElementById("correct_answer").value = radio.value;
    }


    document.getElementById("courseSelect").value = "<?php echo $question['course_id']; ?>";
    loadLessons("<?php echo $question['lesson_id']; ?>");

    function loadLessons(selectedLessonId = "") {
      let courseId = document.getElementById("courseSelect").value;

      if (courseId === "") {
        document.getElementById("lessonSelect").innerHTML =
          '<option value="">Select Lesson</option>';
        return;
      }

      fetch("lesson_get.php?course_id=" + courseId)
        .then(response => response.json())
        .then(data => {
          let lessonSelect = document.getElementById("lessonSelect");
          lessonSelect.innerHTML = '<option value="">Select Lesson</option>';

          data.forEach(item => {
            let selected =
              item.lesson_id == selectedLessonId ? "selected" : "";

            lessonSelect.innerHTML +=
              `<option value="${item.lesson_id}" ${selected}>
            ${item.lesson_title}
           </option>`;
          });
        })
        .catch(error => console.error("Error loading lessons:", error));
    }

  </script>
  <script>
    let deletedOptions = [];

    function removeOption(btn) {

      const optionId = btn.getAttribute("data-id");
      const hiddenInput = document.getElementById("deleted_options");

      if (optionId && hiddenInput) {
        deletedOptions.push(optionId);
        hiddenInput.value = deletedOptions.join(",");
      }

      btn.closest(".option").remove();
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

  <div class="modal fade" id="duplicateEditQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h3 class="modal-title">Duplicate Question</h3>
          <button type="button" data-bs-dismiss="modal" class="closeicon">
            <i class="fas fa-times fs-4"></i>
          </button>
        </div>

        <div class="modal-body">
          <p>
            Question already exists.<br>
            Do you want to add anyway?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="changeTitleBtneq">
            Change Title
          </button>
          <button type="button" class="btn btn-primary" id="addAnywayBtneq">
            Add Anyway
          </button>
        </div>
      </div>
    </div>
  </div>


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


</body>

</html>