<?php
/**
 * Toolbar Loader
 * This file only loads toolbar.php safely
 * DO NOT add UI or logic here
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Load toolbar
require_once __DIR__ . '/toolbar.php';

// Call toolbar render function
if (function_exists('renderToolbar')) {
    renderToolbar(); // p_id is already taken from $_GET inside toolbar.php
} else {
    // Fallback (in case function is removed later)
    include __DIR__ . '/toolbar.php';
}
