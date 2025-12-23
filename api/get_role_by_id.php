<?php
require_once '../config.php';
header("Content-Type: application/json");

$id = $_GET['id'] ?? '';

if (empty($id)) {
    echo json_encode(["status" => false, "message" => "Missing ID"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM role_management WHERE r_id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Decode controls JSON properly
        $row['controls'] = json_decode($row['controls'], true);
        echo json_encode(["status" => true, "data" => $row]);
    } else {
        echo json_encode(["status" => false, "message" => "Role not found"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>
