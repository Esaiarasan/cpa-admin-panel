<?php
require_once '../config.php';

// Fetch all roles
$stmt = $pdo->query("SELECT * FROM role_management WHERE deleted_at = 0 ORDER BY role ASC;");
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];

foreach ($roles as $role) {
    // Access badges
    $accessItems = [];
    if (!empty($role['access_navigations'])) {
        $accessIds = explode(',', $role['access_navigations']);
        $placeholders = implode(',', array_fill(0, count($accessIds), '?'));
        $stmtAccess = $pdo->prepare("SELECT nav_name FROM navigation_items WHERE nav_id IN ($placeholders)");
        $stmtAccess->execute($accessIds);
        $accessNames = $stmtAccess->fetchAll(PDO::FETCH_COLUMN);

        foreach ($accessNames as $name) {
            $accessItems[] = '<span class="badge bg-primary">'.htmlspecialchars($name).'</span>';
        }
    }

    // Controls badges
    $permItems = [];
    if (!empty($role['controls'])) {
        $perms = explode(',', $role['controls']);
        foreach ($perms as $p) {
            $permItems[] = '<span class="badge bg-success">'.ucfirst(htmlspecialchars($p)).'</span>';
        }
    }

    $data[] = [
        'r_id' => $role['r_id'],
        'role' => htmlspecialchars($role['role']),
        'department' => htmlspecialchars($role['department']),
        'access' => implode(' ', $accessItems),
        'controls' => implode(' ', $permItems)
    ];
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($data);
