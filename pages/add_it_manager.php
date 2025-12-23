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


$pageTitle = "Create IT Manager";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';




// Fetch roles/access from the DB
$stmt = $pdo->query("SELECT r_id, role FROM role_management WHERE deleted_at = 0 ORDER BY r_id ASC;");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link href="../assets/css/bootstrap.min.css" rel="stylesheet" />

<div class="main-content" style="background: #fff;">
    <div class="">
        <div class="page-header">
            <button class="back-btn" onclick="window.history.back();">&larr;</button>
            <span class="page-title">Create IT Manager</span>
        </div>
        <form id="it_manager_Form" action="../api/save_it_manager.php" method="POST" enctype="multipart/form-data">
            <div class="row g-4">

                <!-- First Name -->
                <div class="col-md-4">
                    <label class="form-label" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                </div>

                <!-- Last Name -->
                <div class="col-md-4">
                    <label class="form-label" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                </div>

                <!-- Profile Image -->
                <div class="col-md-4">
                    <label class="form-label" for="profile_image">Profile Image</label>
                    <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                </div>

                <!-- Email -->
                <div class="col-md-8">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Role/Access -->
                <div class="col-md-4">
                    <label class="form-label" for="role_access_id">Roles/Access</label>
                    <select class="form-select" id="role_access_id" name="role_access_id" required>
                        <option value="">Select Role/Access</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= htmlspecialchars($role['r_id'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($role['role'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- User ID -->
                <div class="col-md-8">
                    <label class="form-label" for="user_id">User ID</label>
                    <input type="text" class="form-control" id="user_id" name="user_id" required>
                </div>

                <!-- Password -->
                <div class="col-md-4">
                    <label class="form-label" for="password">Password</label>
                    <input type="text" class="form-control" id="password" name="password" required>
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


<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$('#itManagerForm').submit(function(e){
    e.preventDefault(); // prevent normal form submission

    let formData = new FormData(this);

    $.ajax({
        url: '../api/save_it_manager.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // expect JSON response
        success: function(res){
            // Show alert for both success and error
            alert(res.message);

            if(res.status === 'success'){
                // Optional: reset form or redirect
                $('#itManagerForm')[0].reset();
                // window.location.href = 'it_managers.php'; // uncomment if you want redirect
            }
        },
        error: function(xhr, status, error){
            alert('Something went wrong! Please try again.');
            console.error(xhr.responseText);
        }
    });
});
</script>
<?php require_once '../includes/footer.php'; ?>
