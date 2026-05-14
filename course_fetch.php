<?php
session_start();
include "standard_constants.php";
include "config.php";


$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$userType = $_SESSION['LoggedInUserType'] ?? '';
$loggedUserId = $_SESSION['LoggedInUserId'] ?? 0;

/* =========================
   FETCH COURSES
========================= */
if ($userType == STUDENT) {

    $sql = "
        SELECT DISTINCT 
            b.course_id,
            b.course_title,
            b.course_cover_page,
            b.course_details
        FROM courses b
        INNER JOIN section_courses sb ON sb.course_id = b.course_id
        INNER JOIN section_students ss ON ss.section_id = sb.section_id
        INNER JOIN students s ON s.student_id = ss.student_id
        WHERE s.user_id = ?
    ";

    if ($search !== "") {
        $sql .= " AND b.course_title LIKE ?";
        $stmt = $conn->prepare($sql);
        $like = "%{$search}%";
        $stmt->bind_param("is", $loggedUserId, $like);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $loggedUserId);
    }
} else if ($userType == TEACHER) {

    $sql = "
        SELECT DISTINCT 
            b.course_id,
            b.course_title,
            b.course_cover_page,
            b.course_details
        FROM courses b
        INNER JOIN section_courses sb ON sb.course_id = b.course_id
        INNER JOIN section_teachers ss ON ss.section_id = sb.section_id
        INNER JOIN teachers t ON t.teacher_id = ss.teacher_id
        WHERE t.user_id = ?
    ";

    if ($search !== "") {
        $sql .= " AND b.course_title LIKE ?";
        $stmt = $conn->prepare($sql);
        $like = "%{$search}%";
        $stmt->bind_param("is", $loggedUserId, $like);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $loggedUserId);
    }
} else {

    if ($search !== "") {
        $stmt = $conn->prepare("
            SELECT 
                course_id,
                course_title,
                course_cover_page,
                course_details
            FROM courses
            WHERE course_title LIKE ?
            ORDER BY course_sort_order ASC
        ");
        $like = "%{$search}%";
        $stmt->bind_param("s", $like);
    } else {
        $stmt = $conn->prepare("
            SELECT 
                course_id,
                course_title,
                course_cover_page,
                course_details
            FROM courses
           ORDER BY course_sort_order ASC
        ");
    }
}

$stmt->execute();
$query = $stmt->get_result();
$stmt->close();
?>




<?php if ($query->num_rows > 0): ?>

    <?php while ($row = $query->fetch_assoc()):
        $id = $row['course_id'];
        $title = $row['course_title'];
        $cover = $row['course_cover_page'];
        $details = $row['course_details'];

        $imagePath = "uploads/courses/course-$id/$cover";
        ?>
        <!-- <div class="col-md-3 mb-4">
            <div class="course-card">
                <div class="course-image">
                    <a href="manage-lesson.php?course_id=<?= $id ?>&course_name=<?= urlencode($title) ?>">

                        <?php if (!empty($cover) && file_exists($imagePath)) { ?>
                            <img src="<?= $imagePath ?>" alt="Course Image">
                        <?php } ?>
                    </a>

                    <div class="card-actions">
                        <?php if (userHasPermission(COURSE_EDIT)): ?>
                            <a href="edit_course.php?id=<?= $id ?>" onclick="event.stopPropagation()" title="Edit Course">
                                <i class="fas fa-edit text-primary"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (userHasPermission(COURSE_DELETE)): ?>
                            <a href="javascript:void(0)" class="delete" onclick="event.stopPropagation(); deleteCourse(<?= $id ?>)"
                                title="Delete Course">
                                <i class="fas fa-trash text-danger"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>



                <div class="course-body">
                    <h5><?= htmlspecialchars($title) ?></h5>
                    <p><?= htmlspecialchars(limitCharacters($details, 80)) ?></p>



                    <a href="manage-lesson.php?course_id=<?= $id ?>&course_name=<?= urlencode($title) ?>"
                        class="btn btn-primary w-100 mb-3">
                        Start Course
                    </a>

                    <a href="syllabus.php?course_id=<?= $id ?>&course_name=<?= urlencode($title) ?>"
                        class="btn btn-primary w-100">
                        View Syllabus
                    </a> 



                </div>
            </div>
        </div> -->
        <div class="col-md-4 mb-4">
            <div class="course-card">

                <div class="course-image">
                    <img src="<?= $imagePath ?>" alt="Course">

                    <!-- Badge -->
                    <span class="badge-label">New</span>

                    <!-- 3 dots -->
                    <div class="card-actions">
                        <?php if (userHasPermission(COURSE_EDIT)): ?>
                            <a href="edit_course.php?id=<?= $id ?>" onclick="event.stopPropagation()" title="Edit Course">
                                <i class="fas fa-edit text-primary"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (userHasPermission(COURSE_DELETE)): ?>
                            <a href="javascript:void(0)" class="delete" onclick="event.stopPropagation(); deleteCourse(<?= $id ?>)"
                                title="Delete Course">
                                <i class="fas fa-trash text-danger"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Floating Icon -->
                <div class="course-icon">
                    <i class="fas fa-code"></i>
                </div>

                <div class="course-body">
                    <h5><?= htmlspecialchars($title) ?></h5>
                    <div class="course-category">Programming</div>

                    <p><?= htmlspecialchars(limitCharacters($details, 80)) ?></p>

                    <a href="manage-lesson.php?course_id=<?= $id ?>&course_name=<?= urlencode($title) ?>" class="btn">
                        View Course →
                    </a>
                </div>

            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class='text-center ' style='margin-left: 44%;'>No courses found</div>
<?php endif; ?>


<?php
function userHasPermission($permissionId)
{
    return isset($_SESSION['UserPermissions'][$permissionId]);
}

function limitCharacters($text, $limit = 100)
{
    $text = trim(strip_tags($text)); // HTML remove

    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }

    return mb_substr($text, 0, $limit, 'UTF-8') . '...';
}

?>