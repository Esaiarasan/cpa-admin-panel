<?php

require_once '../config.php'; // for $pdo

$currentUserId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id    = $_POST['user_id'] ?? '';
    $first_name   = $_POST['first_name'] ?? '';
    $last_name    = $_POST['last_name'] ?? '';
    $email        = $_POST['email'] ?? '';
    $role_access  = $_POST['roles_access'] ?? '';
    $password     = $_POST['password'] ?? '';
    $profile_image = null;

    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/administrator/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = uniqid('img_') . '_' . basename($_FILES['profile_image']['name']);
        $target = $upload_dir . $filename;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_image = $filename;
        }
    }

    // Insert into DB
    $stmt = $pdo->prepare("
        INSERT INTO administrator
        (user_id, first_name, last_name, email, role_access_id, password, profile_image, created_at, created_by)
        VALUES (:user_id, :first_name, :last_name, :email, :role_access_id, :password, :profile_image, NOW(), :created_by)
    ");

    $status = $stmt->execute([
        ':user_id'      => $user_id,
        ':first_name'     => $first_name,
        ':last_name'      => $last_name,
        ':email'          => $email,
        ':role_access_id' => $role_access,
        ':password'       => $password,
        ':profile_image'  => $profile_image,
        ':created_by'     => $currentUserId,
    ]);

   if ($status) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Administrator saved successfully'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to save administrator'
    ]);
}

} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
