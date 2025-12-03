<?php
$host = "localhost";
$user = "root";
$pass = "";       // XAMPP default
$dbname = "agri_nova";

// Create mysqli connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset("utf8mb4");
?>
