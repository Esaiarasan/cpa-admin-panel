<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require_once '../config.php'; // $pdo connection

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['notification_id']) || empty($data['notification_id'])) {
        echo json_encode(["status" => "error", "message" => "Notification ID is required"]);
        exit;
    }

    $notification_id = $data['notification_id'];

    // Begin transaction
    $pdo->beginTransaction();

    // Delete from notification_recipients
    $stmt1 = $pdo->prepare("DELETE FROM notification_recipients WHERE notification_id = ?");
    $stmt1->execute([$notification_id]);

    // Delete from notifications
    $stmt2 = $pdo->prepare("DELETE FROM notifications WHERE notification_id = ?");
    $stmt2->execute([$notification_id]);

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Notification deleted successfully!"
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
