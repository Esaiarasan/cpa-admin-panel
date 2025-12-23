<?php
require_once '../config.php'; // PDO connection
session_start(); // Ensure session is started

$currentUserId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $moodle_id     = trim($_POST['moodle_id'] ?? '');
    $first_name    = trim($_POST['first_name'] ?? '');
    $last_name     = trim($_POST['last_name'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $role_access   = trim($_POST['roles_access'] ?? '');
    $password      = trim($_POST['password'] ?? '');
    $profile_image = null;

    // ✅ Validate required fields
    if (!$role_access) {
        echo "<script>alert('Role/Access is required'); window.history.back();</script>";
        exit;
    }

    // ✅ Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/therapists/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = uniqid('img_') . '_' . basename($_FILES['profile_image']['name']);
        $target = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target)) {
            $profile_image = $filename;
        }
    }

    // ✅ Insert into database (no duplicate restriction)
    $stmt = $pdo->prepare("
        INSERT INTO therapists
        (moodle_id, first_name, last_name, email, role_access_id, password, profile_image, created_at, created_by)
        VALUES (:moodle_id, :first_name, :last_name, :email, :role_access_id, :password, :profile_image, NOW(), :created_by)
    ");

    $stmt->execute([
        ':moodle_id'      => $moodle_id,
        ':first_name'     => $first_name,
        ':last_name'      => $last_name,
        ':email'          => $email,
        ':role_access_id' => $role_access,
        ':password'       => $password,
        ':profile_image'  => $profile_image,
        ':created_by'     => $currentUserId,
    ]);

    // ✅ Success: save session flag and redirect
    $_SESSION['therapist_saved'] = true;
    header("Location: ../pages/therapist.php");
    exit;

} else {
    http_response_code(405);
    echo "<script>alert('Invalid request method'); window.history.back();</script>";
    exit;
}
?>
