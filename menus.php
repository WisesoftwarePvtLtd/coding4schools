
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once 'standard_constants.php';



/* ==============================
   DEFINE CONSTANT SAFELY
   (Change value ONLY if DB role_id differs)
================================ */
if (!defined('SUPERADMIN')) {
    define('SUPERADMIN', 1); // <-- MUST match role_id in roles table
}

/* ==============================
   CURRENT PAGE
================================ */
$current = basename($_SERVER['PHP_SELF']);

/* ==============================
   AUTH CHECK
================================ */
if (!isset($_SESSION['LoggedInUserRoles'])) {
    return; // stop menu only, not page
}



/* ==============================
   NORMALIZE ROLES
================================ */
$roles = $_SESSION['LoggedInUserRoles'];

if (!is_array($roles)) {
    $roles = explode(',', $roles);
}

$roles = array_values(array_map('intval', $roles));

/* ==============================
   CHECK SUPERADMIN
================================ */
$isSuperAdmin = array_search(SUPERADMIN, $roles, true) !== false;

/* ==============================
   MENU QUERY
================================ */
if ($isSuperAdmin) {

    // SUPER ADMIN → ALL MENUS
    $sql = "SELECT menu, menu_icon, menu_link_path 
            FROM menus 
            ORDER BY menu_order ASC";

} else {

    // ROLE BASED MENUS
    $ids = implode(',', $roles);

    $sql = "
        SELECT DISTINCT m.menu, m.menu_icon, m.menu_link_path
        FROM menus m
        INNER JOIN role_menus rm ON rm.menu_id = m.menu_id
        WHERE rm.role_id IN ($ids)
        ORDER BY m.menu_order ASC
    ";
}

$menulist = $conn->query($sql);
?>

<!-- ==============================
     SIDEBAR TOGGLE
================================ -->
<a href="#" id="toggleSidebar" style="gap:24px;background:#1da1f2;color:#fff;">
    <i class="fas fa-chevron-left"></i>
    <span>Hide Menu</span>
</a>



<!-- ==============================
     DASHBOARD
================================ -->

<a href="dashboard.php"
   class="toggle-btn <?= ($current === 'dashboard.php') ? 'active' : ''; ?>"
   style="gap:16px">
    <i class="fas fa-tachometer-alt"></i>
    <span>Dashboard</span>
</a>

<!-- ==============================
     DYNAMIC MENUS
================================ -->
<?php if ($menulist && $menulist->num_rows > 0): ?>
    <?php while ($row = $menulist->fetch_assoc()): ?>
        <?php
        $link   = htmlspecialchars($row['menu_link_path']);
        $icon   = htmlspecialchars($row['menu_icon']);
        $name   = htmlspecialchars($row['menu']);
        $active = ($current === basename($link)) ? 'active' : '';
        ?>
        <a href="<?= $link ?>" class="toggle-btn <?= $active ?>" style="gap:16px">
            <i class="<?= $icon ?>"></i>
            <span><?= $name ?></span>
        </a>
    <?php endwhile; ?>
<?php endif; ?>

<!-- ==============================
     LOGOUT
================================ -->
<a href="logout.php"
   class="toggle-btn <?= ($current === 'logout.php') ? 'active' : ''; ?>"
   style="gap:16px">
    <i class="fas fa-sign-out-alt"></i>
    <span>Logout</span>
</a>

<script>
document.getElementById('toggleSidebar')?.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('sidebar')?.classList.toggle('collapsed');
});
</script>



