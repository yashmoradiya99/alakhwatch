<?php
$host = 'localhost';
$user = 'root'; // Default for XAMPP
$password = ''; // Default is empty
$database = 'watch_store'; // Database name you created

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>
