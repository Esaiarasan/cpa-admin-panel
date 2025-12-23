<?php
require_once '../config.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['administrator_id'])) 
    {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    $administrator_id = $_POST['administrator_id'];
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
        $targetDir = "../uploads/administrator/";
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
        UPDATE administrator 
        SET first_name = ?, last_name = ?, email = ?, role_access_id = ?, user_id = ?, password = ?, profile_image = ?
        WHERE administrator_id = ?
    ");

    $stmt->execute([$first_name, $last_name, $email, $role_access, $user_id, $password, $profile_image, $administrator_id]);

    echo json_encode(['status' => 'success', 'message' => 'Administrator updated successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
