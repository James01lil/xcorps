<?php
require 'db.php';

$input = $_POST['code'] ?? '';
$stmt = $pdo->query("SELECT code, success_message FROM coupons ORDER BY id DESC LIMIT 1");
$coupon = $stmt->fetch();

if ($coupon && $input === $coupon['code']) {
    echo json_encode(['valid' => true, 'message' => $coupon['success_message']]);
} else {
    echo json_encode(['valid' => false]);
}