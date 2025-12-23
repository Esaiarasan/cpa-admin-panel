<?php
header("Content-Type: application/json");
require_once '../config.php';

// Get therapist ID from query string
$therapist_id = $_GET['id'] ?? null;

if (!$therapist_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Therapist ID is required"
    ]);
    exit;
}

try {
    // Optional: Check if therapist exists
    $stmt = $pdo->prepare("SELECT therapist_id, profile_image FROM therapists WHERE therapist_id = ?");
    $stmt->execute([$therapist_id]);
    $therapist = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$therapist) {
        echo json_encode([
            "status" => "error",
            "message" => "Therapist not found"
        ]);
        exit;
    }

    // Delete profile image from server if exists
    if (!empty($therapist['profile_image']) && file_exists("../uploads/" . $therapist['profile_image'])) {
        unlink("../uploads/" . $therapist['profile_image']);
    }

    // Delete therapist
    $stmt = $pdo->prepare("DELETE FROM therapists WHERE therapist_id = ?");
    $stmt->execute([$therapist_id]);

    echo json_encode([
        "status" => "success",
        "message" => "Therapist deleted successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . $e->getMessage()
    ]);
}
