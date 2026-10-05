<?php
session_start();
include '../authenticate/db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Access denied. Please log in.";
    exit;
}

$user_id = $_SESSION['user_id'];
$bot_id = $_POST['bot_id'] ?? null;
$payment_status = 'pending';

// Simple validation
if (!$bot_id) {
    echo "No bot selected.";
    exit;
}

// Insert the subscription request
$stmt = $conn->prepare("INSERT INTO subscriptions (account_id, bot_id, payment_status) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $bot_id, $payment_status);
$stmt->execute();

// Confirmation
//echo "âœ… Subscription initiated. Wait for admin approval.<br><br>";
//echo "<a href='dashboard.php'>ðŸ”™ Return to Dashboard</a>";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bot Subscription Notice</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #111;
      color: #f5f5f5;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      background: #1c1c1c;
      padding: 30px;
      border-radius: 10px;
      max-width: 480px;
      width: 90%;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.5);
    }

    h2 {
      color: #0af;
      margin-bottom: 15px;
    }

    p {
      font-size: 0.95rem;
      line-height: 1.5;
      margin: 10px 0;
    }

    .warning {
      background-color: #222;
      padding: 12px;
      border-radius: 8px;
      margin-top: 20px;
      border-left: 4px solid #f39c12;
      font-size: 0.9rem;
    }

    button {
      margin-top: 25px;
      padding: 12px 25px;
      background-color: #0af;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #08c;
    }

    @media (max-width: 500px) {
      .container {
        padding: 20px;
      }

      button {
        width: 100%;
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Notify Admin to Proceed</h2>
    <p>This bot is an <strong>external integration</strong> linked directly to your personal trading account.</p>
    <p><strong>Before you make any subscription payments</strong>, our company must complete a backend setup to ensure your bot functions properly with your account.</p>
    <p>Click the button below to notify our team.</p>

    <button onclick="notifyClick()">Notify Admin</button>

    <div class="warning">
      <strong>⚠️ Security Notice:</strong><br>
      You will receive a payment page only from our official company emails.<br>
      Do <u>not</u> trust any unofficial links or pages.<br>
      Stay safe and verify all payment instructions.
    </div>
  </div>

  <script>
    function notifyClick() {
      fetch('notify_admin.php', {
        method: 'POST',
      })
      .then(res => res.text())
      .then(data => alert(data))
      .catch(err => alert('Error sending notification'));
    }
  </script>
</body>
</html>

