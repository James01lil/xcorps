<?php
session_start();

if (!isset($_SESSION['impersonating']) || $_SESSION['impersonating'] !== true) {
    die("You are not impersonating.");
}

// Remove impersonation session
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['impersonating']);

// Restore admin session
$_SESSION['admin_id'] = $_SESSION['original_admin_id'];
unset($_SESSION['original_admin_id']);

header("Location: admin_dashboard.php");
exit;
?>