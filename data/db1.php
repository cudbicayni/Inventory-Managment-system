<?php
// db_connection.php

// Database credentials
$host = 'localhost';     // Usually localhost
$username = 'root';
$password = '';
$database = 'invent';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to utf8
$conn->set_charset("utf8");

// Now you can use $conn for your queries
?>
