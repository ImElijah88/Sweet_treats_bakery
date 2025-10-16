<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: index.php');
    exit();
}

include 'config.php';

$success = '';
$error = '';

// Reply to feedback
if (isset($_POST['reply_feedback'])) {
    $feedback_id = $_POST['feedback_id'];
    $admin_reply = $_POST['admin_reply'];
    
    if (!empty($admin_reply)) {
        $query = "UPDATE feedback SET admin_reply = '$admin_reply', replied_at = NOW() WHERE id = '$feedback_id'";
        if (mysqli_query($conn, $query)) {
            $success = 'Reply sent!';
        } else {
            $error = 'Error sending reply.';
        }
    }
}

// Delete feedback
if (isset($_GET['delete'])) {
    $feedback_id = $_GET['delete'];
    $query = "DELETE FROM feedback WHERE id = $feedback_id";
    if (mysqli_query($conn, $query)) {
        $success = 'Deleted!';
    }
}

$query = "SELECT f.id, f.name, f.email, f.feedback, f.admin_reply, f.created_at, f.replied_at, f.rating, f.menu_item_id, m.item_name, m.image_url,
          (SELECT AVG(rating) FROM feedback WHERE menu_item_id = f.menu_item_id AND rating IS NOT NULL) as avg_rating
          FROM feedback f 
          LEFT JOIN menu_items m ON f.menu_item_id = m.id 
          ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Manage Feedback</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="true" data-is-admin="true" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('images/sweet.webp'); background-size: cover; background-position: center; background-attachment: fixed;">
    <header>
        <h1>Sweet Treats Bakery - Admin</h1>
        <p>Manage Feedback</p>
    </header>
    
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 30px;">Customer Feedback</h2>
        
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="product-review-card">
                    <?php if (isset($row['item_name']) && $row['item_name']): ?>
                        <div class="product-review-header">
                            <?php if (isset($row['image_url']) && $row['image_url']): ?>
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>" class="product-review-image">
                            <?php endif; ?>
                            <div class="product-review-info">
                                <h3><?php echo $row['item_name']; ?></h3>
                                <?php if (isset($row['avg_rating']) && $row['avg_rating']): ?>
                                    <div class="rating-display">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="rating-star <?php echo $i <= round($row['avg_rating']) ? '' : 'empty'; ?>">★</span>
                                        <?php endfor; ?>
                                        <span class="rating-value"><?php echo number_format($row['avg_rating'], 1); ?>/5</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="reviews-section">
                        <div class="review-item">
                            <div class="review-author">
                                <strong><?php echo $row['name']; ?></strong>
                                <?php if (isset($row['rating']) && $row['rating']): ?>
                                    <div class="rating-display">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="rating-star <?php echo $i <= $row['rating'] ? '' : 'empty'; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="review-text"><?php echo $row['feedback']; ?></p>
                            <small class="review-date"><?php echo date('F j, Y', strtotime($row['created_at'])); ?></small>
                            
                            <div class="admin-actions">
                                <button onclick="toggleReplyForm('admin_<?php echo $row['id']; ?>')" class="icon-btn" title="Reply">💬</button>
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')" class="icon-btn" title="Delete">🗑️</a>
                            </div>
                        </div>
                        
                        <?php if (isset($row['admin_reply']) && $row['admin_reply']): ?>
                            <div class="admin-reply">
                                <p><strong>Your Reply:</strong> <?php echo $row['admin_reply']; ?></p>
                                <small><?php echo isset($row['replied_at']) ? date('F j, Y', strtotime($row['replied_at'])) : ''; ?></small>
                            </div>
                        <?php endif; ?>
                        
                        <div id="replyForm_admin_<?php echo $row['id']; ?>" class="reply-form-box">
                            <form method="POST">
                                <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                                <textarea name="admin_reply" rows="2" placeholder="Reply to <?php echo $row['name']; ?>..." required></textarea>
                                <div class="reply-form-actions">
                                    <button type="submit" name="reply_feedback" class="btn btn-sm">Send</button>
                                    <button type="button" onclick="toggleReplyForm('admin_<?php echo $row['id']; ?>')" class="btn btn-sm btn-cancel">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 40px;">
                <p>No feedback yet.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy; 2024 Sweet Treats Bakery, Birmingham. All rights reserved.</p>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>
