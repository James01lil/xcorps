
<?php
include("../db.php");
$id = $_GET['id'];
$status = $_GET['status'];

$conn->query("UPDATE withdrawals SET status = '$status' WHERE id = $id");
header("Location: dashboard.php");
?>
