<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';

$user_name = $_SESSION['full_name'];
$username = $_SESSION['username'];
$success = '';
$error = '';

// Handle profile picture upload
if (isset($_POST['upload_picture'])) {
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $file_extension = strtolower(pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . "profile_" . $_SESSION['user_id'] . "." . $file_extension;
    
    $allowed_types = array("jpg", "jpeg", "png", "gif", "webp");
    
    if (in_array($file_extension, $allowed_types) && $_FILES["profile_picture"]["size"] < 5000000) {
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $update_query = "UPDATE users SET profile_picture = '$target_file' WHERE id = '{$_SESSION['user_id']}'";
            if (mysqli_query($conn, $update_query)) {
                $success = 'Profile picture updated successfully!';
            } else {
                $error = 'Error updating profile picture in database: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Error uploading file.';
        }
    } else {
        $error = 'Invalid file type or size too large (max 5MB).';
    }
}

// Handle profile information update
if (isset($_POST['update_profile'])) {
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dietary_preferences = $_POST['dietary_preferences'];
    $allergens = $_POST['allergens'];
    
    $update_query = "UPDATE users SET phone = '$phone', address = '$address', dietary_preferences = '$dietary_preferences', allergens = '$allergens' WHERE id = '{$_SESSION['user_id']}'";
    
    if (mysqli_query($conn, $update_query)) {
        $success = 'Profile information updated successfully!';
    } else {
        $error = 'Error updating profile information: ' . mysqli_error($conn);
    }
}

// Get user details
$user_data = [];
$user_query = "SELECT * FROM users WHERE id = '{$_SESSION['user_id']}'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    $error = "Error fetching user details: " . mysqli_error($conn);
} else {
    $user_data = mysqli_fetch_assoc($user_result);
}

// Get user's feedback count
$feedback_count = 0;
if (isset($user_data['email'])) {
    $feedback_query = "SELECT COUNT(*) as feedback_count FROM feedback WHERE email = '{$user_data['email']}'";
    $feedback_result = mysqli_query($conn, $feedback_query);

    if (!$feedback_result) {
        $error = "Error fetching feedback count: " . mysqli_error($conn);
    } else {
        $feedback_count_data = mysqli_fetch_assoc($feedback_result);
        $feedback_count = $feedback_count_data['feedback_count'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - My Profile | <?php echo $user_name; ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="true" data-is-admin="false" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet_bakery_youngman.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery</h1>
        <p>Welcome back, <?php echo $user_name; ?>!</p>
    </header>
    
    <div class="container">
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <div class="hero-section" style="<?php echo (isset($user_data['profile_picture']) && $user_data['profile_picture']) ? 'background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url(\''. $user_data['profile_picture'] .'\'); background-size: cover; background-position: center;' : ''; ?>">
            <div class="profile-hero-btn-wrapper">
                <button onclick="document.getElementById('pictureUpload').style.display='block'" class="btn btn-sm">📷 Change Background</button>
            </div>
            <h2>My Profile</h2>
            <p>Manage your account and view your activity</p>
        </div>
        
        <div id="pictureUpload" class="picture-upload-form">
            <h3>Upload Profile Background</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile_picture">Choose Image (JPG, PNG, GIF, WEBP - Max 5MB):</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                </div>
                <button type="submit" name="upload_picture" class="btn">Upload Picture</button>
                <button type="button" onclick="document.getElementById('pictureUpload').style.display='none'" class="btn btn-cancel">Cancel</button>
            </form>
        </div>
        
        <div id="profileForm" class="profile-edit-form">
            <h3>Edit Profile Information</h3>
            <form method="POST">
                <div class="profile-form-grid">
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo $user_data['phone'] ?? ''; ?>" placeholder="0121 123 4567">
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Delivery Address:</label>
                        <textarea id="address" name="address" rows="3" placeholder="123 High Street, Birmingham B1 2AA"><?php echo $user_data['address'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="dietary_preferences">Dietary Preferences:</label>
                        <textarea id="dietary_preferences" name="dietary_preferences" rows="2" placeholder="Vegetarian, Vegan, Gluten-free, etc."><?php echo $user_data['dietary_preferences'] ?? ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="allergens">Allergens to Avoid:</label>
                        <textarea id="allergens" name="allergens" rows="2" placeholder="Nuts, Dairy, Eggs, etc."><?php echo $user_data['allergens'] ?? ''; ?></textarea>
                    </div>
                </div>
                
                <div class="text-center mt-20">
                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                    <button type="button" onclick="document.getElementById('profileForm').style.display='none'" class="btn btn-cancel">Cancel</button>
                </div>
            </form>
        </div>
        
        <div class="features-grid">
            <div class="feature-card">
                <h3>👤 Account Information</h3>
                <p><strong>Name:</strong> <?php echo $user_data['full_name'] ?? ''; ?></p>
                <p><strong>Username:</strong> <?php echo $user_data['username'] ?? ''; ?></p>
                <p><strong>Email:</strong> <?php echo $user_data['email'] ?? ''; ?></p>
                <p><strong>Phone:</strong> <?php echo $user_data['phone'] ?? 'Not provided'; ?></p>
                <p><strong>Member since:</strong> <?php echo (isset($user_data['created_at']) && $user_data['created_at']) ? date('F Y', strtotime($user_data['created_at'])) : 'Not available'; ?></p>
                <button onclick="document.getElementById('profileForm').style.display='block'" class="btn btn-sm mt-15">✏️ Edit Profile</button>
            </div>
            
            <div class="feature-card">
                <h3>🏠 Delivery Information</h3>
                <p><strong>Address:</strong> <?php echo $user_data['address'] ?? 'Not provided'; ?></p>
                <p><strong>Phone:</strong> <?php echo $user_data['phone'] ?? 'Not provided'; ?></p>
                <?php if (isset($user_data['address']) && $user_data['address'] && isset($user_data['phone']) && $user_data['phone']): ?>
                    <p class="delivery-status-ok">✅ Ready for delivery orders</p>
                <?php else: ?>
                    <p class="delivery-status-warning">⚠️ Complete info for delivery</p>
                <?php endif; ?>
            </div>
            
            <div class="feature-card">
                <h3>🥗 Dietary Preferences</h3>
                <p><strong>Preferences:</strong> <?php echo $user_data['dietary_preferences'] ?? 'None specified'; ?></p>
                <p><strong>Allergens:</strong> <?php echo $user_data['allergens'] ?? 'None specified'; ?></p>
                <p class="profile-card-subtitle">We'll recommend products based on your preferences</p>
            </div>
            
            <div class="feature-card">
                <h3>📝 Your Activity</h3>
                <p><strong>Reviews Submitted:</strong> <?php echo $feedback_count; ?></p>
                <p><strong>Account Status:</strong> Active</p>
                <a href="feedback_form.php" class="btn btn-sm mt-15">✍️ Write a Review</a>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 Sweet Treats Bakery, Birmingham. All rights reserved.</p>
        <p>123 High Street, Birmingham B1 2AA | Tel: 0121 123 4567 | Email: hello@sweettreats.co.uk</p>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>
