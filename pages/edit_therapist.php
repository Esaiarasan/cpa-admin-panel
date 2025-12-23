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

$pageTitle = "Edit Therapist";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Get therapist ID from URL
$therapist_id = $_GET['id'] ?? null;
if (!$therapist_id) {
    echo "<p class='text-danger'>Invalid Therapist ID.</p>";
    exit;
}

// Fetch therapist data
$stmt = $pdo->prepare("SELECT * FROM therapists WHERE therapist_id = ?");
$stmt->execute([$therapist_id]);
$therapist = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$therapist) {
    echo "<p class='text-danger'>Therapist not found.</p>";
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
            <span class="page-title">Edit Therapist</span>
        </div>

        <form id="therapistForm" action="../api/update_therapist.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="therapist_id" value="<?= htmlspecialchars($therapist['therapist_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <div class="row g-4">

                <!-- First Name -->
                <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" 
                        value="<?= htmlspecialchars($therapist['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Last Name -->
                <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" 
                        value="<?= htmlspecialchars($therapist['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Profile Image -->
                <div class="col-md-4 mb-3">
                    <label class="form-label" for="profile_image">Profile Image</label>
                
                    <input type="file" name="profile_image" id="profile_image" class="form-control">
                
                    <?php if (!empty($therapist['profile_image'])): ?>
                        <div class="mt-2 text-muted small">
                           <strong><?= htmlspecialchars($therapist['profile_image']) ?></strong>
                        </div>
                    <?php endif; ?>
                
                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($therapist['profile_image']) ?>">
                </div>

                <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                        value="<?= htmlspecialchars($therapist['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Role/Access -->
                <div class="col-md-4">
                    <label class="form-label" for="roles_access">Roles/Access</label>
                    <select class="form-select" id="roles_access" name="roles_access">
                    <option value="">Role/Access</option>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= htmlspecialchars($role['r_id'], ENT_QUOTES, 'UTF-8') ?>" 
                            <?= ($therapist['role_access_id'] == $role['r_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($role['role'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                </div>

                <!-- Moodle ID -->
                <div class="col-md-8">
                    <label class="form-label" for="moodle_id">Moodle ID</label>
                    <input type="text" class="form-control" id="moodle_id" name="moodle_id" 
                        value="<?= htmlspecialchars($therapist['moodle_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password" 
                        value="<?= htmlspecialchars($therapist['password'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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
<script src="../assets/js/therapists.js?v=<?php echo time(); ?>"></script>

<?php require_once '../includes/footer.php'; ?>
