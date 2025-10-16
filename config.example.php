<?php
// Database configuration template
// Copy this file to config.php and update with your credentials

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sweet_treats_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
