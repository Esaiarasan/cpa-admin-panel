<?php
require_once __DIR__ . '/../config.php';
// session_start();


$login_error = '';
$login_success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $login_error = "Please fill in all fields.";
    } else {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

            $stmt = $pdo->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $password === $user['password_hash']) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Success message
                $login_success = "Login successful! Welcome, " . htmlspecialchars($user['username']);
            } else {
                $login_error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $login_error = "Server error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - CPAssist</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>assets/css/login.css?v=7.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css?v=7.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css?v=7.0">
</head>
<body>
    <!-- ====== Left Side ====== -->
    <div class="left-panel">
        <img src="<?php echo SITE_URL; ?>assets/images/logo.png" alt="Logo">
    </div>

    <!-- ====== Right Side ====== -->
    <div class="right-panel">
        <div class="login-box">
            <h2>Login</h2>

            <!-- Server-side Popups -->
            <?php if($login_error): ?>
                <div id="login-popup-error" class="alert alert-danger fade show" role="alert" style="position:relative;">
                    <?php echo $login_error; ?>
                </div>
            <?php endif; ?>

            <?php if($login_success): ?>
                <div id="login-popup-success" class="alert alert-success fade show" role="alert" style="position:relative;">
                    <?php echo $login_success; ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" onsubmit="return validateForm()">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" autocomplete="username" placeholder="Enter your username" required>

                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Login</button>
            </form>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js?v=7.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js?v=7.0"></script>
    <script>
    function togglePassword() {
        const pass = document.getElementById("password");
        const icon = document.querySelector(".toggle-password i");
        if (pass.type === "password") {
            pass.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            pass.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    }

    function validateForm() {
        const user = document.getElementById("username").value.trim();
        const pass = document.getElementById("password").value.trim();
        if (!user || !pass) {
            showPopup("Please fill in all fields.", 'error');
            return false;
        }
        return true;
    }

    function showPopup(message, type = 'error') {
        let popupId = type === 'success' ? 'login-popup-success' : 'login-popup-error';
        let popup = document.getElementById(popupId);
        if (!popup) {
            popup = document.createElement('div');
            popup.id = popupId;
            popup.className = `alert alert-${type === 'success' ? 'success' : 'danger'} fade show`;
            popup.innerText = message;
            document.querySelector('.login-box').prepend(popup);
        }
        setTimeout(() => {
            popup.classList.remove('show');
            popup.classList.add('hide');
            if(type === 'success'){
                window.location.href = '../pages/dashboard.php';
            }
        }, 100); // success popup shows for 2 seconds then redirect
    }

    // Auto hide server-side error popup
    window.onload = function() {
        const errorPopup = document.getElementById('login-popup-error');
        if(errorPopup){
            setTimeout(() => {
                errorPopup.classList.remove('show');
                errorPopup.classList.add('hide');
            }, 100);
        }

        const successPopup = document.getElementById('login-popup-success');
        if(successPopup){
            setTimeout(() => {
                successPopup.classList.remove('show');
                successPopup.classList.add('hide');
                window.location.href = '../pages/dashboard.php';
            }, 100);
        }
    }
    </script>
</body>
</html>
