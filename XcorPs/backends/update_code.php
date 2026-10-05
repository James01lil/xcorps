<?php
include '../authenticate/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
  $code = trim($_POST['code']);

  // Allow alphanumeric codes between 10 and 20 characters
  if (preg_match('/^[a-zA-Z0-9]{10,20}$/', $code)) {
    $stmt = $conn->prepare("UPDATE public_value SET code=? WHERE id=1");
    $stmt->bind_param("s", $code);
    $stmt->execute();
  }

  // Redirect back to admin dashboard
  header("Location:admin_dashboard.php");
  exit();
}