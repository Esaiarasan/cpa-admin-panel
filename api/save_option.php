<?php
// api/save_option.php
require_once __DIR__ . '/../config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status'=>false,'message'=>'Unauthorized']); exit;
}

$question_node = $_POST['question_node'] ?? null; // e.g., "1.1.1"
$question_id = $_POST['question_id'] ?? null;
$option_text = trim($_POST['option_text'] ?? '');

if (!$question_node || !$option_text) {
    echo json_encode(['status'=>false,'message'=>'Missing params']); exit;
}

try {
    // next option under pathway_options where parent_node = question_node
    $q = $pdo->prepare("SELECT node_number FROM pathway_options WHERE parent_node = ? ORDER BY CAST(SUBSTRING_INDEX(node_number, '.', -1) AS UNSIGNED) DESC LIMIT 1");
    $q->execute([$question_node]);
    $row = $q->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $newNode = $question_node . '.1';
    } else {
        $last = $row['node_number'];
        $parts = explode('.', $last);
        $lastNum = (int)array_pop($parts);
        $nextNum = $lastNum + 1;
        $newNode = implode('.', $parts) . '.' . $nextNum;
    }

    // option_id in your dump is varchar(20). We will let DB generate or use UUID
    $ins = $pdo->prepare("INSERT INTO pathway_options (option_id, question_id, pqo_id, option_text, linked_question_id, node_number, parent_node, created_at, created_by) VALUES (UUID(), ?, NULL, ?, NULL, ?, ?, NOW(), ?)");
    $created_by = $_SESSION['user_id'];
    $ins->execute([$question_id, $option_text, $newNode, $question_node, $created_by]);

    echo json_encode(['status'=>true,'message'=>'Option saved','node_number'=>$newNode]);
} catch (Exception $e) {
    echo json_encode(['status'=>false,'message'=>$e->getMessage()]);
}
