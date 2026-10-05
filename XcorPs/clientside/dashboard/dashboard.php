<?php
session_start();

/*if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}*/

require '../../authenticate/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
        // Redirect to login page
    header("Location: ../../authenticate/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch full user record
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if user is banned
if ($user && $user['status'] === 'banned') {
    header("Location: ../bannedpage/banned.php");
    exit();
}
?>

<?php if (isset($_SESSION['impersonating']) && $_SESSION['impersonating'] === true): ?>
  <a href="../../backends/stop_impersonation.php" class="impersonation-banner">Back to Admin</a>
<?php endif; ?>

<!--NOTIFICATION MESSAGE-->

<?php
$unread = $conn->prepare("SELECT COUNT(*) AS unread FROM notifications WHERE user_id=? AND is_read=0");
$unread->bind_param("i", $user_id);
$unread->execute();
$count_result = $unread->get_result()->fetch_assoc();
$unread_count = $count_result['unread'];
?>         
 <?php if ($unread_count > 0): ?>
 <?php endif; ?>

<!----END-->

<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <title>XcorPs | User Dashboard</title>

    <!-- FAVIVON -->

        <link rel="shortcut icon" href="images/favicon.png" type="image/png">

        <meta charset="UTF-8">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

	      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        
        

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  

    <link rel="stylesheet" href="css/inner-style.css" />

  

  <style>
  
/*  THIS IS A CSS CODE TO HIDE A WEBPAGE ON BIG SCREEN AND DISPLAY IT ON MOBILES ONLY */
 /* @media (min-width: 501px) {
  body::before {
    content: "⚠️ This website is only accessible on mobile or small screens. Please resize your browser or use a mobile device.";
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #111;
    color: white;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    text-align: center;
    padding: 20px;
  }

  * {
    display: none !important;
  }
}
*/

.impersonation-banner {
  position: fixed;
  top: 40%;
  left: 0;
  writing-mode: vertical-rl;
  transform: rotate(180deg);
  background-color: #e74c3c;
  color: white;
  padding: 10px 15px;
  text-align: center;
  text-decoration: none;
  font-weight: bold;
  border-radius: 0 8px 8px 0;
  z-index: 9999;
  box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
  font-family: sans-serif;
  transition: background 0.3s ease;
}

.impersonation-banner:hover {
  background-color: #c0392b;
}

  
  .image-header {

  width: 100%;

  height: 250px;

  background: url('../../imgg/GIF(1).gif') no-repeat center center/cover;

}
.image-header h3{
    margin-top :150px;
    position:absolute;
}

@media (max-width: 500px) {
.image-header h3{
    font-size:17px;
    color:peru;
}
}
     
  #notchFooter {
  position: fixed;
  bottom: 30px;
  left: 50%;
  transform: translateX(-50%);
  background: linear-gradient(135deg, rgba(26, 5, 7, 0.95), rgba(50, 15, 15, 0.95), rgba(74, 19, 17, 0.95));
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
  border-radius: 50%;
  height: 60px;
  width: 60px;
  transition: all 0.5s ease-in-out;
  z-index: 999;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  text-decoration:none;
}

    #notchFooter.expanded {
      width: 370px;
      height: 60px;
      border-radius: 40px;
      justify-content: center;
    }

    .footer-content {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 30px;
      width: 100%;
      transition: all 0.3s ease;
      text-decoration:none;
    }

    .extra {
      opacity: 0;
      transform: scale(0);
      pointer-events: none;
      color: white;
      text-align: center;
      text-decoration: none;
      transition: opacity 0.3s ease, transform 0.3s ease;
      width: 50px;
      text-decoration:none;
    }

    .extra i {
      font-size: 13px;
      margin-bottom: 4px;
      text-decoration:none;
      outline-color:#ddd;
    }

    .extra b {
      font-size: 11px;
    }

    #notchFooter.expanded .extra {
      opacity: 1;
      transform: scale(1);
      pointer-events: auto;
    }

    .center-icon-wrapper {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      z-index: 2;
    }

    .center-icon {
      background: #951f19;
      border: 3px solid #951f19;
      border-radius: 50%;
      font-size: 22px;
      color: #111111;
      text-decoration:none;
      width: 44px;
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    @media (max-width: 500px) {
      .center-icon {
        font-size: 25px;
        width: 40px;
        height: 40px;
      }
    }




  .blur-overlayaction {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: rgba(255, 255, 255, 0.1); /* or use transparent black/gray */
  backdrop-filter: blur(2px);
  -webkit-backdrop-filter: blur(2px); /* Safari support */
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      display:non;
       padding:10px 10px;
       overflow-x:scroll;
       display:none;
  }


   
   
    .gab{
        position:fixed;
        width:80%;
        color:#ddd;
        margin-left:27px;
        margin-top:50px;
        color:#ddd;
  opacity: 0;
  transform: translateY(100px);
  animation: slideUp 1s ease-out forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
    
 



    .section {
      background: #000000;
      border-radius: 12px;
      padding: 16px;   
      margin-bottom: 20px;
    }
    .section h2 {
      margin-bottom: 12px;
      font-size: 18px;
      font-weight:900;
      color:peru;
    }
    .item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-top: 1px solid #2a2a3b;
      font-size:14px;
      font-weight:900;
    }
    .item:first-child {
      border-top: none;
    }
    .item span {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .new-badge {
      background: orange;
      color: black;
      font-size: 12px;
      padding: 2px 8px;
      border-radius: 12px;
    }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #1a1a2b;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 12px 0;
      border-top: 1px solid #2a2a3b;
    }
    .bottom-nav div {
      text-align: center;
      font-size: 12px;
      color: #aaa;
    }
    .fab {
      background: linear-gradient(to right, #a855f7, #ec4899);
      color: white;
      border-radius: 50%;
      padding: 16px;
      position: relative;
      top: -20px;
      font-size: 24px;
    }
    .notif-dot {
      position: absolute;
      top: 0;
      right: 8px;
      background: orange;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      
    }



    .dropdown {
  display: none;
  background: #2a2a3b;
  margin-top: 8px;
  border-radius: 8px;
  padding: 8px;
  font-size: 14px;
  width:50%;
}
.dropdown div {
  padding: 6px 10px;
  border-bottom: 1px solid #444;
  cursor: pointer;
}
.dropdown div:last-child {
  border-bottom: none;
}
.dropdown div:hover {
  background: #3c3c4e;
}
.dropdown-item.open .dropdown {
  display: block;
}


.sidebar {

  width: 200px;

  background: #161b22;

  position: fixed;

  height: 100vh;

  padding: 20px;

  box-sizing: border-box;

}

@media (max-width: 1000px) {

  .sidebar {

    display:none;

  }

  }

.sidebar h2 {

  color: #58a6ff;

}

.sidebar ul {

  list-style: none;

  padding: 0;

}

.sidebar li {

  margin: 20px 0;

  cursor: pointer;

}

.main {

  margin-left: 200px;

  padding: 0px;

}

@media (max-width: 1000px) {

  .main {

  margin-left: 0px;

}

  }

.header {

  display: flex;

  justify-content: space-between;

  align-items: center;

}

.badge {

  background: red;

  border-radius: 12px;

  padding: 5px 10px;

  color: white;

}

.cards {

  display: grid;

  grid-template-columns: repeat(4, 1fr);

  gap: 15px;

  margin-top: 20px;

}

@media (max-width: 700px) {

 .cards {

  grid-template-columns: repeat(2, 1fr);

  }

  }

  

  @media (max-width: 400px) {

 .cards {

  grid-template-columns: repeat(1, 1fr);

  }

  }

  

.card {

  background: #131313;

  padding: 20px;

  border-radius: 10px;

  text-align: center;

}

table {

  width: 100%;

  margin-top: 20px;

  background: #161b22;

  border-collapse: collapse;

  overflow-x:scroll;

  min-width:600px;

}

 .table_container{

     max-width:100%;

     overflow-x:auto;

 }

th, td {

  padding: 12px;

  text-align: center;

  border-bottom: 1px solid #30363d;

}

.actions {

  margin-top: 20px;

}

button {

  background: #238636;

  color: white;

  padding: 10px 20px;

  margin-right: 10px;

  border: none;

  border-radius: 6px;

  cursor: pointer;

}

.account-summary {

  font-size: 0.9rem;

  margin-top: 5px;

  color: #8b949e;

}

.account-summary p{

  font-weight:800;

  font-size:15px;

}

.status.verified {

  color: #2ea043;

  font-weight: bold;

}

.user-profile {

  display: flex;

  align-items: center;

  gap: 10px;

  position: relative;

}

.avatar {

  width: 70px;

  height: 67px;

  border-radius: 50%;

  border: 3px solid #58a6ff;
  margin-top :30px;

}

.dropdown1 {

  position: relative;

  display: inline-block;

}

.dropbtn1 {

  background-color: #161b22;

  color: #c9d1d9;

  border: none;

  font-size: 24px;

  cursor: pointer;

}

.dropdown-content1 {

  display: none;

  position: absolute;

  background-color: #21262d;

  box-shadow: 0px 8px 16px rgba(0,0,0,0.3);

  right: 0;

  min-width: 120px;

  border-radius: 6px;

  z-index: 1;

}

.dropdown-content1 a {

  color: #c9d1d9;

  padding: 10px 12px;

  text-decoration: none;

  display: block;

}

.dropdown-content1 a:hover {

  background-color: #30363d;

}

.dropdown1:hover .dropdown-content1 {

  display: block;

}

      

      

      

      

      

      

      

      

      

      

      

      

      

      

      .profile-overview {

  margin-top: 40px;

  background: #131313;

  padding: 20px;

  border-radius: 10px;

}

.profile-overview h2 {

  margin-bottom: 15px;

  color: #58a6ff;

}

.summary-cards {

  display: grid;

  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));

  gap: 15px;

  margin-bottom: 30px;

}

.summary-card {

  background: #000000;

  border:2px solid #21262d;

  padding: 15px 20px;

  border-radius: 8px;

  text-align: center;

  color: #c9d1d9;

  font-weight:800;

}

.summary-card h3 {

  margin: 10px 0 0;

  font-size: 1.4rem;

}

.summary-card .profit {

  color: #ddd;

}

.chart-container {

  background: #000000;

  padding: 20px;

  border-radius: 8px;

}    

  .market-panel {

  margin-top: 30px;

  background: #131313;

  padding: 20px;

  border-radius: 10px;

}

.market-panel h2 {

  margin-bottom: 15px;

  color: #58a6ff;

}

.market-ticker {

  display: flex;

  flex-wrap: wrap;

  gap: 20px;

}

.ticker-item {

  flex: 1 1 200px;

  background: #000000;

  padding: 10px 15px;

  border-radius: 8px;

  color: #c9d1d9;

}

.ticker-item .symbol {

  font-weight: bold;

  font-size: 1rem;

  margin-bottom: 5px;

}

.ticker-item .price {

  font-size: 1.2rem;

  color: #3fb950;

  margin-bottom: 5px;

}

.spark {

  height: 20px !important;

  max-height: 20px;

  width: 100%;

}

@media (max-width: 700px) {

  .spark {

  width: 70%;

}

  }

.watchlist {

  margin-top: 20px;

}

.watchlist h3 {

  margin-bottom: 10px;

  color: #c9d1d9;

}

.watchlist ul {

  list-style: none;

  padding: 0;

  display: flex;

  gap: 15px;

  flex-wrap: wrap;

}

.watchlist li {

  background: #21262d;

  padding: 6px 12px;

  border-radius: 6px;

  color: #c9d1d9;

  font-size: 0.9rem;

}

/* Trade History Table */

.trade-history {

  margin-top: 40px;

  background: #131313;

  padding: 20px;

  border-radius: 10px;

}

.trade-history table {

  width: 100%;

  border-collapse: collapse;

  background:#000000;

}

.trade-history th,

.trade-history td {

  padding: 12px 10px;

  text-align: left;

  border-bottom: 1px solid #30363d;

  color: #c9d1d9;

}

.trade-history th {

  background-color: #000000;

}

.trade-history .open {

  color: #facc15;

}

.trade-history .closed {

  color: #10b981;

}

.trading-tools, .chart-section, .notifications-panel, .support-community {

  background: #131313;

  padding: 20px;

  border-radius: 10px;

  margin-top: 30px;

}

.tools-grid {

  display: flex;

  flex-wrap: wrap;

  gap: 15px;

  align-items: center;

}

.tool-btn {

  background: #238636;

  color: #fff;

  padding: 10px 20px;

  border: none;

  border-radius: 8px;

  font-weight: bold;

  cursor: pointer;

}

.bot-status {

  display: flex;

  align-items: center;

  gap: 10px;

  margin-left: auto;

}

.switch {

  position: relative;

  display: inline-block;

  width: 50px;

  height: 24px;

}

.switch input {display:none;}

.slider {

  position: absolute;

  cursor: pointer;

  top: 0; left: 0; right: 0; bottom: 0;

  background-color: #ccc;

  transition: .4s;

  border-radius: 24px;

}

.slider:before {

  position: absolute;

  content: "";

  height: 18px; width: 18px;

  left: 3px; bottom: 3px;

  background-color: white;

  transition: .4s;

  border-radius: 50%;

}

input:checked + .slider {

  background-color: #3fb950;

}

input:checked + .slider:before {

  transform: translateX(26px);

}

.coupon-entry {

  display: flex;

  gap: 5px;

}

.coupon-entry input {

  padding: 8px;

  border-radius: 6px;

  border: 1px solid #30363d;

  background: #0d1117;

  color: #c9d1d9;

}

.coupon-entry button {

  background: #1f6feb;

  color: white;

  padding: 8px 12px;

  border: none;

  border-radius: 6px;

}

.chart-controls {

  display: flex;

  justify-content: space-between;

  align-items: center;

  margin-bottom: 10px;

}

.chart-controls select, .chart-controls button {

  background: #21262d;

  color: #c9d1d9;

  border: 1px solid #30363d;

  padding: 6px 10px;

  border-radius: 6px;

}

.chart-controls .timeframes button {

  margin-left: 5px;

}

.notification-list {

  list-style: none;

  padding: 0;

}

.notification-list li {

  background: #21262d;

  padding: 10px;

  border-radius: 6px;

  margin-bottom: 10px;

}

.badge {

  background: #facc15;

  color: #000;

  padding: 4px 8px;

  border-radius: 8px;

  font-size: 0.9rem;

  margin-left: 8px;

}

.support-links {

  display: flex;

  gap: 10px;

  flex-wrap: wrap;

}

.support-links button, .support-link {

  background: #238636;

  color: white;

  padding: 10px 16px;

  border: none;

  border-radius: 6px;

  text-decoration: none;

  font-weight: bold;

}

.support-link {

  background: #1f6feb;

}

#news-container {

  display: grid;

  grid-template-columns: repeat(auto-fill, minmax(400px, 2fr));

  gap: 20px;

  margin-left:-15px;

}

  .profile-settings {

    background-color: #131313;

    color: #fff;

    border-radius: 16px;

    padding: 24px;

    max-width: 800px;

    margin: 0 auto;

    box-shadow: 0 0 10px rgba(0,0,0,0.5);

    font-family: 'Segoe UI', sans-serif;

  }

  .profile-settings h2 {

    font-size: 22px;

    margin-bottom: 20px;

    color: #00ffc6;

  }

  .setting-group {

    border-bottom: 1px solid #333;

    padding: 16px 0;

  }

  .setting-group:last-child {

    border-bottom: none;

  }

  .setting-title {

    font-weight: bold;

    font-size: 16px;

    margin-bottom: 8px;

  }

  .setting-desc {

    color: #aaa;

    font-size: 14px;

    margin-bottom: 12px;

  }

  .btn-action {

    background-color: #00ffc6;

    color: #000;

    padding: 8px 14px;

    border: none;

    border-radius: 8px;

    cursor: pointer;

    font-weight: 500;

  }

  .btn-action:hover {

    background-color: #00d6a5;

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


          <div class="blur-overlayaction" id="actionTab">
<div class="gab">
  <!-- Toggle -->
        
  <!-- Section: Invest -->
  <div class="section">
    <h2>Account Management</h2>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">My Account</span> ›
  <div class="dropdown">
    <div  onclick="window.location.href='../profile/profile.php'"  >Profile</div>
    </div>
    </div>
    
    
      <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Wallet</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../topup/topup.php'" >➕ Deposit Funds</div>
    <div onclick="window.location.href='../withdrawal/client/withdraw.php'">➖ Withdraw Funds</div>
    <div>🔁 Transfer</div>
    <div>📉 Sell Stocks</div>
    <div>🤖 Auto-Invest</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Upgrade Account</span> ›
  <div class="dropdown">
      <div onclick="window.location.href='../upgrade/upgrade.php'"> UPGRADE PLAN</div>
    <div  onclick="window.location.href='../upgrade/premiumplan.php'"   >Premium Plans</div>
    <div   onclick="window.location.href='../upgrade/deluxeplan.php'"  >Deluxe Plans</div>
    <div onclick="window.location.href='../upgrade/deluxemegaplan.php'"  >Deluxe Mega Plans</div>
    <div  onclick="window.location.href='../upgrade/exclusiveplan.php'"  >Exclusive Plans</div>
    <div  onclick="window.location.href='../upgrade/vipplan.php'"  >VIP Plans</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Balance Summary</span> ›
  <div class="dropdown">
    <div>Deposited -- <?= $user['currency'] ?><?= $user['deposit']; ?>!</h3>
</div>
    <div>Total Balance -- <?= $user['currency'] ?><?= $user['balance']; ?>!</h3>
</div>
    <div>Bonus -- <?= $user['currency'] ?><?= $user['bonus']; ?>!</h3>
</div>
    <div>Total Profits -- <?= $user['currency'] ?><?= $user['pnl']; ?>!</h3>
</div>
    <div>Trading Statistics</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Security Settings</span> ›
  <div class="dropdown">
    <div>Passwords</div>
    <div>2FA (2 Factor Authentication)</div>
    <div>Device Management</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Tax Documentation</span> ›
  <div class="dropdown">
    <div>Annual Statements</div>
    <div>Export Options</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">KYC Verification</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../kyc_verification/kyc_verification.php'">Upload Verification</div>
    <div>Check Verification Status</div>
  </div>
</div>
 </div>
 
  
  
  <!------ SUPPORT AND TOOLS--------->
  <div class="section">
    <h2>Support & Tools</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Help Center</span> ›
  <div class="dropdown">
    <div>FAQ</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Contact Support</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../mailus/mailus.php'">Live Chat With An Expert</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Trading Tools</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../tradestation/trade_station.php'"  >Trade Stations</div>
    <div onclick="window.location.href='../signal/signalupdate.php'">Signal Hub</div>
    <div onclick="window.location.href='../community/community.php'">Watchlists</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">System Status</span> ›
  <div class="dropdown">
    <div>User Comments Or Sentiment Tagging</div>
    <div onclick="window.location.href='../notification_page/notification_page.php'">Announcements</div>
  </div>
</div>   
  </div>


  <!-- Section: Research -->
  <div class="section">
    <h2>Research</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">📰 News</span> ›
  <div class="dropdown">
    <div>📰 Market News</div>
    <div>Financial News Feed</div>
    <div>Alerts</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">🔍 Stock Screener</span> ›
  <div class="dropdown">
    <div>Broker Research</div>
    <div>Stock Analysis</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Learning Center</span> ›
  <div class="dropdown">
    <div>Articles</div>
    <div>Video Tutorials on Investing</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">📑 Webminars / Events</span> ›
  <div class="dropdown">
    <div>Join</div>
    <div>Register</div>
  </div>
</div>   
  </div>
  
  <!------ COMMUNITY FEAUTURES--------->
  <div class="section">
    <h2>Community Feautures</h2>    
    <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Community Forum</span> ›
  <div class="dropdown">
    <div onclick="window.location.href='../community/community.php'">Public Discussions</div>
    <div>Private User Discussions</div>
  </div>
</div>
  <div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Leaderboards</span> ›
  <div class="dropdown">
    <div>Top Traders</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Groups / Clubs</span> ›
  <div class="dropdown">
    <div>Join Based On Assets & Strategies</div>
  </div>
</div>
<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Comment On Stocks</span> ›
  <div class="dropdown">
    <div>User Comments Or Sentiment Tagging</div>
  </div>
</div>   
  </div>
  

  <!-- Section: Earn -->
  <div class="section">
    <h2>Earn</h2>
    <div onclick="window.location.href='../referrals/referral.php'" class="item"><span>🎁 Referral Program</span> <span class="new-badge">New</span></div>
    <div onclick="window.location.href='../reward/reward.php'" class="item"><span>💵 Rewards</span> <span class="new-badge">New</span></div>
  </div>
  
<m onclick="actionMenuremove();" style="justify-content:center;align-items:center;display:flex;"><i class="fa fa-times" style="background:#951f19;border:3px solid #9; padding:10px 12px;border-radius:30px;font-size:25px;font-weight:900;color:#000;"></i></m>
<br><br><br><br>
  
        
        
  </div>
      </div>
        </div>
        
  
  
        

    <header style="float: ;">&nbsp;&nbsp;

    <ul style="margin-top:-10px;">

      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,

                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Dashboard</b></h5></a>

    </ul>

    <ul style="float: right;">
            
            
      <a href="../notification_page/notification_page.php" style=""> <i class="fa fa-message" style="font-size:11px;font-weight:800;color:#ffffff;margin-top:-15px;"></i>
    <span class="badge" style="margin-left:0px;margin-top:-10px;"><?= $unread_count ?></span></a>
          

  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;

    <a href=""></a>&nbsp;&nbsp;&nbsp;&nbsp;

    <a href="../logout/logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

    </ul>

</header>

<br><br>

  <div class="sidebar">

    <h2>📈 TradeDesk</h2>

    <ul>

      <li>Dashboard</li>

      <li>New Trade</li>

      <li>Deposit</li>

      <li>Withdraw</li>

      <li>Profile</li>

      <li>Logout</li>

    </ul>

  </div>

<div class="image-header">

    <small id="google_translate_element" style="margin-top:0px;"></small>

      <h3 style="">Welcome,&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>

      <ul style="margin-top:120px;">

        <div class="dropdown1">

      <button class="dropbtn1">☰</button>

      <div class="dropdown-content1">

        <a href="../profile/profile.php">👤 Profile</a>

        <a href="#">⚙️ Settings</a>

        <a href="../logout/logout.php">🚪 Logout</a>

      </div>

    </div>

      </ul>         

  <!-- TradingView Widget BEGIN -->

<div class="tradingview-widget-container">

  <div class="tradingview-widget-container__widget"></div>

  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>

  {

  "symbols": [

    {

      "proName": "FOREXCOM:SPXUSD",

      "title": "S&P 500 Index"

    },

    {

      "proName": "FOREXCOM:NSXUSD",

      "title": "US 100 Cash CFD"

    },

    {

      "proName": "FX_IDC:EURUSD",

      "title": "EUR to USD"

    },

    {

      "proName": "BITSTAMP:BTCUSD",

      "title": "Bitcoin"

    },

    {

      "proName": "BITSTAMP:ETHUSD",

      "title": "Ethereum"

    }

  ],

  "showSymbolLogo": true,

  "isTransparent": true,

  "displayMode": "adaptive",

  "colorTheme": "dark",

  "locale": "en"

}

  </script>

</div>

<!-- TradingView Widget END -->

     </div>

    

    

    

    

    <!-- Main content area with curved top -->

  <div class="curved-body">

      

      

    

  

  <div class="main">

    <div class="header">
                      

  <div>

    <div class="account-summary">

      <p><strong style="font-size:10px;">Account No:</strong> <?= $user['wallet_id']; ?> </p>

      <p><strong style="font-size:10px;">Account Type:</strong> <?= $user['account']; ?></p>

      <p><strong style="font-size:10px;">Status:</strong> <span class="status verified">✔ Verified &nbsp;<?= $user['status']; ?></span></p>

    </div>

  </div>

  <div class="user-profile">

    <img src="../../imgg/profilepanda.jpg" alt="User" class="avatar">

  </div>

</div> 
          
          
          
    

<div class="profile-overview">
        
        
      <a href="../notification_page/notification_page.php" class="notification-link" style="float:right;">

  🔔 Notifications

    <span class="badge"><?= $unread_count ?></span></a>

  
        <br>

  <h2>Account Overview</h2>
        

  <div class="summary-cards">

    <div class="summary-card">

      <p>Total Balance</p>

      <h3> <?= $user['currency']; ?> <?= $user['balance']; ?> </h3>

    </div>

    <div class="summary-card">

      <p>Available Margin</p>

      <h3><?= $user['currency']; ?> <?= $user['margin']; ?></h3>

    </div>

    <div class="summary-card">

      <p>Invested Funds</p>

      <h3><?= $user['currency']; ?> <?= $user['deposit']; ?></h3>

    </div>

    <div class="summary-card">

      <p>Profit / Loss</p>

      <h3 class="profit"><?= $user['currency']; ?> <?= $user['pnl']; ?></h3>

    </div>

  </div>

  <div class="chart-container">

    <h3>Asset Allocation</h3>

    <canvas id="assetChart" width="400" height="200"></canvas>

  </div>

</div>

    

    

    

    <div class="cards">

      <div class="card">💰 Balance<br><strong><?= $user['currency']; ?> <?= $user['balance']; ?></strong></div>

      <div class="card">📈 Equity<br><strong><?= $user['currency']; ?> <?= $user['equity']; ?></strong></div>

      <div class="card">🧾 Open Trades<br><strong><?= $user['open_trades']; ?></strong></div>

      <div class="card">🪙 Total P&L<br><strong><?= $user['currency']; ?> <?= $user['pnl']; ?></strong></div>

    </div>

    
          
          
          
          
         <!--  <h2>Trade History</h2>

    <div class="table_container">

    <table>

      <thead>

        <tr>

          <th>Symbol</th><th>Type</th><th>Qty</th><th>Price</th><th>Status</th>

        </tr>

      </thead>

      <tbody>

       // <?php

       // $result = $conn->query("SELECT * FROM trades LIMIT 5");

      //  while ($row = $result->fetch_assoc()) {

        //  echo "<tr>

          //        <td>{$row['symbol']}</td>

           //       <td>{$row['type']}</td>

           //       <td>{$row['qty']}</td>

              //    <td>\${$row['price']}</td>

             //     <td>{$row['status']}</td>

              //  </tr>";

     //   }

       // ?>

      </tbody>

    </table>

    </div>-->
                  
    <div class="actions">

      <button onclick="window.location.href='../tradestation/trade_station.php'">➕ New Trade</button>

      <button onclick="window.location.href='../withdrawal/client/withdraw.php'">💸 Withdraw</button>

    </div>

  

  

  

  

  

    

    

    

    

    

    

    

    

    <!-- Live Market Data Panel -->

<div class="market-panel">

  <h2>📊 Live Market Data</h2>

  <div class="market-ticker">

    <div class="ticker-item">

      <div class="symbol">EUR/USD</div>

      <div class="price">1.0852</div>

      <canvas class="spark" id="eurusdChart"></canvas>

    </div>

    <div class="ticker-item">

      <div class="symbol">BTC/USD</div>

      <div class="price">$67,320</div>

      <canvas class="spark" id="btcusdChart"></canvas>

    </div>

    <div class="ticker-item">

      <div class="symbol">AAPL</div>

      <div class="price">$182.45</div>

      <canvas class="spark" id="aaplChart"></canvas>

    </div>

    <div class="ticker-item">

      <div class="symbol">XAU/USD</div>

      <div class="price">$2,345.60</div>

      <canvas class="spark" id="goldChart"></canvas>

    </div>

  </div>

  <div class="watchlist">

    <h3>⭐ Watchlist</h3>

    <ul>

      <li>ETH/USD</li>

      <li>MSFT</li>

      <li>GBP/USD</li>

      <li>TSLA</li>

    </ul>

  </div>

</div>

<!-- Trade History Table -->

<div class="trade-history">

  <h2>🧾 Recent Trades</h2>

  <div class="table_container">

  <table>

    <thead>

      <tr>

        <th>Symbol</th>

        <th>Type</th>

        <th>Qty</th>

        <th>Price</th>

        <th>Date/Time</th>

        <th>Status</th>

      </tr>

    </thead>

    <tbody>

      <tr>

        <td>BTC/USD</td>

        <td>Buy</td>

        <td>0.5</td>

        <td>$66,400</td>

        <td>2025-06-13 14:32</td>

        <td class="open">Open</td>

      </tr>

      <tr>

        <td>EUR/USD</td>

        <td>Sell</td>

        <td>10,000</td>

        <td>1.0830</td>

        <td>2025-06-12 09:50</td>

        <td class="closed">Closed</td>

      </tr>

      <tr>

        <td>AAPL</td>

        <td>Buy</td>

        <td>100</td>

        <td>$179.80</td>

        <td>2025-06-10 11:15</td>

        <td class="closed">Closed</td>

      </tr>

    </tbody>

  </table>

  

  </div>

</div>

  

<div class="trading-tools">

  <h2>🛠️ Trading Tools</h2>

  <div class="tools-grid">

    <button class="tool-btn" onclick="window.location.href='../tradestation/trade_station.php'">➕ New Trade</button>

    <button class="tool-btn" onclick="window.location.href='../topup/topup.php'">💰 Deposit</button>

    <button class="tool-btn" onclick="window.location.href='../withdrawal/client/withdraw.php'">🏧 Withdraw</button>

    <div class="bot-status">

      <p>Trading Bot:</p>

      <label class="switch">

        <input type="checkbox" id="botToggle" checked>

        <span class="slider round"></span>

      </label>

    </div>

    <div class="coupon-entry">

      <input type="text" placeholder="Enter Promo Code">

      <button>Apply</button>

    </div>

  </div>

</div>

<div class="chart-section">

  <h2>📈 Charts & Analysis</h2>

  <div class="chart-controls">

    <select>

      <option>BTC/USD</option>

      <option>EUR/USD</option>

      <option>AAPL</option>

      <option>XAU/USD</option>

    </select>

    <div class="timeframes">

      <button>1m</button><button>5m</button><button>1h</button><button>1d</button><button>1w</button>

    </div>

  </div>

  <div class="chart-area">

    <iframe src="https://www.tradingview.com/widgetembed/?symbol=BTCUSD" frameborder="0" width="100%" height="400" allowfullscreen></iframe>

  </div>

</div>

<div class="notifications-panel">

  <h2>🔔 Notifications <span class="badge">3</span></h2>

  <ul class="notification-list">

    <li><strong>📢 Admin:</strong> System maintenance scheduled for 2025-06-15</li>

    <li><strong>📰 News:</strong> BTC spikes 5% after ETF announcement</li>

    <li><strong>📬 Message:</strong> Your withdrawal request has been approved.</li>

  </ul>

</div>

<div class="support-community">

  <h2>💬 Support & Community</h2>

  <div class="support-links">

    <button>💬 Live Chat</button>

    <button>🎫 Submit Ticket</button>

    <a href="../mailus/mailus.php" class="support-link">📚 Help Center</a>

    <a href="../community/community.php" class="support-link">👥 Visit Community Forum</a>

  </div>

</div>



<!------------'NEWS------->

<div id="news-container"></div>



<br><br>

<div class="profile-settings">

  <h2>💼 Profile & Settings</h2>

  <div class="setting-group">

    <div class="setting-title">KYC Status</div>

    <div class="setting-desc">Verify your identity to unlock full features.</div>

    <button class="btn-action" onclick="window.location.href='../kyc_verification/kyc_verification.php'">Verify Now</button>

  </div>

  <div class="setting-group">

    <div class="setting-title">Bank/Wallet Details</div>

    <div class="setting-desc">Add or update your preferred withdrawal method.</div>

    <button class="btn-action">Manage Details</button>

  </div>

  <div class="setting-group">

    <div class="setting-title">Email & Password</div>

    <div class="setting-desc">Change your account email or reset your password.</div>

    <button class="btn-action">Update Info</button>

  </div>

  <div class="setting-group">

    <div class="setting-title">Two-Factor Authentication</div>

    <div class="setting-desc">Enhance security by enabling 2FA.</div>

    <button class="btn-action">Set Up 2FA</button>

  </div>

</div>
<br><br><br><br>
        
  <footer id="notchFooter">
    <div class="footer-content">
      <a href="dashboard.php" class="extra"><i class="fa fa-bank"></i><br><b>HOME</b></a>
      <a href="../tradestation/trade_station.php" class="extra"><i class="fa fa-line-chart"></i><br><b>TRADE</b></a>
      
      <!-- Center icon (absolute, centered) -->
      <div class="center-icon-wrapper">
        <a href="#" onclick="actionMenu();"><i class="fa fa-exchange center-icon"></i></a>
      </div>
      
      <a href="../signal/signalupdate.php" class="extra"><i class="fa fa-signal"></i><br><b>SIGNAL</b></a>
      <a href="../community/community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>


  <script src="../NEWS/news.js"></script>
    

        
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

  const ctx = document.getElementById('assetChart').getContext('2d');

  new Chart(ctx, {

    type: 'bar',

    data: {

      labels: ['Stocks', 'Crypto', 'Forex', 'Commodities', 'Cash'],

      datasets: [{

        label: 'USD Value',

        data: [3000, 2000, 1500, 1200, 800],

        backgroundColor: [

          '#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#6b7280'

        ],

        borderRadius: 6

      }]

    },

    options: {

      plugins: {

        legend: { display: false }

      },

      scales: {

        y: {

          beginAtZero: true,

          ticks: { color: '#c9d1d9' },

          grid: { color: '#30363d' }

        },

        x: {

          ticks: { color: '#c9d1d9' },

          grid: { display: false }

        }

      }

    }

  });

</script>

    

  <script src="dashboard.js"></script>

  

  

  

  

  

  

  

  

  

  

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

function drawSparkline(id, data, color) {

  new Chart(document.getElementById(id), {

    type: 'line',

    data: {

      labels: data.map((_, i) => i + 1),

      datasets: [{

        data: data,

        borderColor: color,

        backgroundColor: 'transparent',

        borderWidth: 2,

        pointRadius: 0

      }]

    },

    options: {

      plugins: { legend: { display: false } },

      scales: {

        x: { display: false },

        y: { display: false }

      },

      elements: { line: { tension: 0.4 } },

      responsive: true,

      maintainAspectRatio: false

    }

  });

}

drawSparkline("eurusdChart", [1.0850, 1.0851, 1.0849, 1.0852, 1.0853], "#3b82f6");

drawSparkline("btcusdChart", [67000, 67200, 67150, 67300, 67280], "#f97316");

drawSparkline("aaplChart", [180, 181, 182, 181.5, 182.45], "#10b981");

drawSparkline("goldChart", [2330, 2340, 2345, 2347, 2345.6], "#f59e0b");

</script>

  
  <script>
    const footer = document.getElementById("notchFooter");
    let scrollTimeout;
  
    function expandFooter() {
      footer.classList.add("expanded");
  
      clearTimeout(scrollTimeout);
      scrollTimeout = setTimeout(() => {
        footer.classList.remove("expanded");
      }, 2000); // Shrink after 2 seconds idle
    }
  
    window.addEventListener("scroll", expandFooter);
</script>

  <script>
        function actionMenu(){     
       document.getElementById("actionTab").style.display = "block";
        document.getElementById("actionMenufooter").style.display = "none";
       }
       function actionMenuremove(){     
       document.getElementById("actionTab").style.display = "none";
         document.getElementById("actionMenufooter").style.display = "block";
       }
        
        
        
        
       function toggleDropdown(element) {
  const parent = element.closest('.dropdown-item');
  document.querySelectorAll('.dropdown-item').forEach(item => {
    if (item !== parent) item.classList.remove('open');
  });
  parent.classList.toggle('open');
} 
        
        
    </script>


  


  

</body>

</html>
