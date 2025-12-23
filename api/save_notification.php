<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

require_once '../config.php'; // provides $pdo

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['message']) || empty(trim($data['message']))) {
        echo json_encode(["status" => "error", "message" => "Message is required"]);
        exit;
    }

    if (!isset($data['sent_to']) || !is_array($data['sent_to']) || count($data['sent_to']) === 0) {
        echo json_encode(["status" => "error", "message" => "At least one recipient (sent_to) must be selected"]);
        exit;
    }

    $message = trim($data['message']);

    // Replace this with actual logged-in user ID or session
    $logged_in_user_id = 1;

    // ✅ Get username from users table
    $userStmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $userStmt->execute([$logged_in_user_id]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "Logged-in user not found"]);
        exit;
    }

    $created_by = $user['username'];
    $sent_date = date("Y-m-d H:i:s");

    // ✅ Step 1: Insert into notifications table
    if (in_array('all', $data['sent_to'])) {
        // ➤ Case: Send to all roles
        $stmt = $pdo->prepare("
            INSERT INTO notifications (message, sent_date, created_by, created_at, sent_to)
            VALUES (?, ?, ?, NOW(), 'all')
        ");
        $stmt->execute([$message, $sent_date, $created_by]);
        $notification_id = $pdo->lastInsertId();

        // ➤ Step 2: Single record for 'all' in recipients table
        $recipient_stmt = $pdo->prepare("
            INSERT INTO notification_recipients (notification_id, recipient_type)
            VALUES (?, 'all')
        ");
        $recipient_stmt->execute([$notification_id]);

    } else {
        // ➤ Case: Specific roles selected
        $stmt = $pdo->prepare("
            INSERT INTO notifications (message, sent_date, created_by, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$message, $sent_date, $created_by]);
        $notification_id = $pdo->lastInsertId();

        // ➤ Step 2: Insert each role into notification_recipients
        $recipient_stmt = $pdo->prepare("
            INSERT INTO notification_recipients (notification_id, recipient_type, recipient_id)
            VALUES (?, 'role', ?)
        ");
        foreach ($data['sent_to'] as $role_id) {
            $recipient_stmt->execute([$notification_id, $role_id]);
        }
    }

    echo json_encode([
        "status" => "success",
        "message" => "Notification sent successfully!",
        "notification_id" => $notification_id
    ]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
