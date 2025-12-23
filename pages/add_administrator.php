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

$pageTitle = "Create Administrator";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';



// Fetch roles/access from the DB
$stmt = $pdo->query("SELECT r_id, role FROM role_management WHERE deleted_at = 0 ORDER BY r_id ASC;");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<link href="../assets/css/bootstrap.min.css?v=1.0" rel="stylesheet" />
<link href="../assets/css/style.css?v=1.0" rel="stylesheet" />
<style>

</style>

<div class="main-content" style="background: #fff;">
    <div class="">
        <div class="page-header">
            <button class="back-btn" onclick="window.history.back();">&larr;</button>
            <span class="page-title">Create Administrator</span>
        </div>
        <form id="administrator_Form" action="../api/save_administrator.php" method="POST" enctype="multipart/form-data">
            <div class="row g-4">

            <!-- 1st row  start-->

            <!-- FirstName -->

            <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name">
                   
            </div>

            <!-- LastName -->

            <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name">
                   
                </div>

            <!-- Profile Image -->

                <div class="col-md-4">
                <label class="form-label " for="profile_image">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                </div>

            <!-- 1st row end -->

            <!-- 2nd row start -->

            <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
              
                <!-- RoleAccess -->

           

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

                    <!-- 2nd Row END -->

            <!-- 3r Row STart -->

            <!-- MoodleID -->

                <div class="col-md-8">
                    <label class="form-label" for="user_id">User ID</label>
                    <input type="text" class="form-control" id="user_id" name="user_id">
                </div>

                
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password">
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

<?php require_once '../includes/footer.php'; ?>
