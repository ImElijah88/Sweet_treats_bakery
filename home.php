<?php 
session_start();
$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : null;
$is_admin = isset($_SESSION['admin_logged_in']) ? true : false;
$is_logged_in = isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Birmingham's Finest Traditional Bakes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-is-admin="<?php echo $is_admin ? 'true' : 'false'; ?>" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/cyberSweetTreats.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p><?php echo $user_name ? "Welcome back, $user_name!" : "Birmingham's Cyberpunk Bakery Since 1885 ⚡"; ?></p>
    </header>
    
    <div class="container">
        <div class="hero-section">
            <h2>Welcome to Birmingham's Premier Bakery</h2>
            <p>⚡ Blending Victorian tradition with Cyberpunk innovation since 1885 ⚡</p>
            <a href="menu.php" class="btn">Explore Our Cyber Menu</a>
        </div>
        
        <div class="features-grid">
            <div class="feature-card traditional-bakes">
                <h3>⚡ Cyberpunk Creations</h3>
                <p>Neon-glazed pastries and futuristic treats blending Victorian recipes with modern innovation</p>
                <a href="menu.php" class="btn">View Menu</a>
            </div>
            
            <div class="feature-card fresh-daily">
                <h3>🏆 Award Winning</h3>
                <p>50+ national awards for excellence and innovation in baking</p>
                <a href="menu.php" class="btn">See Our Menu</a>
            </div>
            
            <div class="feature-card customer-reviews">
                <h3>⭐ Customer Reviews</h3>
                <p>See what our Birmingham customers say about our treats</p>
                <a href="feedback.php" class="btn">Read Reviews</a>
            </div>
        </div>
        
        <div class="about-section">
            <video id="story-video" class="video-background" muted>
                <source src="images/video1.mp4" type="video/mp4">
            </video>
            <div class="video-overlay"></div>
        </div>
        
        <div class="white-section">
            <h3>Visit Us</h3>
            <div class="about-contact-grid">
                <div>
                    <h4>📍 Location</h4>
                    <p>123 High Street<br>Birmingham B1 2AA</p>
                </div>
                <div>
                    <h4>📞 Contact</h4>
                    <p>Tel: 0121 123 4567<br>hello@sweettreats.co.uk</p>
                </div>
                <div>
                    <h4>🕒 Hours</h4>
                    <p>Mon-Sat: 7AM-6PM<br>Sunday: 8AM-4PM</p>
                </div>
            </div>
        </div>
        

    </div>
    
    <footer>
        <p>&copy; 2024 Sweet Treats Bakery, Birmingham. All rights reserved.</p>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>