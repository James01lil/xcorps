<?php
include '../authenticate/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
  // Optional: confirm id is a number
  $id = (int)$id;

  // Permanently delete user
  $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: admin_dashboard.php");
exit();
?>