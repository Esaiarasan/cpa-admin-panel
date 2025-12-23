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


$pageTitle = "View IT Manager";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Get it_manager ID from URL
$it_manager_id = $_GET['id'] ?? null;
if (!$it_manager_id) {
    echo "<p class='text-danger'>Invalid IT Manager ID.</span>";
    exit;
}

// Fetch it_manager data
$stmt = $pdo->prepare("SELECT * FROM it_managers WHERE it_manager_id = ?");
$stmt->execute([$it_manager_id]);
$it_manager = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$it_manager) {
    echo "<p class='text-danger'>IT Manager not found.</span>";
    exit;
}


// Fetch role name
$role_name = 'N/A';
if (!empty($it_manager['role_access_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM role_management WHERE r_id = ?");
    $stmt->execute([$it_manager['role_access_id']]);
    $role_name = $stmt->fetchColumn() ?: 'N/A';
}

?>


<style>
/* ✅ Floating Image Card Styles */
.image-card {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    width: 320px;
    max-height: 420px;
    overflow: hidden;
    display: none;
    z-index: 100;
}

.image-card.show {
    display: block;
}

.image-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #007bff;
    color: #fff;
    padding: 8px 12px;
    font-size: 15px;
}

.image-card-header button {
    background: none;
    border: none;
    color: #fff;
    font-size: 20px;
    cursor: pointer;
}

.image-card-body {
    text-align: center;
    background: #f9f9f9;
    padding: 10px;
}

.image-card-body img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    object-fit: contain;
    max-height: 350px;
}

.info-row {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.info-label {
    width: 150px;
    font-weight: 600;
}

.info-colon {
    margin: 0 8px;
}

.info-value {
    flex: 1;
}


</style>

<div class="main-content" style="background: #fff;">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">View IT Manager</span>
    </div>

    <div class="it_manager-info">

        <div class="info-row">
            <div class="info-label">User ID</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($it_manager['user_id'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">First Name</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($it_manager['first_name'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Last Name</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($it_manager['last_name'] ?? 'N/A') ?></div>
        </div>

       <div class="info-row">
                <div class="info-label">Profile Image</div>
                <div class="info-colon">:</div>
                <div class="info-value" style="display: flex; justify-content: space-between; align-items: center; margin-left: 8px;">
                    <?php if (!empty($it_manager['profile_image'])): ?>
                        <span><?= htmlspecialchars($it_manager['profile_image']) ?></span>
                        <a href="javascript:void(0)" onclick="showImageCard()" style="text-decoration: underline; color: #0d6efd; cursor: pointer;">
                            View
                        </a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </div>
            </div>
            
        <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($it_manager['email'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Role/Access</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($role_name ?? 'N/A') ?></div>
        </div>


        <div class="info-row">
            <div class="info-label">Password</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($it_manager['password'] ?? 'N/A') ?></div>
        </div>
    </div>
</div>

  <!-- ✅ Floating Image Card -->
    <?php if (!empty($it_manager['profile_image'])): ?>
    <div id="imageCard" class="image-card">
        <div class="image-card-header">
            <span>Profile Image</span>
            <button onclick="closeImageCard()">×</button>
        </div>
        <div class="image-card-body">
            <img src="../uploads/it_managers/<?= htmlspecialchars($it_manager['profile_image']) ?>" 
                 alt="Profile Image">
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function showImageCard() {
    document.getElementById('imageCard').classList.add('show');
}
function closeImageCard() {
    document.getElementById('imageCard').classList.remove('show');
}
</script>

<?php require_once '../includes/footer.php'; ?>
