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


$pageTitle = "Bulk Upload";
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css?v=4.0">

    <style>
        .bulk-upload-container {
            margin: 80px auto;
            max-width: 700px;
            padding: 30px 35px;
            border-radius: 10px;
        }

        .bulk-upload-header {
            color: #16ace5;
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: left;
            border-bottom: 2px solid #16ace5;
            padding-bottom: 8px;
        }

        .bulk-upload-row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 22px;
            gap: 20px;
        }

        .bulk-upload-label {
            flex: 0 0 200px;
            font-size: 1.05em;
            font-weight: 500;
            color: #333;
        }

        .bulk-upload-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .template-btn,
        .choose-btn {
            background: #fff;
            color: black;
            border: 1px solid #bfe9fc;
            border-radius: 5px;
            padding: 8px 12px;
            font-size: 0.95em;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .template-btn:hover,
        .choose-btn:hover {
            background: #e6f5ff;
        }

        /* ✅ Selected state for file */
        .choose-btn.selected {
            background: #f0f0f0;
            color: #555;
            border-color: #ccc;
        }

        .choose-btn.selected .file-name-display {
            color: #444;
            font-weight: 500;
        }

        .file-name-display {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: color 0.2s;
        }

        .upload-btn {
            background: #16ace5;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 9px 24px;
            font-size: 0.95em;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s;
        }

        .upload-btn:hover {
            background: #0d94c9;
        }

        /* ✅ Animation for uploading */
        .uploading {
            background: #999 !important;
            cursor: not-allowed;
            position: relative;
        }

        .uploading::after {
            content: '...';
            display: inline-block;
            animation: dots 1.2s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        input[type="file"] {
            display: none;
        }

        @media (max-width: 600px) {
            .bulk-upload-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .bulk-upload-actions {
                width: 100%;
                flex-wrap: wrap;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="bulk-upload-container">
        <div class="bulk-upload-header">Bulk Upload</div>

        <?php
        $uploadTypes = ["Therapists","Admin Users", "Pathways"];
        foreach ($uploadTypes as $type):
            $inputId = strtolower(str_replace(' ', '_', $type)) . '_file';
        ?>
            <form class="bulk-upload-row" method="POST" enctype="multipart/form-data" action="">
                <div class="bulk-upload-label"><?= htmlspecialchars($type) ?> :</div>
                <div class="bulk-upload-actions">
                    <button class="template-btn" type="button">Template <i class="fa-solid fa-download"></i></button>

                    <label for="<?= $inputId ?>" class="choose-btn">
                        <span class="file-name-display">Choose File</span>
                        <i class="fa-solid fa-upload"></i>
                    </label>
                    <input type="file" id="<?= $inputId ?>" name="<?= $inputId ?>" accept=".csv,.xlsx" required>

                    <button class="upload-btn" type="submit">Upload</button>
                </div>
            </form>
        <?php endforeach; ?>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(function(input) {
        const label = input.previousElementSibling;
        const fileNameSpan = label.querySelector('.file-name-display');
        const form = input.closest('form');
        const uploadBtn = form.querySelector('.upload-btn');

        // File selection
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || "Choose File";
            fileNameSpan.textContent = fileName;
            fileNameSpan.title = fileName;

            if (e.target.files.length > 0) {
                label.classList.add("selected");
            } else {
                label.classList.remove("selected");
                fileNameSpan.textContent = "Choose File";
            }
        });

        // Upload button animation
        form.addEventListener("submit", function(e) {
            e.preventDefault(); // prevent default for demo
            uploadBtn.textContent = "Uploading";
            uploadBtn.classList.add("uploading");

            // Simulate upload for 2.5 seconds
            setTimeout(() => {
                uploadBtn.textContent = "Upload";
                uploadBtn.classList.remove("uploading");
                alert("File uploaded successfully!");
            }, 2500);
        });
    });
});
</script>
</body>

<link rel="stylesheet" href="../assets/css/jquery.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/buttons.dataTables.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/all.min.css?v=4.0">
<link rel="stylesheet" href="../assets/css/style.css?v=4.0">
</html>

