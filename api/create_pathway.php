<?php
require_once '../config.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => false, "message" => "Invalid request"]);
    exit;
}

$appointment_type = trim($_POST['appointment_type'] ?? '');
$age_group        = trim($_POST['age_group'] ?? '');
$goal_domain      = trim($_POST['goal_domain'] ?? '');
$pathway_name     = trim($_POST['pathway_name'] ?? '');
$created_by       = $_SESSION['user_id'] ?? null;

// ✅ Validation
if ($appointment_type === '' || $age_group === '' || $goal_domain === '' || $pathway_name === '') {
    echo json_encode(["status" => false, "message" => "All fields are required"]);
    exit;
}

// ✅ Appointment ID
$appt_id = ($appointment_type === "initial") ? 1 : 2;

try {
    $pdo->beginTransaction();

    // 1️⃣ INSERT PATHWAY
    $stmt = $pdo->prepare("
        INSERT INTO create_pathway
        (appt_id, appointment_type, age_group, goal_domain, pathway_name, created_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $appt_id,
        $appointment_type,
        $age_group,
        $goal_domain,
        $pathway_name,
        $created_by
    ]);

    $clpa_id = $pdo->lastInsertId();

    // 2️⃣ INSERT FIRST GROUP (REQUIRED FOR UI)
    $stmt = $pdo->prepare("
        INSERT INTO pathways
        (
            pathway_name_id,
            node_number,
            parent_node,
            group_name,
            created_at,
            created_by
        )
        VALUES
        (?, '1', NULL, ?, NOW(), ?)
    ");

    $stmt->execute([
        $clpa_id,
        'Background Information & Assessments',
        $created_by
    ]);

    $pdo->commit();

    echo json_encode([
        "status"  => true,
        "message" => "Pathway created successfully",
        "clpa_id" => $clpa_id
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "status"  => false,
        "message" => $e->getMessage()
    ]);
}
