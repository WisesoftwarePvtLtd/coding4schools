<?php
include 'config.php';

$name = $_REQUEST['name'] ?? '';
$url = $_REQUEST['url'] ?? '';

$where = "WHERE 1=1";

if ($name != '') {
    $name = mysqli_real_escape_string($conn, $name);
    $where .= " AND editor_name LIKE '%$name%'";
}
if ($url != '') {
    $url = mysqli_real_escape_string($conn, $url);
    $where .= " AND editor_url LIKE '%$url%'";
}

$sql = "SELECT * FROM editors $where ORDER BY editor_id DESC";
$res = mysqli_query($conn, $sql);

if (mysqli_num_rows($res) == 0) {
    echo "<div class='text-muted fw-semibold'>No editors found</div>";
    exit;
}

while ($row = mysqli_fetch_assoc($res)) {
    ?>
    <div class="grade-box mb-3">
        <div class="grade-row d-flex justify-content-between">
            <div>
                <?= htmlspecialchars($row['editor_name']) ?>
            </div>

            <div class="action-icons">

                <i class="fas fa-edit text-primary"
                    onclick="editEditor('<?= $row['editor_id'] ?>','<?= addslashes($row['editor_name']) ?>','<?= addslashes($row['editor_url']) ?>')">
                </i>
                <i class="fas fa-eye view text-primary" title="View"
                    onclick="viewEditor(<?= $row['editor_id'] ?>,'<?= htmlspecialchars($row['editor_name'], ENT_QUOTES) ?>','<?= htmlspecialchars($row['editor_url'], ENT_QUOTES) ?>')">
                </i>


                <i class="fas fa-trash text-danger" onclick="deleteEditor('<?= $row['editor_id'] ?>')"></i>
            </div>
        </div>
        </div>
    <?php } ?>