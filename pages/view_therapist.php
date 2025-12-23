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


$pageTitle = "View Therapist";
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

// Fetch role name
$role_name = 'N/A';
if (!empty($therapist['role_access_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM role_management WHERE r_id = ?");
    $stmt->execute([$therapist['role_access_id']]);
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

<div class="main-content" style="background: #fff; position: relative;">
    <div class="page-header">
        <button class="back-btn" onclick="window.history.back();">&larr;</button>
        <span class="page-title">View Therapist</span>
    </div>

    <div class="therapist-info p-3">

        <div class="info-row">
            <div class="info-label">Moodle ID</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($therapist['moodle_id'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">First Name</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($therapist['first_name'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Last Name</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($therapist['last_name'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Profile Image</div>
            <div class="info-colon">:</div>
            <div class="info-value" style="display: flex; justify-content: space-between; align-items: center; margin-left: 8px;">
                <?php if (!empty($therapist['profile_image'])): ?>
                    <span><?= htmlspecialchars($therapist['profile_image']) ?></span>
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
            <div class="info-value"><?= htmlspecialchars($therapist['email'] ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Role/Access</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($role_name ?? 'N/A') ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Password</div>
            <div class="info-colon">:</div>
            <div class="info-value"><?= htmlspecialchars($therapist['password'] ?? 'N/A') ?></div>
        </div>
    </div>

    <!-- ✅ Floating Image Card -->
    <?php if (!empty($therapist['profile_image'])): ?>
    <div id="imageCard" class="image-card">
        <div class="image-card-header">
            <span>Profile Image</span>
            <button onclick="closeImageCard()">×</button>
        </div>
        <div class="image-card-body">
            <img src="../uploads/therapists/<?= htmlspecialchars($therapist['profile_image']) ?>" 
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
