<?php
session_start();
include 'config.php';

$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$query = "SELECT * FROM menu_items WHERE item_name LIKE '%$search%' OR description LIKE '%$search%' ORDER BY stock_status ASC, id DESC";
$result = mysqli_query($conn, $query);

$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : null;
$is_admin = isset($_SESSION['admin_logged_in']) ? true : false;
$is_logged_in = isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Today's Menu | Birmingham</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-is-admin="<?php echo $is_admin ? 'true' : 'false'; ?>" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p>Birmingham's Finest Traditional Bakes Since 1885</p>
    </header>
    
    <div class="container">
        <h2 class="page-title">Today's Fresh Menu</h2>
        <p class="page-subtitle">
            All items freshly baked this morning using traditional British recipes
        </p>
        
        <div class="search-box">
            <form method="GET">
                <input type="text" name="search" placeholder="Search our delicious treats..." value="<?php echo $search; ?>">
                <button type="submit" class="btn">Search</button>
            </form>
        </div>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="menu-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="menu-item">
                        <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>" onerror="this.src='images/default.jpg'">
                        <div class="menu-content">
                            <h3><?php echo $row['item_name']; ?></h3>
                            <p><?php echo $row['description']; ?></p>
                            <p class="price">£<?php echo number_format($row['price'], 2); ?></p>
                            
                            <div class="stock-info">
                                <span class="quantity">Quantity: <?php echo $row['quantity']; ?></span>
                                <span class="stock-status <?php echo strtolower(str_replace(' ', '-', $row['stock_status'])); ?>">
                                    <?php echo $row['stock_status']; ?>
                                </span>
                            </div>
                            
                            <?php if ($row['stock_status'] == 'Out of Stock'): ?>
                                <p class="stock-message out-of-stock">
                                    Sorry, this item is currently unavailable
                                </p>
                            <?php elseif ($row['stock_status'] == 'Low Stock'): ?>
                                <p class="stock-message low-stock">
                                    Hurry! Only <?php echo $row['quantity']; ?> left
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <h3>
                    <?php if ($search): ?>
                        No items found matching "<?php echo $search; ?>"
                    <?php else: ?>
                        No menu items available today
                    <?php endif; ?>
                </h3>
                <p>
                    <?php if ($search): ?>
                        Try searching for something else or browse our full menu
                    <?php else: ?>
                        Please check back later for fresh bakes!
                    <?php endif; ?>
                </p>
                <?php if ($search): ?>
                    <a href="menu.php" class="btn mt-20">View All Items</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="white-section text-center">
            <h3>Visit Our Bakery</h3>
            <p class="subtitle">
                <strong>123 High Street, Birmingham B1 2AA</strong><br>
                Tel: 0121 123 4567<br>
                Open: Mon-Sat 7AM-6PM, Sun 8AM-4PM
            </p>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Sweet Treats Bakery, Birmingham. All rights reserved.</p>
        <p>123 High Street, Birmingham B1 2AA | Tel: 0121 123 4567</p>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>