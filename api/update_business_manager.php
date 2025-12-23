<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['business_manager_id'])) 
    {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    $business_manager_id = $_POST['business_manager_id'];
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role_access = $_POST['roles_access'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $password = $_POST['password'] ?? '';
    $existing_image = $_POST['existing_image'] ?? '';
    $profile_image = $existing_image;

    // ✅ Handle image upload if new file provided
    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "../uploads/business_manager/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $profile_image = $fileName;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image']);
            exit;
        }
    }

    $stmt = $pdo->prepare("
        UPDATE business_manager 
        SET first_name = ?, last_name = ?, email = ?, role_access_id = ?, user_id = ?, password = ?, profile_image = ?
        WHERE business_manager_id = ?
    ");

    $stmt->execute([$first_name, $last_name, $email, $role_access, $user_id, $password, $profile_image, $business_manager_id]);

    echo json_encode(['status' => 'success', 'message' => 'Business Manager updated successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
