<?php
require_once '../config.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// Read JSON if sent
$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

// Merge JSON and form-data safely
$data = array_merge($_POST, is_array($input) ? $input : []);

// Extract fields
$role_id = $data['role_id'] ?? '';
$role = trim($data['txt_role'] ?? '');
$department = trim($data['txt_department'] ?? '');
$access = $data['sel_access'] ?? [];
$permissions = $data['permissions'] ?? [];

// Validate required fields
if (empty($role_id) || empty($role) || empty($department) || empty($access)) {
    echo json_encode([
        "status" => false,
        "message" => "Missing required fields. Required: role_id, txt_role, txt_department, sel_access"
    ]);
    exit;
}

// Process permissions into consistent format
$controls = [];
foreach ($permissions as $navId => $val) {
    if (is_array($val)) {
        $controls[$navId] = $val;
    } else {
        $controls[$navId] = array_filter(array_map('trim', explode(',', $val)));
    }
}

// Convert access array to CSV
$access_csv = is_array($access) ? implode(',', $access) : $access;
$controls_json = json_encode($controls);

try {
    $stmt = $pdo->prepare("
        UPDATE role_management 
        SET role = :role, department = :department, access_navigations = :access, controls = :controls
        WHERE r_id = :role_id
    ");

    $stmt->execute([
        ':role' => $role,
        ':department' => $department,
        ':access' => $access_csv,
        ':controls' => $controls_json,
        ':role_id' => $role_id
    ]);

    echo json_encode([
        "status" => true,
        "message" => "Role updated successfully"
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Database Error: " . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "message" => "Error: " . $e->getMessage()
    ]);
}
