<?php
// Quick database setup script
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL file
    $sql = file_get_contents('sweet_treats_db.sql');
    $pdo->exec($sql);
    
    echo "<h2>✅ Database setup complete!</h2>";
    echo "<p><a href='index.php'>Go to website</a></p>";
    
} catch(PDOException $e) {
    echo "<h2>❌ Setup failed: " . $e->getMessage() . "</h2>";
    echo "<p>Make sure XAMPP MySQL is running</p>";
}
?>