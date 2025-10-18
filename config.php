<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sweet_treats_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    echo "<h3>Database Setup Required</h3>";
    echo "<p>Or make sure XAMPP MySQL is running</p>";
    exit();
}
?>