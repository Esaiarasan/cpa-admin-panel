<?php
require_once '../config.php';
header('Content-Type: application/json');

$parent = $_GET['parent_node'] ?? null;
if (!$parent) {
    echo json_encode(['node' => '--']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT node_number
    FROM pathway_questions
    WHERE parent_node = ?
    ORDER BY node_number DESC LIMIT 1
");
$stmt->execute([$parent]);
$last = $stmt->fetchColumn();

if (!$last) {
    $next = $parent . '.1';
} else {
    $p = explode('.', $last);
    $p[count($p)-1]++;
    $next = implode('.', $p);
}

echo json_encode(['node' => $next]);
