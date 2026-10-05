<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form values
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $userMessage = trim($_POST['message']);

    // Build message
    $to = "wsamson630@gmail.com"; // ✅ Your real email
    $subject = "New Mail from Contact Form";
    $message = "Name: $name\n";
    $message .= "Email: $email\n\n";
    $message .= "Message:\n$userMessage";

    // Headers
    $headers = "From: $name <noreply@xcorps.online>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Result output styling
    $success = mail($to, $subject, $message, $headers);
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Mail Status</title>
      <style>
        body {
          font-family: 'Segoe UI', sans-serif;
          background: #0f0f0f;
          color: #fff;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          height: 100vh;
          margin: 0;
          text-align: center;
          padding: 20px;
        }
        .box {
          background: #1e1e1e;
          padding: 30px;
          border-radius: 12px;
          max-width: 500px;
          width: 100%;
          box-shadow: 0 0 12px rgba(0,0,0,0.4);
        }
        h2 {
          color: <?= $success ? '#4caf50' : '#f44336' ?>;
        }
        p {
          margin-top: 10px;
          color: #ccc;
        }
        a.button {
          display: inline-block;
          margin-top: 20px;
          padding: 12px 25px;
          background: #04aa6d;
          color: #fff;
          text-decoration: none;
          border-radius: 6px;
          font-weight: bold;
          transition: background 0.3s;
        }
        a.button:hover {
          background: #039e60;
        }

        @media (max-width: 500px) {
          .box {
            padding: 20px;
          }
          h2 {
            font-size: 20px;
          }
          p {
            font-size: 14px;
          }
          a.button {
            font-size: 14px;
            padding: 10px 20px;
          }
        }
      </style>
    </head>
    <body>
      <div class="box">
        <h2>
          <?= $success ? '✅ Message Sent Successfully' : '❌ Message Failed to Send' ?>
        </h2>
        <p>
          <?= $success ? 'We would reach out to you shortly. Thank you for contacting us!' : 'Please try again later or contact support.' ?>
        </p>
        <a class="button" href="../dashboard/dashboard.php">Proceed to Homepage</a>
      </div>
    </body>
    </html>

    <?php
} else {
    echo "❌ Invalid request.";
}
?>