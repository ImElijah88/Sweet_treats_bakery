<?php
session_start();
include 'config.php';

$success = '';
$error = '';
$is_admin = isset($_SESSION['admin_logged_in']) ? true : false;
$is_logged_in = isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in']);

// Get menu items for rating selection
$menu_query = "SELECT id, item_name FROM menu_items ORDER BY item_name";
$menu_result = mysqli_query($conn, $menu_query);

if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback_text = $_POST['feedback'];
    $menu_item_id = $_POST['menu_item_id'];
    $rating = $_POST['rating'];
    
    // Check if user is blocked
    $block_check = "SELECT is_blocked FROM users WHERE email = '$email'";
    $block_result = mysqli_query($conn, $block_check);
    
    if ($block_result && mysqli_num_rows($block_result) > 0) {
        $user_data = mysqli_fetch_assoc($block_result);
        if ($user_data['is_blocked'] == 1) {
            $error = 'Your account has been blocked. Please contact us directly for assistance.';
        }
    }
    
    if (!$error && !empty($name) && !empty($email) && !empty($feedback_text) && !empty($rating)) {
        $query = "INSERT INTO feedback (name, email, feedback, menu_item_id, rating, created_at) VALUES ('$name', '$email', '$feedback_text', '$menu_item_id', '$rating', NOW())";
        
        if (mysqli_query($conn, $query)) {
            $success = 'Thank you for your feedback and rating! We appreciate your review.';
        } else {
            $error = 'Error submitting feedback. Please try again.';
        }
    } elseif (!$error) {
        $error = 'Please fill in all fields and select a rating.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Leave Review | Birmingham</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-is-admin="<?php echo $is_admin ? 'true' : 'false'; ?>" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p>Birmingham's Finest Traditional Bakes Since 1985</p>
    </header>
    
    <nav class="user-nav">
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="menu.php">Our Menu</a></li>
            <li><a href="feedback.php">Reviews</a></li>
            <?php if (isset($_SESSION['user_logged_in'])): ?>
                <li><a href="user_dashboard.php">Dashboard</a></li>
                <li><a href="user_logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="index.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div class="container">
        <div class="feedback-form">
            <h2>Rate & Review Our Products</h2>
            <p>Tell us about your experience and rate the product you tried!</p>
            
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="menu_item_id">Which product are you reviewing?</label>
                    <select id="menu_item_id" name="menu_item_id" required>
                        <option value="">Select a product...</option>
                        <?php while ($menu_item = mysqli_fetch_assoc($menu_result)): ?>
                            <option value="<?php echo $menu_item['id']; ?>"><?php echo $menu_item['item_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Your Rating:</label>
                    <div class="star-rating">
                        <span class="star" data-rating="1">★</span>
                        <span class="star" data-rating="2">★</span>
                        <span class="star" data-rating="3">★</span>
                        <span class="star" data-rating="4">★</span>
                        <span class="star" data-rating="5">★</span>
                    </div>
                    <input type="hidden" id="rating" name="rating" required>
                </div>
                
                <div class="form-group">
                    <label for="feedback">Your Review:</label>
                    <textarea id="feedback" name="feedback" rows="5" placeholder="Tell us about your experience with this product..." required></textarea>
                </div>
                
                <button type="submit" class="btn">Submit Review & Rating</button>
            </form>
            
            <p>
                <a href="feedback.php" class="forgot-password">Read other customer reviews</a>
            </p>
        </div>
    </div>
    
    <script src="js/script.js"></script>
    <script>
        // Star rating functionality
        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                ratingInput.value = rating;
                
                stars.forEach((s, index) => {
                    if (index < rating) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>