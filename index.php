<?php
session_start();
include 'config.php';

$error = '';

// Handle remember me functionality
if (isset($_COOKIE['remember_user']) && !isset($_SESSION['user_logged_in']) && !isset($_SESSION['admin_logged_in'])) {
    $remembered_user = $_COOKIE['remember_user'];
    $query = "SELECT * FROM users WHERE username = '$remembered_user'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if ($user['is_admin'] == 1) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin_dashboard.php');
        } else {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            header('Location: my_profile.php');
        }
        exit();
    }
}

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember_me']);
    
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if ($user['is_blocked'] == 1) {
            $error = 'Your account has been blocked. Please contact us for assistance.';
        } else {
            if ($remember) {
                setcookie('remember_user', $username, time() + (86400 * 30), "/");
            }
            
            if ($user['is_admin'] == 1) {
                $_SESSION['admin_logged_in'] = true;
                header('Location: admin_dashboard.php');
            } else {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                header('Location: my_profile.php');
            }
            exit();
        }
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Birmingham | Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="false" data-is-admin="false" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery_youngman.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p>Birmingham's Cyberpunk Bakery Since 1885</p>
    </header>
    
    <div class="container">
        <div class="login-form">
            <h2>Welcome Back</h2>
            <p class="text-center mb-20">Sign in to your account</p>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    </div>
                </div>
                
                <div class="login-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember_me" name="remember_me">
                        <label for="remember_me">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password" onclick="alert('Please contact us at hello@sweettreats.co.uk for password recovery.')">Forgot password?</a>
                </div>
                
                <button type="submit" class="btn">Sign In</button>
            </form>
            
            <p class="mt-20 text-center">
                Don't have an account? <a href="register.php" class="forgot-password">Create one here</a>
            </p>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>