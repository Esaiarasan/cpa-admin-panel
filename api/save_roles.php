<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['txt_role'] ?? '';
    $department = $_POST['txt_department'] ?? '';
    $access = $_POST['sel_access'] ?? [];
    $permissions = $_POST['permissions'] ?? [];

    if (!empty($role) && !empty($department) && !empty($access)) {
        $access_csv = implode(',', $access);
        $permissions_json = json_encode($permissions);
        $created_at = date('Y-m-d H:i:s'); // current server time

        $stmt = $pdo->prepare("
            INSERT INTO role_management 
            (role, department, access_navigations, controls, created_at) 
            VALUES (?,?,?,?,?)
        ");
        if ($stmt->execute([$role, $department, $access_csv, $permissions_json, $created_at])) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}

