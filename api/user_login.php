<?php
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
  
    ini_set('session.cookie_httponly', 1);
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        ini_set('session.cookie_secure', 1);
    }
}

// Detect if request expects JSON (AJAX/Postman)
$isApiRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if ($isApiRequest) {
        http_response_code(405);
        echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    } else {
        $_SESSION['login_error'] = "Method Not Allowed";
        header('Location: login.php');
    }
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    if ($isApiRequest) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
    } else {
        $_SESSION['login_error'] = "Username and password are required";
        header('Location: login.php');
    }
    exit;
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $password === $user['password_hash']) {
        // Successful login
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($isApiRequest) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            // Browser: redirect with JS popup
            echo "<script>alert('Login successful'); window.location.href='../pages/dashboard.php';</script>";        }
    } else {
        if ($isApiRequest) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
        } else {
            echo "<script>alert('Login failed: Invalid username or password'); window.location.href='../pages/login.php';</script>";
        }
    }
} catch (PDOException $e) {
    if ($isApiRequest) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
    } else {
        echo "<script>alert('Server error: " . addslashes($e->getMessage()) . "'); window.location.href='../pages/login.php';</script>";
    }
}
