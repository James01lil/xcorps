<?php
require 'db.php';

$code = $_POST['code'];
$message = $_POST['message'];

$stmt = $pdo->prepare("INSERT INTO coupons (code, success_message) VALUES (?, ?)");
$stmt->execute([$code, $message]);

header("Location:../backends/admin_dashboard.php");
//header("Location:panel.php");
exit;