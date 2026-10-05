<?php

session_start();

$reason = $_SESSION['ban_reason'] ?? "You have been banned by the administrator.";

?>

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Access Denied</title>

  <style>

    body {

      background-color: #111;

      color: #f33;

      font-family: Arial, sans-serif;

      margin: 0;

      padding: 0;

      display: flex;

      align-items: center;

      justify-content: center;

      min-height: 100vh;

    }

    .card {

      background: #222;

      padding: 25px;

      border-radius: 12px;

      color: white;

      width: 90%;

      max-width: 500px;

      box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);

    }

    h1 {

      font-size: 1.8em;

      margin-bottom: 10px;

    }

    p {

      margin: 10px 0;

      font-size: 1em;

      line-height: 1.4;

    }

    input, textarea {

      width: 100%;

      padding: 12px;

      margin-top: 12px;

      border-radius: 6px;

      border: none;

      background-color: #333;

      color: white;

      font-size: 1em;

    }

    input::placeholder,

    textarea::placeholder {

      color: #ccc;

    }

    button {

      width: 100%;

      padding: 12px;

      margin-top: 16px;

      background: #f33;

      color: white;

      border: none;

      border-radius: 6px;

      font-size: 1em;

      cursor: pointer;

    }

    button:hover {

      background: #d22;

    }

    @media (max-width: 480px) {

      .card {

        padding: 20px;

      }

      h1 {

        font-size: 1.4em;

      }

      input, textarea, button {

        font-size: 0.95em;

      }

    }
    
    
    
    
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
    
    <div id="desktopWarning">
  ⚠️ This website is only accessible on mobile or small screens.<br>
  Please resize your browser or use a mobile device.
</div>

<div id="mainContent">
  <!-- Your actual website content goes here -->
</div>

  <div class="card">

    <h1>ðŸš« Access Denied</h1>

    <p>Your account has been <strong>banned</strong>.</p>

    <p>Reason: <?= htmlspecialchars($reason) ?></p>

    <p>If you believe this is a mistake, contact support below:</p>

    <form action="support_request.php" method="post">

      <input type="text" name="email" placeholder="Your Email" required>

      <textarea name="message" rows="5" placeholder="Write your appeal or issue here..." required></textarea>

      <button type="submit">Send Appeal</button>

    </form>

  </div>

</body>

</html>