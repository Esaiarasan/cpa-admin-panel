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
$pageTitle = "Create Therapist";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';




// Fetch roles/access from the role_management table
$stmt = $pdo->query("SELECT r_id, role FROM role_management WHERE deleted_at = 0 ORDER BY r_id ASC;");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
<!-- Alertify CSS & JS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<div class="main-content" style="background: #fff;">
    <div class="">
        <div class="page-header">
            <button class="back-btn" onclick="window.history.back();">&larr;</button>
            <span class="page-title">Create Therapist</span>
        </div>

        <form id="therapistForm" action="../api/save_therapist.php" method="POST" enctype="multipart/form-data" novalidate>
            <div class="row g-4">

                <!-- First Name -->
                <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                    <div class="invalid-feedback">First Name is required</div>
                </div>

                <!-- Last Name -->
                <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                    <div class="invalid-feedback">Last Name is required</div>
                </div>

                <!-- Profile Image -->
                <div class="col-md-4">
                    <label class="form-label" for="profile_image">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                </div>

                <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback">Valid Email is required</div>
                </div>

                <!-- Role/Access -->
                <div class="col-md-4">
                    <label class="form-label" for="roles_access">Roles/Access</label>
                    <select class="form-select" id="roles_access" name="roles_access">
                        <option value="">Select Role/Access</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= htmlspecialchars($role['r_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($role['role'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Please select a Role/Access</div>
                </div>

                <!-- Moodle ID -->
                <div class="col-md-8">
                    <label class="form-label" for="moodle_id">Moodle ID</label>
                    <input type="text" class="form-control" id="moodle_id" name="moodle_id">
                    <div class="invalid-feedback">Moodle ID is required</div>
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password">
                    <div class="invalid-feedback">Password must be at least 6 characters</div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Alertify JS -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<!-- Therapists JS (auto version refresh for cache busting) -->
<script src="../assets/js/therapists.js?v=<?php echo time(); ?>"></script>


<?php require_once '../includes/footer.php'; ?>
