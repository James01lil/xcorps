<?php

include 'db.php';

session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {

  die("Unauthorized access.");

}

$result = $conn->prepare("SELECT message, created_at FROM notifications WHERE user_id=? ORDER BY created_at DESC");

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

    body { font-family: Arial; padding: 20px; background: #f4f4f4; }

    .notification {

      background: white;

      padding: 15px;

      margin-bottom: 15px;

      border-left: 5px solid #3498db;

      border-radius: 8px;

    }

    .time { font-size: 12px; color: #999; }

  </style>

</head>

<body>

  <h2>Your Messages</h2>

  <?php while ($row = $messages->fetch_assoc()): ?>

    <div class="notification">

      <div class="time"><?= date('F j, Y H:i', strtotime($row['created_at'])) ?></div>

      <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>

    </div>

  <?php endwhile; ?>

</body>

</html>