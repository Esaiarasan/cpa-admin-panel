<?php
require_once '../config.php';
header('Content-Type: application/json');

$pathwayId  = $_GET['pathway_id'] ?? null;
$parentNode = $_GET['parent_node'] ?? null;

if (!$pathwayId || !$parentNode) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT node_number, question_text, keywords
    FROM pathway_questions
    WHERE pathway_id = ?
      AND parent_node = ?
      AND deleted_at IS NULL
    ORDER BY node_number ASC
");

$stmt->execute([$pathwayId, $parentNode]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
