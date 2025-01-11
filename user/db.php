<?php
// Database connection details
$servername = "localhost";  // Your database server, usually localhost
$username = "root";         // Your MySQL username (default is root)
$password = "";             // Your MySQL password (leave empty if none set)
$dbname = "watch_store";    // Your database name

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Display error if connection fails
}
?>
