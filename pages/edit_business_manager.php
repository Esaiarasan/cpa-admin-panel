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


$pageTitle = "Edit Business Manager";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Get Business Manager ID from URL
$business_manager_id = $_GET['id'] ?? null;
if (!$business_manager_id) {
    echo "<p class='text-danger'>Invalid Business Manager ID.</p>";
    exit;
}

// Fetch Business Manager data
$stmt = $pdo->prepare("SELECT * FROM business_manager WHERE business_manager_id = ?");
$stmt->execute([$business_manager_id]);
$business_manager = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$business_manager) {
    echo "<p class='text-danger'>Business Manager not found.</p>";
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
            <span class="page-title">Edit Business Manager</span>
        </div>

        <form id="business_managerForm" action="../api/update_business_manager.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="business_manager_id" value="<?= htmlspecialchars($business_manager['business_manager_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <div class="row g-4">

                <!-- First Name -->
                <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                        value="<?= htmlspecialchars($business_manager['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Last Name -->
                <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                        value="<?= htmlspecialchars($business_manager['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Profile Image -->
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="profile_image">Profile Image</label>
                
                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                
                    <?php if (!empty($business_manager['profile_image'])): ?>
                        <div class="mt-2 text-muted small">
                           <strong><?= htmlspecialchars($business_manager['profile_image']) ?></strong>
                        </div>
                    <?php endif; ?>
                
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($business_manager['profile_image']) ?>">
                </div>

                <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="<?= htmlspecialchars($business_manager['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Role/Access -->
                <div class="col-md-4">
                    <label class="form-label" for="roles_access">Roles/Access</label>
                    <select class="form-select" id="roles_access" name="roles_access">
                    <option value="">Role/Access</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= htmlspecialchars($role['r_id'], ENT_QUOTES, 'UTF-8') ?>" 
                            <?= ($business_manager['role_access_id'] == $role['r_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['role'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                </div>

                <!-- Moodle ID -->
                <div class="col-md-8">
                    <label class="form-label" for="user_id">Moodle ID</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" 
                        value="<?= htmlspecialchars($business_manager['user_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password" 
                        value="<?= htmlspecialchars($business_manager['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
<script src="../assets/js/business_manager.js?v=<?php echo time(); ?>"></script>

<?php require_once '../includes/footer.php'; ?>
