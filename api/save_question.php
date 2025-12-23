<?php
require_once '../config.php';

header('Content-Type: application/json');


/*
  Incoming from toolbar.php:
  - pathway_id   = create_pathway.clpa_id  (pathway_name_id)
  - parent_node  = group node (e.g. "1")
  - question_text
  - keyword
*/

$pathwayNameId  = isset($_POST['pathway_id'])  ? (int)$_POST['pathway_id'] : 0; // clpa_id
$parentNode     = trim($_POST['parent_node']   ?? '');
$question       = trim($_POST['question_text'] ?? '');
$keyword        = trim($_POST['keyword']       ?? '');

if (!$pathwayNameId || $parentNode === '' || $question === '' || $keyword === '') {
    echo json_encode([
        "status"  => false,
        "message" => "Missing required fields"
    ]);
    exit;
}

try {

    // 1️⃣ Map create_pathway.clpa_id + node_number → pathways.pathway_id
    $stmt = $pdo->prepare("
        SELECT pathway_id
        FROM pathways
        WHERE pathway_name_id = ?
          AND node_number      = ?
        LIMIT 1
    ");
    $stmt->execute([$pathwayNameId, $parentNode]);
    $pathwayId = $stmt->fetchColumn();

    if (!$pathwayId) {
        echo json_encode([
            "status"  => false,
            "message" => "Invalid pathway reference (no group found for this pathway & node)"
        ]);
        exit;
    }

    // 2️⃣ Find last question node under this group in this pathway
    $stmt = $pdo->prepare("
        SELECT node_number
        FROM pathway_questions
        WHERE pathway_id  = ?
          AND parent_node = ?
        ORDER BY node_number DESC
        LIMIT 1
    ");
    $stmt->execute([$pathwayId, $parentNode]);
    $lastNode = $stmt->fetchColumn();

    if (!$lastNode) {
        // first question under this group → 1.1 if parent_node is 1
        $newNode = $parentNode . '.1';
    } else {
        $parts = explode('.', $lastNode);
        $parts[count($parts) - 1]++;        // increment last segment
        $newNode = implode('.', $parts);
    }

    // 3️⃣ Insert question
    $stmt = $pdo->prepare("
        INSERT INTO pathway_questions
        (
            pathway_id,
            parent_node,
            node_number,
            question_text,
            keywords,
            is_for_therapist,
            sort_order,
            created_at,
            created_by
        )
        VALUES
        (?, ?, ?, ?, ?, 1, 0, NOW(), ?)
    ");

    $stmt->execute([
        $pathwayId,
        $parentNode,
        $newNode,
        $question,
        $keyword,
        $_SESSION['user_id'] ?? null
    ]);

    echo json_encode([
        "status"      => true,
        "node_number" => $newNode
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status"  => false,
        "message" => $e->getMessage()
    ]);
}
