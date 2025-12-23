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


$pageTitle = "View Role";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Get role ID from URL
$role_id = $_GET['id'] ?? null;
if (!$role_id) {
    echo "<p class='text-danger'>Invalid Role ID.</p>";
    exit;
}

// Fetch role details
$stmt = $pdo->prepare("SELECT * FROM role_management WHERE r_id = ?");
$stmt->execute([$role_id]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    echo "<p class='text-danger'>Role not found.</p>";
    exit;
}

// --- Step 1: Decode controls JSON ---
$accessControls = [];
if (!empty($role['controls'])) {
    $accessControlsRaw = json_decode($role['controls'], true); // decode JSON to associative array
    if (is_array($accessControlsRaw)) {
        foreach ($accessControlsRaw as $nav_id => $permVal) {
            if (is_array($permVal)) {
                // Already array, e.g. ["view","edit"]
                $accessControls[$nav_id] = $permVal;
            } elseif (is_string($permVal)) {
                // String like "view,edit"
                $accessControls[$nav_id] = array_filter(array_map('trim', explode(',', $permVal)));
            } else {
                // Unexpected type
                $accessControls[$nav_id] = [];
            }
        }
    }
}


// --- Step 2: Fetch access module names ---
$accessModules = [];
if (!empty($role['access_navigations'])) {
    $accessIds = explode(',', $role['access_navigations']);
    $stmt2 = $pdo->prepare("SELECT nav_id, nav_name FROM navigation_items WHERE nav_id IN (" . implode(',', array_fill(0, count($accessIds), '?')) . ")");
    $stmt2->execute($accessIds);
    $accessModules = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR); // nav_id => nav_name
}

// --- Step 3: Combine module names + controls ---
$moduleControls = [];
foreach ($accessModules as $nav_id => $nav_name) {
    $perms = $accessControls[$nav_id] ?? [];
    $moduleControls[$nav_name] = $perms;
}
?>

<div class="main-content">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">View Role Management</span>
    </div>

    <div class="role-details">
        <div class="detail-row">
            <strong>Role:</strong>
            <span class="view-role"><?= htmlspecialchars($role['role']) ?></span>
        </div>

        <div class="detail-row">
            <strong>Department:</strong>
            <span class="view-dep"><?= htmlspecialchars($role['department']) ?></span>
        </div>

        <div class="detail-row">
            <strong>Access:</strong>
            <div class="value access-list">
                <?php if (!empty($moduleControls)): ?>
                    <?php foreach ($moduleControls as $module => $perms): ?>
                        <div class="access-row">
                            <span class="module-name"><?= htmlspecialchars($module) ?> :</span>
                            <span class="module-perms">
                                <?php if(!empty($perms)): ?>
                                    <?= implode(' ', array_map('ucfirst', array_map('htmlspecialchars', $perms))) ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">No Controls</span>
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span>No Access</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Optional styling -->
<style>
.role-details {
    max-width: 700px;
    margin: 30px auto;
    padding: 25px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.detail-row strong {
    width: 30%;
    color: #333;
    font-weight: 600;
}

.detail-row .value,
.detail-row span {
    width: 68%;
    text-align: left;
}

.access-list .access-row {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 5px;
}

.access-list .module-name {
    width: 200px; /* fixed first column */
    font-weight: 500;
}

.access-list .module-perms {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.85rem;
    color: black;
}
</style>

<?php require_once '../includes/footer.php'; ?>
