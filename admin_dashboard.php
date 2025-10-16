<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="true" data-is-admin="true" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery_youngman.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery </h1>
        <p>Manage your bakery</p>
    </header>
    
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 30px;">Admin Dashboard</h2>
        
        <div class="admin-dashboard-grid">
            <div class="admin-card">
                <h3>Manage Daily Menu</h3>
                <p>Add, edit, or remove menu items </p>
                <a href="manage_menu.php" class="btn">Manage Menu</a>
            </div>
            
            <div class="admin-card">
                <h3>Customer Feedback</h3>
                <p>View and manage customer feedback</p>
                <a href="view_feedback.php" class="btn">View Feedback</a>
            </div>
        </div>
        
        <div class="logout-section">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>