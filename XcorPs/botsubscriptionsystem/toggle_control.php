<?php
include '../authenticate/db.php';

$account_id = $_POST['account_id'];
$new_state = $_POST['new_state'];

$stmt = $conn->prepare("UPDATE accounts SET toggle_enabled = ? WHERE id = ?");
$stmt->bind_param("ii", $new_state, $account_id);
$stmt->execute();

header("Location: admin_dashboard.php");
exit;
?>