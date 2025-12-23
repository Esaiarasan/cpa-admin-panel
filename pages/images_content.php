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

$pageTitle = "Banner Upload";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Save banner
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["banner_image"])) {
    $title = $_POST['title'];
    $targetDir = "../uploads/";
    $bannerFile = basename($_FILES["banner_image"]["name"]);
    $targetFilePath = $targetDir . $bannerFile;

    if (move_uploaded_file($_FILES["banner_image"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO lib_banner_upload (title, banner_image) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $bannerFile);
        $stmt->execute();
        echo '<div class="upload-success">Banner uploaded successfully!</div>';
    } else {
        echo '<div class="upload-fail">Banner upload failed.</div>';
    }
}
?>

<style>
.banner-upload-container {
    max-width: 900px;
    margin: 5rem auto 0 auto;
}

.banner-upload-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: -37px;
    margin-left: -41px;
}

.nav-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
}

.nav-btn span {
    font-size: 2rem;
    color: #333;
}

.close-btn span {
    display: inline-block;
    font-size: 2.1rem;
    font-weight: bold;
    color: #888;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 36px;
    border: 2px solid #ccc;
}

.upload-success {
    color: green;
    margin: 16px;
}

.upload-fail {
    color: red;
    margin: 16px;
}

.banner-upload-form {
    margin-top: 24px;
}
</style>

<div class="banner-upload-container">
    <!-- Back and Close buttons -->
    <div class="banner-upload-header">
        <button onclick="window.history.back()" class="nav-btn">
            <span>&#8592;</span>
        </button>
        <button onclick="window.location.href='images_content.php'" class="nav-btn close-btn">
            <span>&times;</span>
        </button>
    </div>

    <h2 class="page-title">Image Upload</h2>

    <form method="POST" enctype="multipart/form-data" autocomplete="off" class="banner-upload-form">
        <div class="mb-3">
            <label class="form-label fw-bold">Title</label>
            <input type="text" name="title" required class="form-control" placeholder="Enter banner title">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Image</label>
            <input type="file" name="banner_image" accept="image/png, image/jpeg, image/jpg, image/webp" required class="form-control">
        </div>

        <button type="submit" class="btn btn-primary px-5">Submit</button>
    </form>
</div>
