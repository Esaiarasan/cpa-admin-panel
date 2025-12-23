<?php
session_start(); // Required before destroying session
require_once __DIR__ . '/../config.php';

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: ../pages/login.php');
exit;
?>
