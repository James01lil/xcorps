<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../authenticate/login.html"); // adjust path as needed
    exit;
}

$user = $_SESSION['user'];
$_SESSION['user_id'] = $user['id']; // ✅ Add this line
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>XcorPs | Overview Page</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      width: 100%;
      font-family: 'Segoe UI', sans-serif;
      background:#000;
      overflow-x: hidden;
    }

    .hero {
      background: url('../imgg/intro2.jpg') no-repeat center center/cover;
      min-height: 100%;
      width: 100%;
      position: relative;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
    }

    .overlay {
      position: relative;
      width: 100%;
      background: linear-gradient(to right, rgba(5, 5, 5, 0.8), transparent 100%);
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 30px;
    }

    .content {
      z-index: 2;
      position: relative;
      text-align: left;
      max-width: 700px;
    }

    .content h1 {
      font-size: 2rem;
      margin-bottom: 10px;
      word-break: break-word;
    }

    .proceed-btn {
      background: #ef763c;
      color: #000;
      font-weight: 800;
      border: none;
      padding: 12px 25px;
      border-radius: 25px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 20px;
      text-decoration: none;
      display: inline-block;
      text-align: center;
    }

    .icons-row {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin: 25px 0;
      font-size: 1.8rem;
      flex-wrap: wrap;
    }

    .icons-row i {
      cursor: pointer;
      transition: transform 0.3s ease;
    }

    .icons-row i:hover {
      transform: scale(1.2);
      color: #00c776;
    }

    .info-group {
      margin-bottom: 15px;
    }

    .label {
      font-size: 0.85rem;
      color: #94a3b8;
    }

    .value {
      font-size: 1rem;
      font-weight: 600;
      color: #f8fafc;
      word-break: break-word;
    }

    @media (max-width: 768px) {
      .overlay {
        padding: 24px;
      }
      .content {
        max-width: 100%;
      }
      .content h1 {
        font-size: 1.7rem;
      }
    }

    @media (max-width: 600px) {
      .hero {
        min-height: 100vh;
      }
      .overlay {
        padding: 18px;
      }
      .content h1 {
        font-size: 1.5rem;
      }
      .proceed-btn {
        padding: 10px 20px;
        font-size: 0.9rem;
        width: 100%;
      }
      .icons-row {
        gap: 20px;
        font-size: 1.5rem;
      }
      .label {
        font-size: 0.78rem;
      }
      .value {
        font-size: 0.9rem;
      }
    }

    @media (max-width: 380px) {
      .overlay {
        padding: 14px;
      }
      .content h1 {
        font-size: 1.3rem;
      }
      .label {
        font-size: 0.72rem;
      }
      .value {
        font-size: 0.85rem;
      }
      .proceed-btn {
        padding: 10px 16px;
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>

  <div class="hero">
    <!-- Center icons (social + growth) -->

    <!-- Bottom Transparent Overlay -->
    <div class="overlay">
      <div class="content">
         <h1> Welcome,  <?= $user['user_name']; ?>!</h1>

         <div class="info-group">
      <div class="label">Email</div>
      <div class="value"><?= $user['email']; ?>
  </div>
    </div>

    <div class="info-group">
      <div class="label">Plan Type</div>
      <div class="value"><?= $user['plan']; ?>
</div>
    </div>

    <div class="info-group">
      <div class="label">Currency</div>
      <div class="value"><?= $user['currency']; ?></div>
    </div>

    <div class="info-group">
      <div class="label">Country / State</div>
      <div class="value"><?= $user['country']; ?> / <?= $user['state']; ?></div>
    </div>

    <div class="info-group">
      <div class="label">Investment Type</div>
      <div class="value"><?= $user['account']; ?>
</div>
    </div>

    <div class="info-group">
      <div class="label">Amount to Invest</div>
      <div class="value"><span id="showInvest"></span></div>
    </div>

    <div class="info-group">
      <div class="label">Target Return</div>
      <div class="value"><span id="showReturn"></span></div>
    </div>

    <div class="info-group">
      <div class="label">Investment Interval</div>
      <div class="value">Weekly</div>
    </div>

    <div class="info-group">
      <div class="label">Target Duration</div>
      <div class="value">6 Months</div>
    </div>

    <div class="info-group">
      <div class="label">Daily Earnings (If Trading On Starter Plan)</div>
      <div class="value"><?= $user['currency']; ?>174.65 - <?= $user['currency']; ?>349.65</div>
    </div>

    <div class="info-group">
      <div class="label">Daily Earnings (If Not Trading On Starter Plan)</div>
      <div class="value"><?= $user['currency']; ?>5.00</div>
    </div>



        <a href="dashboard/dashboard.php" class="proceed-btn">Proceed to dashboard</a>
      </div>
    </div>
  </div>





  <script>
window.addEventListener("DOMContentLoaded", () => {
  const amount = localStorage.getItem("invest_amount");
  const returns = localStorage.getItem("expected_return");

  document.getElementById("showInvest").textContent = amount ? `$${amount}` : "N/A";
  document.getElementById("showReturn").textContent = returns ? `$${returns}` : "N/A";
});
</script>

</body>
</html>
