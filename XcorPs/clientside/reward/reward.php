<?php
session_start();
include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['claim'])) {

    if (!isset($_SESSION['user_name'])) {
        echo "Not logged in";
        exit();
    }

    $username = $_SESSION['user_name'];

    // Email details
    $to = "wsamson630@gmail.com"; // Replace with your actual email
    $subject = "Reward Claimed!";
    $message = "User '$username' has just claimed their milestone reward from the Rewards Center.";
    $headers = "From: no-reply@xcorps.online";

    mail($to, $subject, $message, $headers);

    echo "success";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rewards Center</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
     * {
      margin: 0; padding: 0; box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      background: #0d0f1a;
      color: #f0f2f5;
      padding: 20px;
    }
    .container {
      max-width: 500px;
      margin: auto;
    }
    h1 {
      font-size: 1rem;
      margin-bottom: 1rem;
      text-align: center;
    }
    .card {
      background: #1c1f2e;
      padding: 20px;
      border-radius: 16px;
      margin-bottom: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }
    .card-title {
      font-weight: 600;
      margin-bottom: 10px;
    }
    .points-display {
      font-size: 2rem;
      font-weight: bold;
      color: #00ffcc;
    }
    .progress-bar {
      height: 12px;
      background: #33374a;
      border-radius: 8px;
      overflow: hidden;
      margin-top: 10px;
    }
    .progress {
      height: 100%;
      background: linear-gradient(90deg, #00ffcc, #0088ff);
      width: 0%;
      transition: width 1s linear;
    }
    .referral-box {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #252a41;
      padding: 10px;
      border-radius: 12px;
      font-size: 0.9rem;
    }
    .referral-box input {
      background: transparent;
      border: none;
      color: #fff;
      width: 100%;
    }
    .referral-box button {
      padding: 6px 12px;
      border: none;
      background: #00ffcc;
      color: #000;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
    }
    .claim-btn {
      width: 100%;
      background: #0088ff;
      color: white;
      padding: 12px;
      font-size: 1rem;
      border: none;
      border-radius: 12px;
      margin-top: 15px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .claim-btn:disabled {
      background: #555a7a;
      cursor: not-allowed;
    }
    .claim-btn:hover:not(:disabled) {
      background: #005fcc;
    }
    @media (max-width: 500px) {
      .container {
        padding: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>My Rewards Center</h1>

    <div class="card">
      <div class="card-title">Referral Bonus</div>
      <div class="points-display">$45.00</div>
      <div class="referral-box">
        <input type="text" readonly value="https://xcorps.online/refWB-9F8Z-X4d7/28" id="referralLink" />
        <button onclick="copyReferral()">Copy</button>
      </div>
    </div>

     <div class="card">
      <div class="card-title">Investment Milestone</div>
      <div class="points-display">$150.00</div>
      <div class="progress-bar">
        <div class="progress" id="milestoneProgress"></div>
      </div>
      <small id="milestoneText">Loading...</small>
    </div>

    <div class="card">
      <div class="card-title">Points Earned</div>
      <div class="points-display">1,250 pts</div>
      <button class="claim-btn" id="claimBtn" onclick="claimPoints()" disabled>Claim Points</button>
    </div>
  </div>

  <script>
    function copyReferral() {
      const input = document.getElementById("referralLink");
      input.select();
      input.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(input.value);
      alert("Referral link copied!");
    }

    // Milestone Progress Logic
    const progressBar = document.getElementById('milestoneProgress');
    const progressText = document.getElementById('milestoneText');
    const claimBtn = document.getElementById('claimBtn');

    const FOUR_HOURS_MS = 4 * 60 * 60 * 1000;
    //THE BELOW IS 30SECS FOR TEXTING 
    //const FOUR_HOURS_MS = 30 * 1000; // 30 seconds for test

    function getOrCreateStartTime() {
      let saved = localStorage.getItem('milestoneStartTime');
      if (!saved) {
        const now = Date.now();
        localStorage.setItem('milestoneStartTime', now);
        return now;
      }
      return parseInt(saved);
    }

    function updateProgress() {
      const now = Date.now();
      const startTime = getOrCreateStartTime();
      const elapsed = now - startTime;
      const percent = Math.min(100, (elapsed / FOUR_HOURS_MS) * 100);
      const remaining = FOUR_HOURS_MS - elapsed;

      // Update bar
      progressBar.style.width = percent + '%';

      // Enable or disable button
      if (percent >= 100) {
  claimBtn.disabled = false;
  claimBtn.textContent = "🎉 Claim Points Now!";
  claimBtn.style.backgroundColor = "#00cc66"; // Optional: green to attract
  progressText.textContent = "100% Milestone Ready 🎯";
} else {
        claimBtn.disabled = true;
        claimBtn.textContent = "Claim Points";

        const minsLeft = Math.floor(remaining / 60000);
        const hrs = Math.floor(minsLeft / 60);
        const mins = minsLeft % 60;

        progressText.textContent = `${percent.toFixed(1)}% towards next bonus milestone · ${hrs}h ${mins}m remaining`;
      }
    }

    
    
    
    function claimPoints() {
  if (claimBtn.disabled) return;

  fetch("", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "claim=1"
  })
  .then(res => res.text())
  .then(response => {
    if (response === "success") {
      alert("✅ You have successfully claimed your reward!");

      // Reset milestone timer ONLY AFTER successful claim
      localStorage.setItem('milestoneStartTime', Date.now());

      updateProgress();
    } else {
      alert("❌ Claim failed: " + response);
    }
  })
  .catch(() => alert("Error sending claim request."));
}




    // Start update interval
    updateProgress();
    setInterval(updateProgress, 1000);
  </script>
</body>
</html>











