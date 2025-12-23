<?php
define('SITE_URL', 'http://localhost:8080/cpa_admin-panel/');
// define('SITE_URL', 'https://cpassist.traitsolutions.in/admin-panel/');
define('SITE_NAME', ' Super Admin');

// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', '');
// define('DB_NAME', 'cerebral_palsy');


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'u194236851_cpa');

session_start();

try {
    // Create PDO connection
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
        DB_USER, 
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
