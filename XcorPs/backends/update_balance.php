<?php
include 'db.php';
$id = $_POST['id'];
$amount = $_POST['amount'];
$conn->query("UPDATE users SET balance = $amount WHERE id = $id");
header("Location: admin_dashboard.php");
?>
