<?php
include 'config.php';

$success = '';
$error = '';

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    
    if (!empty($username) && !empty($email) && !empty($password) && !empty($full_name)) {
        $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Username or email already exists';
        } else {
            $query = "INSERT INTO users (username, email, password, full_name) VALUES ('$username', '$email', '$password', '$full_name')";
            if (mysqli_query($conn, $query)) {
                $success = 'Registration successful! You can now login.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Birmingham | Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="false" data-is-admin="false" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery_youngman.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p>Birmingham's Cyberpunk Bakery Since 1885</p>
    </header>
    
    <nav class="user-nav">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="menu.php">Our Menu</a></li>
            <li><a href="feedback.php">Reviews</a></li>
            <li><a href="index.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <div class="register-form">
            <h2>Join Our Bakery Family</h2>
            <p class="form-subtitle">Create an account to enjoy exclusive offers and faster ordering</p>
            
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="password-toggle" onclick="togglePassword('password')">👁️</span>
                    </div>
                </div>
                
                <button type="submit" class="btn">Register</button>
            </form>
            
            <p class="text-center mt-20">
                Already have an account? <a href="index.php" class="forgot-password">Login here</a>
            </p>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>