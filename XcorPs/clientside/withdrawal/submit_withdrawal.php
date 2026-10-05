<?php
include("db.php");

$user_id = $_POST['user_id'];
$amount = $_POST['amount'];
$method = $_POST['method'];
$destination = $_POST['destination'];

$conn->query("INSERT INTO withdrawals (user_id, amount, method, destination) 
VALUES ('$user_id', '$amount', '$method', '$destination')");
?>
