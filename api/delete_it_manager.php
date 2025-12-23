<?php
header("Content-Type: application/json");
require_once '../config.php';

// Match the AJAX key
$it_manager_id  = $_POST['it_manager_id'] ?? null;

if (!$it_manager_id) {
    echo json_encode([
        "status" => "error",
        "message" => "IT Manager ID is required"
    ]);
    exit;
}

try {
    // Check if IT Manager exists
    $stmt = $pdo->prepare("SELECT it_manager_id, profile_image FROM it_managers WHERE it_manager_id = ?");
    $stmt->execute([$it_manager_id]);
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manager) {
        echo json_encode([
            "status" => "error",
            "message" => "IT Manager not found"
        ]);
        exit;
    }

    // Delete profile image if exists
    if (!empty($manager['profile_image']) && file_exists("../uploads/it_managers/" . $manager['profile_image'])) {
        unlink("../uploads/it_managers/" . $manager['profile_image']);
    }

    // Delete IT Manager from DB
    $stmt = $pdo->prepare("DELETE FROM it_managers WHERE it_manager_id = ?");
    $stmt->execute([$it_manager_id]);

    echo json_encode([
        "status" => "success",
        "message" => "IT Manager deleted successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
