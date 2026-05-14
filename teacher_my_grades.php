<?php include 'header.php'; 
$userType = $_SESSION['LoggedInUserType'] ?? '';
  
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Books </title>
</head>
<style>
    /* ===============================
   GRADES SECTION
================================ */

.grades-wrapper {
    padding: 30px;
}

.grades-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 30px;
    color: #222;
}

/* Grid layout */
.grades-grid {
    display: grid;
    grid-template-columns: repeat(4, 200px); /* 4 blocks per row */
    row-gap: 35px; /* SAME gap for row & column */
    column-gap: 20px;
    justify-content: start;
}

/* Card base – SHARP corners */
.grade-card {
    width: 160px;
    height: 140px;
    background: #1db2f2;
    border-radius: 0; /* SHARP */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-decoration: none;
    color: #fff;
    position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

/* Rounded top bump (like image) */
.grade-card::before {
    content: "";
    position: absolute;
    top: -18px;
    width: 60%;
    height: 36px;
    background: inherit;
    border-radius: 50px 50px 0 0; /* ROUND bump */
}

/* Icon */
.grade-card i {
    font-size: 32px;
    margin-bottom: 10px;
}

/* Text */
.grade-card span {
    font-size: 16px;
    text-transform: lowercase;
}

/* Colors */
.grade-card.blue {
    background: #1db2f2;
}

.grade-card.orange {
    background: #ff5a2c;
}

/* Hover */
.grade-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
}

/* ===============================
   RESPONSIVE
================================ */

@media (max-width: 1200px) {
    .grades-grid {
        grid-template-columns: repeat(3, 200px);
    }
}

@media (max-width: 900px) {
    .grades-grid {
        grid-template-columns: repeat(2, 200px);
    }
}

@media (max-width: 480px) {
    .grades-grid {
        grid-template-columns: 200px;
        justify-content: center;
    }
}


    
    

</style>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>
            <div class="grades-wrapper">
    <h2 class="grades-title">My Grades</h2>

    <div class="grades-grid">
        <a href="#" class="grade-card blue">
            <i class="fas fa-users"></i>
            <span>grade 1</span>
        </a>

        <a href="#" class="grade-card orange">
            <i class="fas fa-users"></i>
            <span>grade 2</span>
        </a>

        <a href="#" class="grade-card blue">
            <i class="fas fa-users"></i>
            <span>grade 3</span>
        </a>

        <a href="#" class="grade-card orange">
            <i class="fas fa-users"></i>
            <span>grade 4</span>
        </a>

        <a href="#" class="grade-card blue">
            <i class="fas fa-users"></i>
            <span>grade 5</span>
        </a>

        <a href="#" class="grade-card orange">
            <i class="fas fa-users"></i>
            <span>grade 6</span>
        </a>

        <a href="#" class="grade-card blue">
            <i class="fas fa-users"></i>
            <span>grade 7</span>
        </a>

        <a href="#" class="grade-card orange">
            <i class="fas fa-users"></i>
            <span>grade 8</span>
        </a>
    </div>
</div>




                
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            const modalBook = new bootstrap.Modal(document.getElementById('bookModal'));

            function loadBooks(search = "") {
                $("#bookList").load("course_fetch.php?search=" + encodeURIComponent(search));
            }

            // Page load par sab books
            loadBooks();

            // Search Click
            $("#searchBtn").on("click", function () {
                let keyword = $("#searchBook").val().trim();
                loadBooks(keyword);
            });

            // Clear Click
            $("#clearBtn").on("click", function () {
                $("#searchBook").val("");
                loadBooks();
            });



            // OPEN ADD BOOK MODAL
            document.getElementById('addBookBtn').addEventListener('click', function () {
                $(".book-modal-title").text("Add Book");
                document.getElementById("bookTitle").value = "";
                document.getElementById("bookCover").value = "";
                // 🔥 FIX: Clear old preview
                document.getElementById("coverPreview").style.display = "none";
                document.getElementById("coverPreview").src = "";
                document.getElementById("saveBookBtn").setAttribute("data-mode", "add"); // MODE SET
                document.getElementById("saveBookBtn").innerText = "Save";
                modalBook.show();
            });


            // AJAX POST with FILE using fetch
            function ajaxPostFile(url, formData, callback) {
                fetch(url, {
                    method: "POST",
                    body: formData
                })
                    .then(res => res.text())
                    .then(data => callback(data.trim()));
            }

            // INSERT (file supported)
            function insertDataFile(apiUrl, formData, callbackMsg = "Book Inserted Successfully!", type = "success") {
                ajaxPostFile(apiUrl, formData, function (res) {
                    res = res.trim();

                    if (res === "duplicate") {
                        showMessage("Book already exists!", "error");
                        return;
                    }
                    if (res === "success") {
                        showMessage(callbackMsg, type);

                        modalBook.hide();
                        loadBooks();
                    } else {
                        showMessage("Error Occurred!", "error");
                    }
                });
            }

            // UPDATE (file supported)
            function updateDataFile(apiUrl, formData, callbackMsg = "Book Updated Successfully!", type = "success") {
                ajaxPostFile(apiUrl, formData, function (res) {
                    res = res.trim();

                    if (res === "duplicate") {
                        showMessage("Book already exists!", "error");
                        return;
                    }
                    if (res === "success") {
                        showMessage(callbackMsg, type);

                        modalBook.hide();
                        loadBooks();
                    } else {
                        showMessage("Error Occurred!", "error");
                    }
                });
            }


            document.getElementById('saveBookBtn').addEventListener('click', function () {

                let mode = this.getAttribute("data-mode");
                let id = this.getAttribute("data-id");

                let title = document.getElementById("bookTitle").value;
                let coverFile = document.getElementById("bookCover").files[0];
                let previewVisible = $("#coverPreview").is(":visible");

                if (title === "") {
                    showMessage("Enter Book Title", "error");
                    return;
                }

                if (!coverFile && !previewVisible) {
                    showMessage("Please upload a book cover", "error");
                    return;
                }


                let formData = new FormData();
                formData.append("title", title);

                if (coverFile) {
                    formData.append("cover", coverFile);
                }

                if (mode === "add") {
                    // ADD with file
                    insertDataFile("course_add.php", formData, "Book Added Successfully!", "success");
                } else {
                    // UPDATE with file
                    formData.append("id", id);
                    updateDataFile("course_update.php", formData, "Book Updated Successfully!", "success");
                }

            });



            // EDIT BOOK
            function editBook(id, title, cover) {
                document.getElementById("bookTitle").value = title;
                document.getElementById("bookCover").value = ""; // keep empty
                $(".book-modal-title").text("Edit Book");
                // Show existing cover preview
                if (cover) {
                    document.getElementById("coverPreview").src =
                        "uploads/books/Book-" + id + "/" + cover;

                    document.getElementById("coverPreview").style.display = "block";
                } else {
                    document.getElementById("coverPreview").style.display = "none";
                }

                document.getElementById("saveBookBtn").setAttribute("data-mode", "update");
                document.getElementById("saveBookBtn").setAttribute("data-id", id);
                document.getElementById("saveBookBtn").innerText = "Update";
                modalBook.show();
            }




            // DELETE BOOK

            function deleteBook(id) {
                openDeletePopup(id, "course_delete.php", "Book Deleted Successfully!");
            }

            // DELETE (Confirmation + Success Msg)

            let deleteID = null;
            let deleteUrl = null;
            let deleteSuccessMsg = "";

            // Open Delete Modal
            function openDeletePopup(id, apiUrl, msg) {
                deleteID = id;
                deleteUrl = apiUrl;
                deleteSuccessMsg = msg;

                let modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                modal.show();
            }
            function confirmDelete() {

                ajaxPost(deleteUrl, { id: deleteID }, function (res) {

                    if (res.trim() === "success") {
                        showMessage(deleteSuccessMsg, "success");
                        loadBooks();
                    } else {
                        showMessage("Error deleting! " + res, "error");
                    }

                });

                bootstrap.Modal.getInstance(
                    document.getElementById('deleteConfirmModal')
                ).hide();
            }
        </script>





</body>

</html>