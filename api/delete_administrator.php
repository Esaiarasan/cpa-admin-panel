<?php
header("Content-Type: application/json");
require_once '../config.php';

// ✅ Accept both `id` or `administrator_id` from POST
$administrator_id = $_POST['administrator_id'] ?? $_POST['id'] ?? null;

if (!$administrator_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Administrator ID is required"
    ]);
    exit;
}

try {
    // ✅ Check if Administrator exists
    $stmt = $pdo->prepare("SELECT administrator_id, profile_image FROM administrator WHERE administrator_id = ?");
    $stmt->execute([$administrator_id]);
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$manager) {
        echo json_encode([
            "status" => "error",
            "message" => "Administrator not found"
        ]);
        exit;
    }

    // ✅ Delete profile image if it exists
    $imagePath = "../uploads/administrator/" . $manager['profile_image'];
    if (!empty($manager['profile_image']) && file_exists($imagePath)) {
        unlink($imagePath);
    }

    // ✅ Delete Administrator from DB
    $stmt = $pdo->prepare("DELETE FROM administrator WHERE administrator_id = ?");
    $stmt->execute([$administrator_id]);

    echo json_encode([
        "status" => "success",
        "message" => "Administrator deleted successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
