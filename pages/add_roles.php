<?php

require_once '../config.php';


/* -----------------------------------------
   🔒 SESSION PROTECTION
------------------------------------------ */

// BLOCK access if session not set
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// PREVENT browser back/forward caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

/* -----------------------------------------
   🔚 END OF PROTECTION
------------------------------------------ */
$pageTitle = "Create Role";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Fetch navigation items
$stmt = $pdo->query("SELECT nav_id, nav_name FROM navigation_items ORDER BY parent_id ASC, nav_id ASC");
$nav_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ✅ CSS Section -->
<link href="../assets/css/bootstrap.min.css?v=5.0" rel="stylesheet">
<link href="../assets/css/select2.min.css?v=5.0" rel="stylesheet">
<link href="../assets/css/style.css?v=5.0" rel="stylesheet"> <!-- ✅ Added Global Style -->

<div class="main-content">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">Create Role</span>
    </div>

    <form id="createRoleForm" method="POST">
        <div class="mb-3">
            <label for="txt_role" class="form-label">Role</label>
            <input type="text" class="form-control" id="txt_role" name="txt_role">
            <span class="text-danger" id="roleError"></span>
        </div>

        <div class="mb-3">
            <label for="txt_department" class="form-label">Department</label>
            <input type="text" class="form-control" id="txt_department" name="txt_department">
            <span class="text-danger" id="deptError"></span>
        </div>

        <div class="mb-3">
            <label for="sel_access" class="form-label">Access</label>
            <select class="form-select" id="sel_access" name="sel_access[]" multiple style="width:100%">
                <?php foreach ($nav_items as $nav): ?>
                    <option value="<?= htmlspecialchars($nav['nav_id']) ?>">
                        <?= htmlspecialchars($nav['nav_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="text-danger" id="accessError"></span>
        </div>

        <!-- ✅ Controls Section Card -->
        <div id="accessControlsContainer" class="access-controls" style="display:none;">
            <h5>Controls:</h5>
            <div id="accessControlsList"></div>
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
        </div>
    </form>
</div>

<!-- ✅ JS Section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js?v=5.0"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js?v=5.0"></script>
<script src="../assets/js/roles.js?v=<?php echo time(); ?>"></script>

<?php require_once '../includes/footer.php'; ?>
