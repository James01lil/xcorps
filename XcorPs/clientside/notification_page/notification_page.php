<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../authenticate/db.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("Unauthorized access.");
}

$result = $conn->prepare("SELECT message, created_at FROM notifications WHERE user_id=? ORDER BY created_at DESC");
if (!$result) {
    die("Prepare failed: " . $conn->error);
}

$result->bind_param("i", $user_id);
$result->execute();
$messages = $result->get_result();

?>

<?php

$conn->query("UPDATE notifications SET is_read=1 WHERE user_id=$user_id AND is_read=0");

?>


<!DOCTYPE html>

<html>

<head>

  <title>Your Notifications</title>

  <style>

    body { font-family: Arial; padding: 20px; background: #000000;color:#ddd;font-weight:800;}

    .notification {

      background: #333;

      padding: 15px;

      margin-bottom: 15px;

      border-left: 5px solid #3498db;

      border-radius: 8px;

    }

    .time { font-size: 12px; color: #fff; }
     
/* Hide warning by default */
#desktopWarning {
  display: none;
}

/* Show warning and hide content on screens wider than 500px */
@media (min-width: 501px) {
  #desktopWarning {
    display: flex;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #111;
    color: white;
    font-size: 1.2rem;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
    z-index: 9999;
  }

  #mainContent {
    display: none;
  }
}
  </style>

</head>

<body>

<div id="mainContent">
  <!-- Your actual website content goes here -->
</div>
  <h2>Your Messages</h2>

  <?php while ($row = $messages->fetch_assoc()): ?>

    <div class="notification">

      <div class="time"><?= date('F j, Y H:i', strtotime($row['created_at'])) ?></div>

      <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

    </div>

  <?php endwhile; ?>

</body>

</html>