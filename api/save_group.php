<?php
require_once '../config.php';
header('Content-Type: application/json');

// SESSION CHECK
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => false,
        "message" => "Session expired"
    ]);
    exit;
}

$pathwayId = $_POST['pathway_id'] ?? null;
$groupId   = $_POST['group_id'] ?? null;

if (!$pathwayId || !$groupId) {
    echo json_encode([
        "status" => false,
        "message" => "Missing parameters"
    ]);
    exit;
}

// FETCH GROUP NAME
$stmt = $pdo->prepare("SELECT group_name FROM groups WHERE id = ?");
$stmt->execute([$groupId]);
$group = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$group) {
    echo json_encode([
        "status" => false,
        "message" => "Invalid group"
    ]);
    exit;
}

$groupName = $group['group_name'];

try {
    // ✅ FIXED ORDER BY (NO id COLUMN!)
    $stmt = $pdo->prepare("
        SELECT node_number
        FROM pathways
        WHERE pathway_name_id = ?
        ORDER BY node_number DESC
        LIMIT 1
    ");
    $stmt->execute([$pathwayId]);
    $lastNode = $stmt->fetchColumn();

    // GENERATE NODE NUMBER
    if (!$lastNode) {
        $newNode = "1";
    } else {
        $parts = explode('.', $lastNode);
        $parts[count($parts) - 1]++;
        $newNode = implode('.', $parts);
    }

    // INSERT GROUP
    $stmt = $pdo->prepare("
        INSERT INTO pathways (pathway_name_id, group_name, node_number, created_at)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->execute([$pathwayId, $groupName, $newNode]);

    echo json_encode([
        "status" => true,
        "message" => "Group saved successfully",
        "node" => $newNode
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "message" => "Database error",
        "error" => $e->getMessage()
    ]);
    exit;
}
