<?php

// Show PHP errors (for debugging)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include  'db.php';





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




<!--NOTIFICATION MESSAGE--->

<?php
/*$unread = $conn->prepare("SELECT COUNT(*) AS unread FROM notifications WHERE user_id=? AND is_read=0");
$unread->bind_param("i", $user_id);
$unread->execute();
$count_result = $unread->get_result()->fetch_assoc();
$unread_count = $count_result['unread'];*/
?>         
  <?php /*if ($unread_count > 0):*/ ?>
  <?php /*endif; */?> 

<!--END-->


<?php require '../../tradebotsystem/db.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>XcorPs</title>
        <!-- FAVIVON -->
        <link rel="shortcut icon" href="images/favicon.png" type="image/png">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <lin href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        
        <!--START CANDLE STICK SCRIPT-->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial"></script>
  <!--END CANDLE STICK SCRIPT-->
<script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>

    <link rel="stylesheet" href="css/inner-style.css" />
    
  <style>
  
  
       @media (max-width:500px){
    .image-header h3{
        font-size :17px;
        color:peru;
        margin-top :150px;
        position:absolute;
    }
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
          
          
          #bot-controls.disabled {

      pointer-events: none;

      opacity: 0.2;

    }

    #bot-controls {

      margin-top: 20px;

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
    }

    .extra i {
      font-size: 13px;
      margin-bottom: 4px;
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

     
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #;
      color: #ffffff;
      display: fle;
      flex-direction: column;
      min-height: 100vh;
    }

    

    main {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 1rem;
      padding: ;
      flex: 1;
    }

    .card {
      background-color: #1e222d;
      border-radius: 12px;
      padding: 2rem 15px;
      margin-bottom: 1rem;
    }

    .chart {
      height: 300px;
      background: linear-gradient(to right, #22d3ee, #2563eb);
      border-radius: 10px;
      margin-top: 1rem;
    }

    .form-group {
      margin-bottom: 0.75rem;
    }

    label {
      display: block;
      margin-bottom: 0.4rem;
    }

    input, select, button {
      width: 100%;
      padding: 0.5rem;
      border-radius: 6px;
      border: none;
      margin-top: 0.2rem;
    }

    button {
      font-weight: bold;
      cursor: pointer;
      background-color: #22c55e;
      height:30px;
      color: white;
    }

    button.sell {
      background-color: #ef4444;
    }

    .section-title {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      font-weight: bold;
    }

    table {
      width: 100%;
      font-size: 0.85rem;
      border-collapse: collapse;
    }

    th, td {
      padding: 0.4rem;
      border-bottom: 1px solid #2c2f36;
      font-size:13px;
      font-weight:800;
    }

    .bot-status {
      background-color: #10b981;
      color:#000;
      padding: 0.4rem 0.6rem;
      border-radius: 4px;
      font-size: 1.70rem;
      font-weight:700;
      text-align: center;
      margin-bottom: 0.8rem;
    }

    footer {
      background-color: #161b22;
      text-align: center;
      padding: 1rem;
      font-size: 0.85rem;
      border-top: 1px solid #2c2f36;
    }

    @media screen and (max-width: 768px) {
      main {
        grid-template-columns: 1fr;
      }
    }
    
    .tradefig{
        text-align:center;
    }
    #trade-value {
    font-size: 35px;
    font-weight: 800;
    margin-bottom: 1rem;  
  }
  #trade-change {
    font-size: 25px;
    font-weight:800;
  }
  .up {
    color: #22c55e; /* bright green */
  }
  .down {
    color: #ef4444; /* bright red */
  }
  
 
    #disabledDiv {
      pointer-events: none;       /* Prevent interaction */
    opacity: 0.3;               /* Make it look disabled */
    user-select: none;    
      padding: 10px;
    }

    #disabledDiv.enabled {
      pointer-events: auto;
      opacity: 1;
      user-select: auto;
    }
     #hiddenDiv {
      display: none;
      margin-top: 10px;
      padding: 50px 10px;
      background-color: #ddd;
      border:2px solid greenyellow;
      border-radius:10px;
      font-weight:800;
      font-size:17px;
      color:#000;
    }
    #hiddenDiv.visible{
      display: block;
    }
    #couponBox{
        display: block;
    }
    #couponBox.invisible{
        display: none;
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




.hood{
     opacity: 0;
  transform: translateY(-100px); /* Start 100px left */
  animation: slideUp 1s ease-out forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0); /* Move to original position */
  }
}

          
          
          
          
          
          
          
          
          
          
          p {
      margin: 6px 0;
    }

    .toggle-switch {
      width: 60px;
      height: 30px;
      background: #555;
      border-radius: 30px;
      position: relative;
      margin-top: 12px;
      transition: background 0.3s;
      cursor: not-allowed;
    }

    .toggle-switch.active {
      background: #0af;
      cursor: pointer;
    }

    .toggle-switch .slider {
      width: 26px;
      height: 26px;
      background: #fff;
      border-radius: 50%;
      position: absolute;
      top: 2px;
      left: 2px;
      transition: left 0.3s;
    }

.toggle-switch.active .slider {
      left: 32px;
    }

    #toggleStatus {
      margin-top: 10px;
      font-size: 14px;
      color: #aaa;
    }

    .bot-list {
      margin-top: 30px;
    }

    .bot-item {
      background: #1e1e1e;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 20px;
      box-shadow: 0 0 8px rgba(0,0,0,0.4);
    }

    .bot-item strong {
      font-size: 18px;
      color: #fff;
    }

    .bot-item p {
      font-size: 14px;
      margin: 8px 0;
    }

    .bot-item form button {
      margin-top: 10px;
      background: #0af;
      color: white;
      border: none;
      padding: 10px 20px;
border-radius: 6px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .bot-item form button:hover {
      background: #08c;
    }

    @media (max-width: 600px) {
      
      .toggle-switch {
        width: 50px;
        height: 26px;
      }

      .toggle-switch .slider {
        width: 22px;
        height: 22px;
      }

      .toggle-switch.active .slider {
        left: 26px;
      }
    }
          
          
          

          
          
          
           


.
    . .channel-list {
      background-color: #1a1a1a;
      border-radius: 16px;
      padding: 5px 17px;
      box-shadow: 0 0 10px rgba(0,0,0,0.5);
    }

    .channel {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 7px;
      border-bottom: 1px solid #333;
      cursor: pointer;
    }
      .channel:last-child {
      border-bottom: none;
    }

    .channel-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .icon {
      width: 30px;
      height: 30px;
      background: #333;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 16px;
    }

    .details {
      display: flex;
      flex-direction: column;
    }

    .title {
      font-weight: bold;
    }

    .desc {
      font-size: 13px;
      color: #aaa;
    }

    .unavailable {
      color: #6c9bcf;
      font-size: 13px;
    }

    .arrow {
      font-size: 18px;
      color: white;
    }

    .channel.unavailable .arrow {
      display: none;
    }




     


.notice-box2 {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  backdrop-filter: blur(3px);
  background-color: #020203b5; /* Soft dark overlay */
  align-items: center;
  justify-content: center;
  z-index: 9999;
  display: none; /* default hidden */
}
    
        .starbox{
                    background-color: #111;
      padding: 20px 20px;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;
      position: fixed;
    bottom:50px;
    left: 0;
    right: 0;
    text-align: left;
    z-index: 100;
  transform-origin: center;
                animation: slideUp 1s ease-out forwards;
}

@keyframes slideUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
      
          
          
          
          
          
          
         
          
          

    .videos {

      margin-top: 40px;

    }

    .videos h5 {

      margin-bottom: 20px;

      color: #0af;

      text-align: center;

    }

    .video-grid {

      display: grid;

      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

      gap: 20px;

    }

    .video {

      background: #1a1a1a;

      padding: 16px;

      border-radius: 16px;

    }

    iframe {

      width: 100%;

      border-radius: 12px;

      height: 180px;

    }

    @media screen and (max-width: 500px) {

      header h1 {

        font-size: 1.8rem;

      }

    }
          
          
          
          
        #news-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(400px, 2fr));
  gap: 20px;
  margin-left:-15px;
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
    <div onclick="window.location.href='../withdrawal/client/withdrawal.php'">➖ Withdraw Funds</div>
    <div>🔁 Transfer</div>
    <div>📉 Sell Stocks</div>
    <div>🤖 Auto-Invest</div>
  </div>
</div>

<div class="item dropdown-item">
  <span onclick="toggleDropdown(this)">Upgrade Account</span> ›
  <div class="dropdown">
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
    <div onclick="window.location.href='trade_station.php'"  >Trade Stations</div>
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
    <div class="item"><span>💵 Bonuses</span> <span class="new-badge">New</span></div>
  </div>
  
<m onclick="actionMenuremove();" style="justify-content:center;align-items:center;display:flex;"><i class="fa fa-times" style="background:#951f19;border:3px solid #9; padding:10px 12px;border-radius:30px;font-size:25px;font-weight:900;color:#000;"></i></m>
<br><br><br><br>
  
        
        
  </div>
      </div>
        </div>
        
  
  
  
  





  <script>
    function validateCoupon() {
      const code = document.getElementById('coupon').value;

      fetch('../../tradebotsystem/validate_coupon.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'code=' + encodeURIComponent(code)
      })
      .then(res => res.json())
      .then(data => {
        const msg = document.getElementById('status');
        const botControls = document.getElementById('bot-controls');

        if (data.valid) {
  botControls.classList.remove('disabled');
  msg.innerHTML = data.message;
  msg.style.color = 'green'; // Optional: overall color
} else {
  botControls.classList.add('disabled');
  msg.innerHTML = "<p>INVALID TRADE COUPON.</p>The entered coupon code is not valid. <br><br><u>Please purchase a signal plan to obtain a valid coupon code and activate trading.</u> <br><br><a href='/support' style='color:#000;font-weight:700;background:#007bff;padding:10px 50px;border-radius:10px;'>GET TRADE SIGNALS</a>";
  msg.style.color = 'red';
}
      });
    }
  </script>

          
       
          
          <header style="float: ;">&nbsp;&nbsp;
    <ul style="margin-top:-10px;">
      <a href=""><h5 style="font-weight: 800;color:#ccccccf1;float: left;">&nbsp;&nbsp;&nbsp;&nbsp;X<span style="background: linear-gradient(to right, #bfffd5,
                                    #7bff55,#2D9E45, #6affa2); -webkit-text-fill-color: transparent; -webkit-background-clip: text;font-weight: 800;">corPs</span>&nbsp;&nbsp;<i class="fa fa-exchange" style=""></i>&nbsp;&nbsp;<b style="font-size:12px;color:peru;">Our trade station</b></h5></a>
    </ul>
    <ul style="float: right;">
  <i class="fa fa-podcast" style="font-size:15px;font-weight:800;color:#ffffff;"></i>  &nbsp;&nbsp;&nbsp;&nbsp;
    <a href=""></a>&nbsp;&nbsp;&nbsp;&nbsp;
    <a href="../logout/logout.php"><i class="fa fa-sign-out" style="font-size:15px;font-weight:800;color:red;"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </ul>
</header>


<div class="hood">
  <!-- Image at the top of the page -->
  <div class="image-header"  style=" background: url('../../imgg/GIF(1).gif') no-repeat center center/cover;
        ">
    <small id="google_translate_element" style="margin-top:0px;"></small>
      <h3 style="font-size:16px;">WELCOME::&nbsp;&nbsp;<?= $user['user_name']; ?>!</h3>
</h3>
      <ul style="">
        <?= $user['email']; ?>
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
      <h4 style="text-align:center;color#ddd;font-weight:800;">TRADE STATION<br><br><p style="font-size:14px;color:#fff;">watch your trade progressively</p></h4>
      
          
          
          
          
          

          

       
         
    
          
          
          
          
      
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
    
          
          
         
      
      
<h5 style="text-align:center;">ACTIVATE WITH TRADE COUPON CODE</h5>
          <div style="background:#ddd ; 
padding: 10px 10px;

      border:2px solid greenyellow;

      border-radius:10px;">
          
          <p id="status" style="
      font-weight:500;

      font-size:14px;

      color:#000;">TradeBot Status : <span style="color:red;">DISABLED.</span><br><b style="color:#000;">please enter a valid trade coupon to activate trading</b></p>
                                
  <input type="text" style="background:#fff; border:1px solid #000;color:#000;text-align:center;font-weight:700;" id="coupon" placeholder="Enter Coupon Code" maxlength="4">
          <br><br>
  <m onclick="validateCoupon()" style="  cursor:pointer;background:#80e171;color:#000;padding:10px 50px;text-align:center;font-weight:800;border-radius:10px;  " >Activate</m>
          <br><br>
               <p style="color:#000;">For assistance, contact <a href="" style="">support@yourcompany.com.</a></p>
  </div>
          
    
          
          
          

<div class="cards">

      <div class="card">💰 Balance<br><strong><?= $user['currency']; ?> <?= $user['balance']; ?></strong></div>

      <div class="card">📈 Equity<br><strong><?= $user['currency']; ?> <?= $user['equity']; ?></strong></div>

      <div class="card">🧾 Open Trades<br><strong><?= $user['open_trades']; ?></strong></div>

      <div class="card">🪙 Total P&L<br><strong><?= $user['currency']; ?> <?= $user['pnl']; ?></strong></div>

    </div>
  
          
          
          
          <?php
ob_start();
//session_start();
include '../../authenticate/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../authenticate/login.html");
    exit;
}

// Fetch user data
$sql = "SELECT u.user_name, u.email, u.current_bot_id, u.toggle_enabled, b.bot_name
        FROM users u
        LEFT JOIN bots b ON u.current_bot_id = b.id
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Redirect to subscription page if no bot
//if (empty($user['current_bot_id'])) {
   // header("Location: subscribe.php");
   // exit;
//}
?>
<h2>Welcome, <?php echo htmlspecialchars($user['user_name']); ?></h2>
  <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
  <p>Subscribed Bot: <strong><?php echo $user['bot_name'] ?? 'None'; ?></strong></p>

  <h3>Bot Toggle</h3>
  <div class="toggle-switch <?php echo ($user['toggle_enabled'] ? 'active' : '') . (!$user['toggle_enabled'] ? ' disabled' : ''); ?>" onclick="toggleBot()" id="botToggle">
    <div class="slider"></div>
  </div>
  <p id="toggleStatus"><?php echo $user['toggle_enabled'] ? 'Bot is ready to toggle.' : 'Toggle not yet approved.'; ?></p>


  <script>
  function toggleBot() {
    const toggle = document.getElementById("botToggle");

    // If the toggle is disabled by admin
    if (toggle.classList.contains("disabled")) {
      alert("Your toggle hasn't been approved by the admin yet.");
      return;
    }

    // Toggle the visual state
    const isNowActive = toggle.classList.toggle("active");

    // Update the status text
    const status = document.getElementById("toggleStatus");
    status.innerText = isNowActive
      ? "Bot is now ON."
      : "Bot is now OFF.";

    // Send AJAX to notify backend (optional, can be stored in session or log)
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "notify_toggle.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("state=" + (isNowActive ? 1 : 0));
  }
</script>
          
        
          

  <div id="bot-controls" class="disabled">
      

          
      
    

    
   <div class="form-group">
  <label for="log">Bot Status Log</label>
  <div id="bot-log" style="background:#0d1117; border:1px solid greenyellow; padding:0.5rem; height:100px; overflow-y:auto; border-radius:6px; font-size:1.35rem;font-weight:800;color:peru;"></div>
</div>

<div class="form-group">
  <label>📊 Bot Profit Graph</label>
  <canvas id="profitChart" height="160"></canvas>
</div>


<!--<div class="form-group">
  <label for="candlestick">Profit Candlestick Chart</label>
  <div id="candlestick-chart" style="height: 300px;"></div>
</div>-->

<ul class="tradefig">
<h5>Trade Figures</h5>
<div id="trade-value">00.00</div>
<div id="trade-change"></div>
</ul>

  
    <!-- Left: Chart + Bot -->
    <div>
        
        <div class="card">
        <div class="section-title">🤖 Bot Trading Panel</div>
        <div class="bot-status">Bot Status: <strong>Running</strong></div>

        <div class="form-group">
          <label for="strategy">Strategy</label>
          <select id="strategy" style="color:green;font-weight:800;">
            <option>Grid Trading</option>
            <option>Scalping</option>
            <option>RSI Bot</option>
            <option>Mean Reversion</option>
          </select>
        </div>

        <div class="form-group">
          <label for="bot-investment">Initial Investment (USDT)</label>
          <input type="number" id="bot-investment" placeholder="e.g. 500" style="color:green;font-weight:800;"/>
        </div>
          <br>

        <button id="start-btn">Start Bot</button>
        <br><br>
        <button id="stop-btn" class="sell" style="margin-top: 0.5rem;">Stop Bot</button>
      </div>
        
        
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

        
      <div class="card">
        <div class="section-title">BTC/USD Chart</div>
        <div class="chart"></div>
      </div>

      
    </div>

    <!-- Right: Manual Trade + Portfolio -->
    <div>
      <div class="card">
        <div class="section-title">📥 Manual Trade</div>
        <div class="form-group">
          <label for="type">Order Type</label>
          <select id="type" style="color:green;font-weight:800;">
            <option>Market</option>
            <option>Limit</option>
          </select>
        </div>
        <div class="form-group">
          <label for="amount">Amount</label>
          <input type="number" id="amount" placeholder="e.g. 0.2 BTC" style="color:green;font-weight:800;"/>
        </div>
        <button>Buy</button>
        <button class="sell" style="margin-top: 0.5rem;">Sell</button>
      </div>

      <div class="card">
        <div class="section-title">💼 Portfolio</div>
        <table>
          <thead>
            <tr><th>Asset</th><th>Balance</th></tr>
          </thead>
          <tbody>
            <tr><td>BTC</td><td>0.75</td></tr>
            <tr><td>USDT</td><td>3,200</td></tr>
          </tbody>
        </table>
      </div>

<div class="chart" id="tradingview-widget"></div>
<br>
            
            
            
            </div>
          </div>
          
          
          
          
          
          
          
          <?php

    include '../../authenticate/db.php';

    $sql = "SELECT * FROM bots";

    $result = $conn->query($sql);

  ?>

  <div class="bot-list">

    <h5 style="color:#0af;text-align:center;font-weight:800;">Select a Bot to Subscribe</h5>

    <?php while ($row = $result->fetch_assoc()): ?>

      <div class="bot-item">

        <strong><?php echo $row['bot_name']; ?></strong>

        <p><?php echo $row['description']; ?></p>

        <p>Price: $<?php echo $row['price']; ?></p>

       <form method="POST" action="../../botsubscriptionsystem/payment.php">
      <!--<form method="GET" action="bot_details.php">--->

          <input type="hidden" name="bot_id" value="<?php echo $row['id']; ?>">

          <button type="submit">Subscribe</button>

        </form>

      </div>

    <?php endwhile; ?>

</div>
        
        
<section class="videos">
  <h5>🎥 Learn How to Profit with Trade Bots</h5>
  <div class="video-grid">
    <div class="video">
      <h4>Getting Started with Bots</h4>
            <iframe src="https://www.youtube.com/embed/zjgsF2xzIzI?si=8x2_gGmTtaf7rUEN" allowfullscreen></iframe>
    </div>
    <div class="video">
      <h4>Daily Profit Strategy</h4> 
      <iframe src="https://www.youtube.com/embed/4mvJ5Z9Qpe8?si=GGqlSPv6XUevAdML" allowfullscreen></iframe>
    </div>
    <div class="video">
      <h4>Smart Risk Management</h4>  
      <iframe src="https://www.youtube.com/embed/55OPHZbFeeY?si=2uioHwgs_ia6PaMV" allowfullscreen></iframe>
    </div>
  </div>
</section>
        
        
        
        
        
        
        
              <div class="card">
        <div class="section-title">📜 Trade History</div>
        <table>
          <thead>
            <tr><th>Time</th><th>Type</th><th>Price</th><th>Amount</th></tr>
          </thead>
          <tbody>
            <tr><td>14:32</td><td>Buy</td><td>$67,500</td><td>0.1 BTC</td></tr>
            <tr><td>13:58</td><td>Sell</td><td>$67,900</td><td>0.05 BTC</td></tr>
          </tbody>
        </table>
      </div>
          
          
          
          
          
  <!-----------NEWS--------------->
  <div id="news-container"></div>
          

  
  
  
  </div></div>
  
    
        <br><br><br><br><br><br>
        
         <footer id="notchFooter">
    <div class="footer-content">
      <a href="../dashboard/dashboard.php" class="extra"><i class="fa fa-bank"></i><br><b>HOME</b></a>
      <a href="trade_station.php" class="extra"><i class="fa fa-line-chart"></i><br><b>TRADE</b></a>
      
      <!-- Center icon (absolute, centered) -->
      <div class="center-icon-wrapper">
        <a href="#" onclick="actionMenu();"><i class="fa fa-exchange center-icon"></i></a>
      </div>
      
      <a href="../signal/signalupdate.php" class="extra"><i class="fa fa-signal"></i><br><b>SIGNAL</b></a>
      <a href="../community/community.php" class="extra"><i class="fa fa-users"></i><br><b>COMMUNITY</b></a>
    </div>
  </footer>

  
<!-- </div>
 </div>
  </div>-->
  
  
  
          
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

 
        
        <script src="../NEWS/cachedNEWS/gnews.js"></script>
          
  

    
 
  




<script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>

<script type="text/javascript">

  new TradingView.widget({

    "container_id": "tradingview-widget",

    "width": "100%",

    "height": 300,

    "symbol": "BINANCE:BTCUSDT",

    "interval": "1",

    "timezone": "Etc/UTC",

    "theme": "dark",

    "style": "1",

    "locale": "en",

    "toolbar_bg": "#0d1117",

    "enable_publishing": false,

    "allow_symbol_change": false,

    "hide_top_toolbar": false,

    "hide_legend": true,

    "save_image": false,

    "studies": ["MACD@tv-basicstudies"],

  });

</script>











<script>

  const botStatus = document.querySelector('.bot-status');

  const startBtn = document.querySelector('button:not(.sell)');

  const stopBtn = document.querySelector('button.sell');

  const strategySelect = document.getElementById('strategy');

  const investmentInput = document.getElementById('bot-investment');

  const botLog = document.getElementById('bot-log');

  let botRunning = false;

  let tradeInterval;

  let profit = 0;

  let profitData = [];

  let profitChart;

  // Log to bot log area

  function logToBotUI(message) {

    const time = new Date().toLocaleTimeString();

    const entry = document.createElement('div');

    entry.textContent = `[${time}] ${message}`;

    botLog.appendChild(entry);

    botLog.scrollTop = botLog.scrollHeight;

  }

  // Simulate trade and update profit

  function simulateTrade() {

    const price = (67000 + Math.random() * 500).toFixed(2);

    const amount = (Math.random() * 0.05 + 0.01).toFixed(4);

    const pnl = parseFloat((Math.random() * 10 - 5).toFixed(2)); // -5 to +5

    profit += pnl;

    profit = parseFloat(profit.toFixed(2));

    updateProfitChart(profit);

    logToBotUI(`Trade executed: Buy ${amount} BTC at $${price}, PnL: ${pnl >= 0 ? '+' : ''}${pnl}`);

  }

  function updateProfitChart(currentProfit) {

    const timeLabel = new Date().toLocaleTimeString();

    profitChart.data.labels.push(timeLabel);

    profitChart.data.datasets[0].data.push(currentProfit);

    if (profitChart.data.labels.length > 20) {

      profitChart.data.labels.shift();

      profitChart.data.datasets[0].data.shift();

    }

    profitChart.update();

  }

  function startBot() {

    const strategy = strategySelect.value;

    const investment = investmentInput.value;

    if (!investment || investment <= 0) {

      alert('Please enter a valid investment amount.');

      return;

    }

    botRunning = true;

    localStorage.setItem('botRunning', 'true');

    localStorage.setItem('strategy', strategy);

    localStorage.setItem('investment', investment);

    localStorage.setItem('profit', profit.toString());

    updateBotUI();

    logToBotUI(`Bot started with strategy "${strategy}" using $${investment}`);

    tradeInterval = setInterval(simulateTrade, 2000);

  }

  function stopBot() {

    botRunning = false;

    clearInterval(tradeInterval);

    localStorage.setItem('botRunning', 'false');

    updateBotUI();

    logToBotUI('Bot stopped.');

  }

  function updateBotUI() {

    if (botRunning) {

      botStatus.innerHTML = 'Bot Status: <strong>Running</strong>';

      botStatus.style.backgroundColor = '#10b981';

      startBtn.disabled = true;

      stopBtn.disabled = false;

    } else {

      botStatus.innerHTML = 'Bot Status: <strong>Stopped</strong>';

      botStatus.style.backgroundColor = '#ef4444';

      startBtn.disabled = false;

      stopBtn.disabled = true;

    }

  }

  // Init Chart.js

  function initProfitChart() {

    const ctx = document.getElementById('profitChart').getContext('2d');

    profitChart = new Chart(ctx, {

      type: 'line',

      data: {

        labels: [],

        datasets: [{

          label: 'Profit (USDT)',

          data: [],

          borderColor: '#22c55e',

          backgroundColor: 'rgba(34, 197, 94, 0.1)',

          tension: 0.3

        }]

      },

      options: {

        responsive: true,

        scales: {

          y: {

            beginAtZero: true

          }

        }

      }

    });

  }

  window.addEventListener('load', () => {
  initProfitChart();

  // FORCE bot to start as stopped
  botRunning = false;
  localStorage.setItem('botRunning', 'false'); // override saved running state

  const savedStrategy = localStorage.getItem('strategy');
  const savedInvestment = localStorage.getItem('investment');
  profit = parseFloat(localStorage.getItem('profit')) || 0;

  if (savedStrategy) strategySelect.value = savedStrategy;
  if (savedInvestment) investmentInput.value = savedInvestment;

  updateBotUI();
  logToBotUI('Bot is currently stopped. Click "Start Bot" to begin.');
});

  // Event Listeners

  startBtn.addEventListener('click', startBot);

  stopBtn.addEventListener('click', stopBot);

</script>

<script>

  let tradeValue = 1000;

  let intervalId = null;

  function updateTradeValue() {

    const change = (Math.random() - 0.5) * 60;

    const newValue = Math.max(0, tradeValue + change);

    const diff = newValue - tradeValue;

    tradeValue = newValue;

    const valueEl = document.getElementById('trade-value');

    const changeEl = document.getElementById('trade-change');

    valueEl.textContent = `${tradeValue.toFixed(2)}`;

    if (diff > 0) {

      changeEl.innerHTML = `<span class="up">▲ +${diff.toFixed(2)}</span>`;

    } else if (diff < 0) {

      changeEl.innerHTML = `<span class="down">▼ ${diff.toFixed(2)}</span>`;

    } else {

      changeEl.textContent = '';

    }

  }

  function startBot() {

    if (!intervalId) {

      intervalId = setInterval(updateTradeValue, 300);

      document.getElementById('start-btn').disabled = true;

      document.getElementById('stop-btn').disabled = false;

    }

  }

  function stopBot() {

    if (intervalId) {

      clearInterval(intervalId);

      intervalId = null;

      document.getElementById('start-btn').disabled = false;

      document.getElementById('stop-btn').disabled = true;

    }

  }

  // Start immediately on page load

  //startBot();

  document.getElementById('start-btn').addEventListener('click', startBot);

  document.getElementById('stop-btn').addEventListener('click', stopBot);


        
        
  
  
  
  
  
  
  
  
  
        
        
        
        
        
        
        
        
        </script>













  

        

        

        

 



        
</body>
</html>