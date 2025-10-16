<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';

$success = '';
$error = '';

// Add new menu item
if (isset($_POST['add_item'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Handle image
    if (isset($_FILES['image_input']) && $_FILES['image_input']['error'] == 0 && $_FILES['image_input']['size'] > 0) {
        $upload_dir = "images/";
        $file_extension = strtolower(pathinfo($_FILES["image_input"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif", "webp");
        
        if (in_array($file_extension, $allowed_types) && $_FILES["image_input"]["size"] < 5000000) {
            $image_url = $upload_dir . basename($_FILES["image_input"]["name"]);
            move_uploaded_file($_FILES["image_input"]["tmp_name"], $image_url);
        } else {
            $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
        }
    } else {
        $image_url = !empty($_POST['image_url']) ? mysqli_real_escape_string($conn, $_POST['image_url']) : '';
    }
    
    // Determine stock status based on quantity
    if ($quantity == 0) {
        $stock_status = 'Out of Stock';
    } elseif ($quantity <= 5) {
        $stock_status = 'Low Stock';
    } else {
        $stock_status = 'In Stock';
    }
    
    if (!empty($item_name) && !empty($description) && !empty($price) && isset($quantity)) {
        $query = "INSERT INTO menu_items (item_name, description, price, quantity, stock_status, image_url) VALUES ('$item_name', '$description', '$price', '$quantity', '$stock_status', '$image_url')";
        if (mysqli_query($conn, $query)) {
            header('Location: manage_menu.php?added=1');
            exit();
        } else {
            $error = 'Error: ' . mysqli_error($conn);
        }
    } else {
        $error = 'Fill all fields.';
    }
}

if (isset($_GET['added'])) {
    $success = 'Item added!';
}

// Edit menu item
if (isset($_POST['edit_item'])) {
    $item_id = intval($_POST['item_id']);
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Handle image
    if (isset($_FILES['image_input']) && $_FILES['image_input']['error'] == 0 && $_FILES['image_input']['size'] > 0) {
        $upload_dir = "images/";
        $file_extension = strtolower(pathinfo($_FILES["image_input"]["name"], PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif", "webp");
        
        if (in_array($file_extension, $allowed_types) && $_FILES["image_input"]["size"] < 5000000) {
            $image_url = $upload_dir . basename($_FILES["image_input"]["name"]);
            move_uploaded_file($_FILES["image_input"]["tmp_name"], $image_url);
        } else {
            $image_url = mysqli_real_escape_string($conn, $_POST['image_url']);
        }
    } else {
        $image_url = !empty($_POST['image_url']) ? mysqli_real_escape_string($conn, $_POST['image_url']) : '';
    }
    
    if ($quantity == 0) {
        $stock_status = 'Out of Stock';
    } elseif ($quantity <= 5) {
        $stock_status = 'Low Stock';
    } else {
        $stock_status = 'In Stock';
    }
    
    $query = "UPDATE menu_items SET item_name='$item_name', description='$description', price='$price', quantity='$quantity', stock_status='$stock_status', image_url='$image_url' WHERE id=$item_id";
    if (mysqli_query($conn, $query)) {
        header('Location: manage_menu.php?edited=1');
        exit();
    } else {
        $error = 'Error: ' . mysqli_error($conn);
    }
}

if (isset($_GET['edited'])) {
    $success = 'Item updated!';
}

$edit_item = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_query = "SELECT * FROM menu_items WHERE id = $edit_id";
    $edit_result = mysqli_query($conn, $edit_query);
    $edit_item = mysqli_fetch_assoc($edit_result);
}

// Update stock
if (isset($_POST['update_stock'])) {
    $item_id = $_POST['item_id'];
    $new_quantity = $_POST['new_quantity'];
    
    // Determine stock status based on quantity
    if ($new_quantity == 0) {
        $stock_status = 'Out of Stock';
    } elseif ($new_quantity <= 5) {
        $stock_status = 'Low Stock';
    } else {
        $stock_status = 'In Stock';
    }
    
    $query = "UPDATE menu_items SET quantity = '$new_quantity', stock_status = '$stock_status' WHERE id = '$item_id'";
    if (mysqli_query($conn, $query)) {
        $success = 'Stock updated successfully!';
    } else {
        $error = 'Error updating stock.';
    }
}

// Delete menu item
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Delete related feedback first
    mysqli_query($conn, "DELETE FROM feedback WHERE menu_item_id = $id");
    
    // Delete menu item
    $query = "DELETE FROM menu_items WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header('Location: manage_menu.php?deleted=1');
        exit();
    } else {
        $error = 'Error: ' . mysqli_error($conn);
    }
}

if (isset($_GET['deleted'])) {
    $success = 'Deleted!';
}

// Get all menu items
$query = "SELECT * FROM menu_items ORDER BY stock_status ASC, id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Manage Menu | Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="true" data-is-admin="true" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery - Admin Panel</h1>
        <p>Manage Birmingham's finest bakery</p>
    </header>
    
    <nav class="admin-nav">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_menu.php">Manage Menu</a></li>
            <li><a href="view_feedback.php">View Reviews</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <h2 class="text-center mb-30">Manage Daily Menu</h2>
        
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <div class="menu-form">
            <h3><?php echo $edit_item ? 'Edit item' : 'Add new item'; ?></h3>
            <form method="POST" enctype="multipart/form-data">
                <?php if ($edit_item): ?>
                    <input type="hidden" name="item_id" value="<?php echo $edit_item['id']; ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="item_name">Name:</label>
                    <input type="text" id="item_name" name="item_name" value="<?php echo $edit_item ? htmlspecialchars($edit_item['item_name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" rows="3" required><?php echo $edit_item ? htmlspecialchars($edit_item['description']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="price">Price (£):</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $edit_item ? $edit_item['price'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $edit_item ? $edit_item['quantity'] : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Image (URL or upload):</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <input type="text" id="image_url" name="image_url" placeholder="images/item-name.jpg" value="<?php echo $edit_item ? $edit_item['image_url'] : ''; ?>" style="flex: 1;">
                        <input type="file" id="image_file" name="image_input" accept=".jpg,.jpeg,.png,.gif,.webp" style="display: none;" onchange="if(this.files[0]) document.getElementById('image_url').value = 'images/' + this.files[0].name">
                        <button type="button" onclick="document.getElementById('image_file').click()" style="padding: 12px 15px; background: var(--btn-bg); color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 18px;">➕</button>
                    </div>
                </div>
                
                <button type="submit" name="<?php echo $edit_item ? 'edit_item' : 'add_item'; ?>" class="btn"><?php echo $edit_item ? 'Update Item' : 'Add Menu Item'; ?></button>
                <?php if ($edit_item): ?>
                    <a href="manage_menu.php" class="btn btn-cancel" style="margin-left: 10px;">Cancel</a>
                <?php endif; ?>
            </form>
        </div>
        
        <h3 class="mt-40 mb-20">Current menu items</h3>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="menu-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="menu-item">
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>">
                        <?php endif; ?>
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
                            
                            <form method="POST" class="update-stock-form">
                                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                <div>
                                    <input type="number" name="new_quantity" value="<?php echo $row['quantity']; ?>" min="0">
                                    <button type="submit" name="update_stock" class="btn btn-update">Update</button>
                                </div>
                            </form>
                            
                            <div style="display: flex; gap: 5px;">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-update" style="flex: 1; text-align: center;">✏️ Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="btn btn-delete" style="flex: 1;">🗑️</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="no-items">No menu items yet. Add some items above!</p>
        <?php endif; ?>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>