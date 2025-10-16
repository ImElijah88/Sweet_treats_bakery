<?php
session_start();
include 'config.php';

$success = '';
$error = '';

// Handle user reply submission
if (isset($_POST['submit_reply']) && isset($_SESSION['user_logged_in'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply_text = $_POST['reply_text'];
    $user_id = $_SESSION['user_id'];
    
    if (!empty($reply_text)) {
        $reply_query = "INSERT INTO user_replies (feedback_id, user_id, reply_text, created_at) VALUES ('$feedback_id', '$user_id', '$reply_text', NOW())";
        if (mysqli_query($conn, $reply_query)) {
            $success = 'Reply posted successfully!';
        } else {
            $error = 'Error posting reply.';
        }
    } else {
        $error = 'Please enter a reply message.';
    }
}

$query = "SELECT f.id as feedback_id, f.name, f.email, f.feedback, f.admin_reply, f.created_at, f.replied_at, f.rating, m.item_name, m.image_url,
          (SELECT AVG(rating) FROM feedback WHERE menu_item_id = f.menu_item_id AND rating IS NOT NULL) as avg_rating,
          (SELECT COUNT(*) FROM feedback WHERE menu_item_id = f.menu_item_id AND rating IS NOT NULL) as rating_count
          FROM feedback f 
          LEFT JOIN menu_items m ON f.menu_item_id = m.id 
          ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<?php
$is_admin = isset($_SESSION['admin_logged_in']) ? true : false;
$is_logged_in = isset($_SESSION['user_logged_in']) || isset($_SESSION['admin_logged_in']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery - Customer Reviews | Birmingham</title>
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
        <h2 style="text-align: center; margin-bottom: 30px;">What Our Customers Say</h2>
        
        <div style="text-align: center; margin-bottom: 30px;">
            <a href="feedback_form.php" class="btn">Leave Your Review & Rating</a>
        </div>
        
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
                                <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['item_name']; ?>" class="product-review-image" onerror="this.style.display='none'">
                            <?php endif; ?>
                            <div class="product-review-info">
                                <h3><?php echo $row['item_name']; ?></h3>
                                <?php if (isset($row['avg_rating']) && $row['avg_rating']): ?>
                                    <div class="rating-display">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="rating-star <?php echo $i <= round($row['avg_rating']) ? '' : 'empty'; ?>">★</span>
                                        <?php endfor; ?>
                                        <span class="rating-value"><?php echo number_format($row['avg_rating'], 1); ?>/5 (<?php echo $row['rating_count']; ?> reviews)</span>
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
                        </div>
                        
                        <?php if (isset($row['admin_reply']) && $row['admin_reply']): ?>
                            <div class="admin-reply">
                                <p><strong>Sweet Treats Bakery:</strong> <?php echo $row['admin_reply']; ?></p>
                                <small>Replied: <?php echo isset($row['replied_at']) ? date('F j, Y', strtotime($row['replied_at'])) : ''; ?></small>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($_SESSION['user_logged_in'])): ?>
                            <div class="reply-actions">
                                <button onclick="toggleReplyForm('main_<?php echo $row['feedback_id']; ?>')" class="btn btn-sm">💬 Reply</button>
                            </div>
                            <div id="replyForm_main_<?php echo $row['feedback_id']; ?>" class="reply-form-box">
                                <form method="POST">
                                    <input type="hidden" name="feedback_id" value="<?php echo $row['feedback_id']; ?>">
                                    <textarea name="reply_text" rows="2" placeholder="Reply to <?php echo $row['name']; ?>... 😊" required></textarea>
                                    <div class="emoji-picker">
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">😊</span>
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">👍</span>
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">❤️</span>
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">😋</span>
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">🎉</span>
                                        <span onclick="addEmoji(this, 'main_<?php echo $row['feedback_id']; ?>')">👏</span>
                                    </div>
                                    <div class="reply-form-actions">
                                        <button type="submit" name="submit_reply" class="btn btn-sm">Post Reply</button>
                                        <button type="button" onclick="toggleReplyForm('main_<?php echo $row['feedback_id']; ?>')" class="btn btn-sm btn-cancel">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        $replies_query = "SELECT ur.id as reply_id, ur.reply_text, ur.created_at, u.full_name, u.username, f.name as original_author FROM user_replies ur JOIN users u ON ur.user_id = u.id JOIN feedback f ON ur.feedback_id = f.id WHERE ur.feedback_id = '{$row['feedback_id']}' ORDER BY ur.created_at ASC";
                        $replies_result = mysqli_query($conn, $replies_query);
                        
                        if (mysqli_num_rows($replies_result) > 0):
                        ?>
                            <div class="replies-section">
                                <?php while ($reply = mysqli_fetch_assoc($replies_result)): ?>
                                    <div class="reply-item">
                                        <p><strong><?php echo $reply['username']; ?></strong> <span class="reply-to">@<?php echo $reply['original_author']; ?></span></p>
                                        <p class="reply-text"><?php echo $reply['reply_text']; ?></p>
                                        <small><?php echo date('F j, Y g:i A', strtotime($reply['created_at'])); ?></small>
                                        <?php if (isset($_SESSION['user_logged_in'])): ?>
                                            <button onclick="toggleReplyForm('reply_<?php echo $reply['reply_id']; ?>')" class="btn-reply-small">Reply</button>
                                            <div id="replyForm_reply_<?php echo $reply['reply_id']; ?>" class="reply-form-box">
                                                <form method="POST">
                                                    <input type="hidden" name="feedback_id" value="<?php echo $row['feedback_id']; ?>">
                                                    <textarea name="reply_text" rows="2" placeholder="Reply to <?php echo $reply['full_name']; ?>... 😊" required></textarea>
                                                    <div class="emoji-picker">
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">😊</span>
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">👍</span>
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">❤️</span>
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">😋</span>
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">🎉</span>
                                                        <span onclick="addEmoji(this, 'reply_<?php echo $reply['reply_id']; ?>')">👏</span>
                                                    </div>
                                                    <div class="reply-form-actions">
                                                        <button type="submit" name="submit_reply" class="btn btn-sm">Post Reply</button>
                                                        <button type="button" onclick="toggleReplyForm('reply_<?php echo $reply['reply_id']; ?>')" class="btn btn-sm btn-cancel">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 40px;">
                <p style="font-size: 18px; color: #666;">No reviews yet. Be the first to share your experience!</p>
            </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <p>&copy; 2024 Sweet Treats Bakery, Birmingham. All rights reserved.</p>
        <p>123 High Street, Birmingham B1 2AA | Tel: 0121 123 4567</p>
    </footer>
    
    <script src="js/script.js"></script>
</body>
</html>