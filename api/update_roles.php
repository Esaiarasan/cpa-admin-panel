<?php
require_once '../config.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

// debug log file (adjust path if necessary)
$logFile = __DIR__ . '/update_roles_debug.log';

function dbg($msg) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . ' ' . $msg . PHP_EOL, FILE_APPEND);
}

// Read raw JSON if present
$inputJSON = file_get_contents("php://input");
$input = json_decode($inputJSON, true);

// Merge POST and JSON (POST wins for same keys)
$data = array_merge(is_array($input) ? $input : [], $_POST);

// Small debug dump of incoming data
dbg("Incoming raw JSON: " . $inputJSON);
dbg("Merged data: " . print_r($data, true));

// Extract & sanitize fields
$role_id = isset($data['role_id']) ? trim($data['role_id']) : '';
$role = isset($data['txt_role']) ? trim($data['txt_role']) : '';
$department = isset($data['txt_department']) ? trim($data['txt_department']) : '';
$access = $data['sel_access'] ?? [];
$permissions = $data['permissions'] ?? [];

// Normalize sel_access: if single CSV string -> array; if numeric string -> wrap
if (!is_array($access)) {
    // e.g., "2,3" or "2"
    if (strlen($access) === 0) {
        $access = [];
    } else {
        $access = explode(',', (string)$access);
        $access = array_map('trim', $access);
    }
}

// Normalize permissions coming from form-data: they may be strings "view,edit"
$controls = [];
if (is_array($permissions)) {
    foreach ($permissions as $navId => $val) {
        if (is_array($val)) {
            // can come as array from form-data inputs
            $controls[$navId] = array_map('trim', $val);
        } else {
            // string like "view,edit" or empty
            $val = trim((string)$val);
            if ($val === '') {
                $controls[$navId] = [];
            } else {
                $controls[$navId] = array_filter(array_map('trim', explode(',', $val)));
            }
        }
    }
}

// minimal validation
if ($role_id === '' || $role === '' || $department === '' || empty($access)) {
    $msg = "Missing required fields. Provided role_id={$role_id}, role='".substr($role,0,20)."', department='".substr($department,0,20)."', access_count=" . count($access);
    dbg("Validation failed: " . $msg);
    echo json_encode([
        'status' => false,
        'message' => 'Missing required fields. Required: role_id, txt_role, txt_department, sel_access',
        'debug' => $msg
    ]);
    exit;
}

// final values to store
$access_csv = implode(',', array_map('trim', $access));
$controls_json = json_encode($controls, JSON_UNESCAPED_UNICODE);

// Debug before DB
dbg("Prepared access_csv: {$access_csv}");
dbg("Prepared controls_json: {$controls_json}");

try {
    // Use prepared statement with named params
    $stmt = $pdo->prepare("
        UPDATE role_management
        SET role = :role,
            department = :department,
            access_navigations = :access,
            controls = :controls
        WHERE r_id = :role_id
    ");

    $ok = $stmt->execute([
        ':role' => $role,
        ':department' => $department,
        ':access' => $access_csv,
        ':controls' => $controls_json,
        ':role_id' => $role_id
    ]);

    if ($ok === false) {
        $err = $stmt->errorInfo();
        dbg("Execute returned false: " . print_r($err, true));
        http_response_code(500);
        echo json_encode([
            'status' => false,
            'message' => 'Database execute failed',
            'error' => $err
        ]);
        exit;
    }

    $affected = $stmt->rowCount();
    dbg("Execute OK. rowCount = {$affected}");

    // If rowCount is 0, it might mean no changes or no matching row
    if ($affected > 0) {
        echo json_encode([
            'status' => true,
            'message' => 'Role updated successfully',
            'affected_rows' => $affected
        ]);
    } else {
        // confirm whether the row exists
        $check = $pdo->prepare("SELECT r_id, role, department, access_navigations, controls FROM role_management WHERE r_id = ?");
        $check->execute([$role_id]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        dbg("Post-update check: " . print_r($existing, true));

        if (!$existing) {
            echo json_encode([
                'status' => false,
                'message' => 'No row found with given role_id (nothing updated).',
                'role_id' => $role_id
            ]);
        } else {
            echo json_encode([
                'status' => true,
                'message' => 'Update executed but 0 rows affected (values may be identical).',
                'affected_rows' => 0,
                'current_row' => $existing
            ]);
        }
    }

} catch (PDOException $e) {
    dbg("PDOException: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
    exit;
}
