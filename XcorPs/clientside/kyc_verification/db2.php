<?php
$host = "127.0.0.1";         // or your host (e.g. fdb1028.awardspace.net)
$dbname = "Xdb";   // your actual database name
$username = "root"; // your MySQL username
$password = "root"; // your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>