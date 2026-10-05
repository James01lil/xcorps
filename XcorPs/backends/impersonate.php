<?php
session_start();
include '../authenticate/db.php';

// Only allow if the user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    die("Unauthorized.");
}

if (!isset($_GET['id'])) {
    die("User ID missing.");
}

$user_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT id, user_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($uid, $uname);

if ($stmt->fetch()) {
    $_SESSION['original_admin_id'] = $_SESSION['admin_id'];
    $_SESSION['impersonating'] = true;

    $_SESSION['user_id'] = $uid;
    $_SESSION['user_name'] = $uname;

    header("Location: ../clientside/dashboard/dashboard.php");
    exit;
} else {
    die("User not found.");
}