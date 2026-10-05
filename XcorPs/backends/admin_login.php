<?php
session_start();
include '../authenticate/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin) {
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_id'] = $admin['id'];
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Invalid login.";
}
?>