<?php
require_once '../config.php'; // PDO connection
session_start();

header('Content-Type: application/json');

try {
    $currentUserId = $_SESSION['user_id'] ?? 1;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        exit;
    }

    $user_id       = trim($_POST['user_id'] ?? '');
    $first_name    = trim($_POST['first_name'] ?? '');
    $last_name     = trim($_POST['last_name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $role_access   = trim($_POST['role_access_id'] ?? '');
    $password      = trim($_POST['password'] ?? '');
    $profile_image = null;

    // Validation
    if (empty($role_access)) {
        echo json_encode(['status' => 'error', 'message' => 'Role/Access is required']);
        exit;
    }

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/brand_manager/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = uniqid('img_') . '_' . basename($_FILES['profile_image']['name']);
        $target = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_image = $filename;
        }
    }

    // Insert into database
    $stmt = $pdo->prepare("
        INSERT INTO brand_manager 
        (user_id, first_name, last_name, email, role_access_id, password, profile_image, created_at, created_by)
        VALUES 
        (:user_id, :first_name, :last_name, :email, :role_access_id, :password, :profile_image, NOW(), :created_by)
    ");

    $status = $stmt->execute([
        ':user_id'       => $user_id,
        ':first_name'    => $first_name,
        ':last_name'     => $last_name,
        ':email'         => $email,
        ':role_access_id'=> $role_access,
        ':password'      => $password,
        ':profile_image' => $profile_image,
        ':created_by'    => $currentUserId,
    ]);

    if ($status) {
        echo json_encode(['status' => 'success', 'message' => 'Brand Manager saved successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error! Please try again.']);
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
