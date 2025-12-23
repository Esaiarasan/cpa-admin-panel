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


$pageTitle = "Edit IT Manager";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Get IT Manager ID from URL
$it_manager_id = $_GET['id'] ?? null;
if (!$it_manager_id) {
    echo "<p class='text-danger'>Invalid IT Manager ID.</p>";
    exit;
}

// Fetch IT Manager data
$stmt = $pdo->prepare("SELECT * FROM it_managers WHERE it_manager_id = ?");
$stmt->execute([$it_manager_id]);
$it_manager = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$it_manager) {
    echo "<p class='text-danger'>IT Manager not found.</p>";
    exit;
}

// Fetch roles/access from the DB
$stmt = $pdo->query("SELECT r_id, role FROM role_management ORDER BY role ASC");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />

<div class="main-content" style="background: #fff;">
    <div class="">
        <div class="page-header">
            <button class="back-btn" onclick="window.history.back();">&larr;</button>
            <span class="page-title">Edit IT Manager</span>
        </div>

        <form id="it_managerForm" action="../api/update_it_manager.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="it_manager_id" value="<?= htmlspecialchars($it_manager['it_manager_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <div class="row g-4">

                <!-- First Name -->
                <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                        value="<?= htmlspecialchars($it_manager['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Last Name -->
                <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                        value="<?= htmlspecialchars($it_manager['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Profile Image -->
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="profile_image">Profile Image</label>
                
                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                
                    <?php if (!empty($it_manager['profile_image'])): ?>
                        <div class="mt-2 text-muted small">
                           <strong><?= htmlspecialchars($it_manager['profile_image']) ?></strong>
                        </div>
                    <?php endif; ?>
                
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($it_manager['profile_image']) ?>">
                </div>

                <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="<?= htmlspecialchars($it_manager['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Role/Access -->
                <div class="col-md-4">
                    <label class="form-label" for="roles_access">Roles/Access</label>
                    <select class="form-select" id="roles_access" name="roles_access">
                    <option value="">Role/Access</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= htmlspecialchars($role['r_id'], ENT_QUOTES, 'UTF-8') ?>" 
                            <?= ($it_manager['role_access_id'] == $role['r_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['role'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                </div>

                <!-- Moodle ID -->
                <div class="col-md-8">
                    <label class="form-label" for="user_id">Moodle ID</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" 
                        value="<?= htmlspecialchars($it_manager['user_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password" 
                        value="<?= htmlspecialchars($it_manager['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="../assets/js/it_managers.js?v=<?php echo time(); ?>"></script>

<?php require_once '../includes/footer.php'; ?>
