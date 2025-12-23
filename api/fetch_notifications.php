<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Identify logged-in user
    $user_id   = $_SESSION['user_id'] ?? null;
    $role      = $_SESSION['user_type'] ?? null; // super_admin, administrator, brand_manager, etc.

    if (!$role) {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    // Build SQL query based on user type
    $query = "";
    switch ($role) {
        case 'super_admin':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'super_admin' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        case 'administrator':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'administrator' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        case 'brand_manager':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'brand_manager' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        case 'business_manager':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'business_manager' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        case 'it_manager':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'it_manager' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        case 'therapist':
            $query = "SELECT notification_id, sent_by, message, created_at 
                      FROM notifications
                      WHERE recipient_type = 'therapist' OR recipient_type = 'all'
                      ORDER BY created_at DESC";
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Unknown role"]);
            exit;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $notifications
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
