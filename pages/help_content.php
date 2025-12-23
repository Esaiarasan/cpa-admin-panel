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

$pageTitle = "Help Content Upload";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';


// Save form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["video"])) {
    $title = $_POST['title'];
    $steps = $_POST['steps'];
    $description = $_POST['description'];
    $targetDir = "../uploads/";
    $videoFile = basename($_FILES["video"]["name"]);
    $targetFilePath = $targetDir . $videoFile;

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO help_upload_content (title, video, steps, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $videoFile, $steps, $description);
        $stmt->execute();
        echo '<div class="upload-success">Uploaded successfully!</div>';
    } else {
        echo '<div class="upload-fail">Video upload failed.</div>';
    }
}
?>

<style>
.help-upload-container {
    max-width: 900px;
    margin: 5rem auto 0 auto;
}

.help-upload-header {
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

.help-upload-form {
    margin-top: 24px;
}
</style>

<div class="help-upload-container">
    <!-- Back and Close buttons -->
    <div class="help-upload-header">
        <button onclick="window.history.back()" class="nav-btn">
            <span>&#8592;</span>
        </button>
        <button onclick="window.location.href='content_upload.php'" class="nav-btn close-btn">
            <span>&times;</span>
        </button>
    </div>

    <h2 class="page-title">Help Upload Content</h2>

    <form method="POST" enctype="multipart/form-data" autocomplete="off" class="help-upload-form">
        <div class="mb-3">
            <label class="form-label fw-bold">Title</label>
            <input type="text" name="title" required class="form-control" placeholder="Enter title">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Video</label>
            <input type="file" name="video" accept="video/mp4,video/x-m4v,video/*" required class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Steps to follow</label>
            <input type="text" name="steps" placeholder="Go to > Click here > Select > Open Screen" class="form-control">
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Enter short description"></textarea>
        </div>

        <button type="submit" class="btn btn-primary px-5">Submit</button>
    </form>
</div>
