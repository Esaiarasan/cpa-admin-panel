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


$pageTitle = "Library Content Upload";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Save form
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["video"])) {
    $title = $_POST['title'];
    $sub_heading = $_POST['sub_heading'];
    $description = $_POST['description'];
    $page_link = $_POST['page_link'];

    // $targetDir = "../uploads/";
    // $videoFile = basename($_FILES["video"]["name"]);
    // $targetFilePath = $targetDir . $videoFile;

    if (move_uploaded_file($_FILES["video"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO lib_upload_content (title, sub_heading, description, page_link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $sub_heading, $description, $page_link, $videoFile);
        $stmt->execute();
        echo '<div class="upload-success">Uploaded successfully!</div>';
    } else {
        echo '<div class="upload-fail">Video upload failed.</div>';
    }
}
?>

<style>
.library-upload-container {
    max-width: 900px;
    margin: 5rem auto 0 auto;
}

.library-upload-header {
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

.library-upload-form {
    margin-top: 24px;
}
</style>

<div class="library-upload-container">
    <!-- Back and Close buttons -->
    <div class="library-upload-header">
        <button onclick="window.history.back()" class="nav-btn">
            <span>&#8592;</span>
        </button>
        <button onclick="window.location.href='content_upload.php'" class="nav-btn close-btn">
            <span>&times;</span>
        </button>
    </div>

    <h2 class="page-title">Library Content Upload</h2>

    <form method="POST" enctype="multipart/form-data" autocomplete="off" class="library-upload-form">
        <div class="mb-3">
            <label class="form-label fw-bold">Title</label>
            <input type="text" name="title" required class="form-control" placeholder="Enter title">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Sub Heading</label>
            <input type="text" name="sub_heading" required class="form-control" placeholder="Enter sub heading">
        </div>


        <div class="mb-4">
            <label class="form-label fw-bold">Description</label>
            <textarea name="description" rows="3" class="form-control" placeholder="Enter short description"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Page Link</label>
            <input type="text" name="page_link" placeholder="Enter related page link" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary px-5">Submit</button>
    </form>
</div>
