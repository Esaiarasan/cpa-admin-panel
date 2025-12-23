<?php
require_once '../config.php';
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$role_id = $input['role_id'] ?? '';

if (empty($role_id)) {
    echo json_encode(["success" => false, "message" => "Missing role_id"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM role_management WHERE r_id = ?");
    $stmt->execute([$role_id]);

    echo json_encode(["success" => true, "message" => "Role deleted successfully"]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
