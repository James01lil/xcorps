<?php
$host = 'localhost';
$user = 'capisdlj_wealthbridges';
$pass = 'THEhybrid3002#';
$db   = 'capisdlj_wealthbridges';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);